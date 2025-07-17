<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentResponse;
use App\Models\Exam;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentExamController extends Controller
{

    public function index()
    {
        $academicYearId = session('academic_year_id');
        $studentId = Auth::id();

        $studentClass = DB::table('student_class')
            ->where('student_id', $studentId)
            ->where('academic_year_id', $academicYearId)
            ->value('class_id');

        if (!$studentClass) {
            return view('student.exams.index', [
                'exams' => collect(),
                'examStatus' => [],
                'header_title' => 'Mes examens'
            ]);
        }

        $exams = Exam::where('class_id', $studentClass)
            ->where('academic_year_id', $academicYearId)
            ->with('subject')
            ->get();

        // Pour chaque examen, on récupère la tentative de l'étudiant (ou null)
        $examStatus = [];
        foreach ($exams as $exam) {
            $attempt = DB::table('student_exam_results')
                ->where('student_id', $studentId)
                ->where('exam_id', $exam->id)
                ->first();

            $now = now();
            if ($now->lt($exam->start_time)) {
                $status = 'not_started'; // Pas encore commencé (date future)
            } elseif ($attempt && $attempt->submitted_at) {
                $status = 'submitted'; // Déjà soumis
            } elseif ($now->gt($exam->end_time)) {
                $status = 'expired'; // Date limite dépassée
            } elseif ($attempt && !$attempt->submitted_at) {
                $status = 'in_progress'; // Commencé mais pas soumis (si tu gères le brouillon)
            } else {
                $status = 'available'; // Peut commencer
            }
            $examStatus[$exam->id] = [
                'status' => $status,
                'score' => $attempt->score ?? null,
                'submitted_at' => $attempt->submitted_at ?? null,
            ];
        }

        return view('student.exams.index', [
            'exams' => $exams,
            'examStatus' => $examStatus,
            'header_title' => 'Mes examens'
        ]);
    }




    public function show($examId)
    {
        $exam = Exam::with('questions.choices')->findOrFail($examId);

        // Cherche une tentative existante pour cet étudiant et cet exam
        $attempt = DB::table('student_exam_results')
            ->where('student_id', Auth::id())
            ->where('exam_id', $examId)
            ->first();

        if ($attempt && $attempt->submitted_at) {
            // Examen déjà soumis
            return redirect()->route('student.exams.index')
                ->with('error', 'Vous avez déjà soumis cet examen.');
        }

        // Si tu stockes l'heure de début dans la table, utilise-la
        $startedAt = $attempt ? $attempt->created_at : now();
        $elapsed = now()->diffInMinutes($startedAt);
        $remaining_time = max(0, $exam->duration_minutes - $elapsed);

        return view('student.exams.show', [
            'exam' => $exam,
            'header_title' => $exam->title,
            'remaining_time' => $remaining_time
        ]);
    }



    private function calculateRemainingTime($response, $exam)
    {
        $elapsed = now()->diffInMinutes($response->started_at);
        return max(0, $exam->duration_minutes - $elapsed);
    }



    // public function submit(Request $request, $examId)
    // {
    //     $studentId = Auth::id();
    //     $exam = Exam::with('questions.choices')->findOrFail($examId);

    //     // Empêcher la double soumission
    //     $alreadySubmitted = DB::table('student_exam_results')
    //         ->where('student_id', $studentId)
    //         ->where('exam_id', $examId)
    //         ->exists();

    //     if ($alreadySubmitted) {
    //         return redirect()->route('student.exams.index')
    //             ->with('error', 'Vous avez déjà soumis cet examen.');
    //     }

    //     // Validation
    //     $rules = [];
    //     foreach ($exam->questions as $question) {
    //         $rules["answers.{$question->id}"] = 'required|exists:choices,id';
    //     }
    //     $request->validate($rules);

    //     $score = 0;
    //     foreach ($exam->questions as $question) {
    //         $choiceId = $request->input("answers.{$question->id}");
    //         $choice = $question->choices->where('id', $choiceId)->first();

    //         // DEBUG : Log les valeurs pour vérifier
    //         Log::info("Q{$question->id} - Choice: " . ($choice ? $choice->id : 'null') . " - is_correct: " . ($choice ? $choice->is_correct : 'null'));

    //         $isCorrect = $choice && (intval($choice->is_correct) === 1);

    //         if ($isCorrect) {
    //             $score += $question->points;
    //         }

    //         // Stocker chaque réponse dans student_responses
    //         \App\Models\StudentResponse::create([
    //             'student_id'   => $studentId,
    //             'exam_id'      => $examId,
    //             'question_id'  => $question->id,
    //             'choice_id'    => $choiceId,
    //             'score'        => $isCorrect ? $question->points : 0,
    //             'started_at'   => now(), // ou la vraie date de début
    //             'submitted_at' => now(),
    //         ]);
    //     }

    //     // Stocker le score total dans student_exam_results
    //     DB::table('student_exam_results')->insert([
    //         'student_id'    => $studentId,
    //         'exam_id'       => $examId,
    //         'score'         => $score,
    //         'submitted_at'  => now(),
    //         'created_at'    => now(),
    //         'updated_at'    => now(),
    //     ]);

    //     if ($request->ajax() || $request->wantsJson()) {
    //         return response()->json([
    //             'success' => true,
    //             'score' => $score,
    //             'redirect' => route('student.exams.index')
    //         ]);
    //     }

    //     return redirect()->route('student.exams.index')
    //         ->with('score', $score)
    //         ->with('success', "Examen soumis avec succès !");
    // }

    public function submit(Request $request, $examId)
    {
        $studentId = Auth::id();
        $exam = Exam::with('questions.choices')->findOrFail($examId);

        // Empêcher la double soumission
        $alreadySubmitted = DB::table('student_exam_results')
            ->where('student_id', $studentId)
            ->where('exam_id', $examId)
            ->exists();

        if ($alreadySubmitted) {
            return redirect()->route('student.exams.index')
                ->with('error', 'Vous avez déjà soumis cet examen.');
        }

        // Validation : accepter les réponses vides (question non cochée = fausse)
        $rules = [];
        foreach ($exam->questions as $question) {
            $rules["answers.{$question->id}"] = 'nullable|exists:choices,id';
        }
        $request->validate($rules);

        $score = 0;
        foreach ($exam->questions as $question) {
            $choiceId = $request->input("answers.{$question->id}", null);
            $isCorrect = false;
            $choice = null;

            if ($choiceId) {
                $choice = $question->choices->where('id', $choiceId)->first();
                $isCorrect = $choice && (intval($choice->is_correct) === 1);
            }
            if ($isCorrect) {
                $score += $question->points;
            }

            // Stocker chaque réponse dans student_responses
            \App\Models\StudentResponse::create([
                'student_id'   => $studentId,
                'exam_id'      => $examId,
                'question_id'  => $question->id,
                'choice_id'    => $choiceId,
                'score'        => $isCorrect ? $question->points : 0,
                'started_at'   => now(),
                'submitted_at' => now(),
            ]);
        }

        // Stocker le score total dans student_exam_results
        DB::table('student_exam_results')->insert([
            'student_id'    => $studentId,
            'exam_id'       => $examId,
            'score'         => $score,
            'submitted_at'  => now(),
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'score' => $score,
                'redirect' => route('student.exams.index')
            ]);
        }

        return redirect()->route('student.exams.index')
            ->with('score', $score)
            ->with('success', "Examen soumis avec succès !");
    }


    public function result($examId)
    {
        $studentId = Auth::id();
        $exam = Exam::with('questions.choices')->findOrFail($examId);

        $attempt = DB::table('student_exam_results')
            ->where('student_id', $studentId)
            ->where('exam_id', $examId)
            ->first();

        if (!$attempt) {
            return redirect()->route('student.exams.index')->with('error', "Vous n'avez pas passé cet examen.");
        }

        // Récupérer toutes les réponses de l'étudiant pour cet examen
        $responses = DB::table('student_responses')
            ->where('student_id', $studentId)
            ->where('exam_id', $examId)
            ->get()
            ->keyBy('question_id');

        return view('student.exams.result', [
            'exam' => $exam,
            'attempt' => $attempt,
            'responses' => $responses,
            'header_title' => 'Mes réponses et résultat'
        ]);
    }
}
