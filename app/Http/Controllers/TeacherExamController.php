<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AssignClassTeacherModel;
use App\Models\SubjectModel;
use App\Models\Exam;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TeacherExamController extends Controller
{
    public function create()
    {
        $teacherId = Auth::id();
        $academicYearId = session('academic_year_id');

        $classes = AssignClassTeacherModel::where([
            'teacher_id' => $teacherId,
            'academic_year_id' => $academicYearId
        ])->with('class')->get()->pluck('class');

        return view('teacher.exams.create', [
            'classes' => $classes,
            'header_title' => 'Créer un questionnaire'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'class_id' => 'required|exists:class,id',
            'subject_id' => 'required|exists:subject,id',
            'question_count' => 'required|integer|min:1',
            'choices_per_question' => 'required|integer|min:2|max:6',
            'duration_minutes' => 'required|integer|min:5',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $exam = Exam::create([
            'teacher_id' => Auth::id(),
            'academic_year_id' => session('academic_year_id'),
            ...$request->only([
                'title',
                'class_id',
                'subject_id',
                'question_count',
                'choices_per_question',
                'duration_minutes',
                'start_time',
                'end_time'
            ])
        ]);

        return redirect()->route('teacher.exams.questions.create', $exam->id);
    }

    // TeacherExamController.php
    public function index()
    {
        $academicYearId = session('academic_year_id');

        $exams = Exam::where('teacher_id', Auth::id())
            ->where('academic_year_id', $academicYearId)
            ->with(['class', 'subject'])
            ->paginate(10);

        return view('teacher.exams.index', [
            'exams' => $exams,
            'header_title' => 'Mes questionnaires',
            'academic_years' => AcademicYear::orderBy('start_date', 'desc')->get(),
            'current_academic_year' => AcademicYear::find($academicYearId)
        ]);
    }



    public function getSubjectsByClass($classId)
    {
        try {
            $teacherId = Auth::id();
            $academicYearId = session('academic_year_id');

            $subjects = AssignClassTeacherModel::where('teacher_id', $teacherId)
                ->where('class_id', $classId)
                ->where('academic_year_id', $academicYearId)
                ->where('is_delete', 0)
                ->with('subject')
                ->get()
                ->pluck('subject')
                ->filter() // enlève les null
                ->unique('id')
                ->values();

            return response()->json($subjects->map(function ($subject) {
                return [
                    'id' => $subject->id,
                    'name' => $subject->name
                ];
            }));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur serveur : ' . $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        $exam = Exam::findOrFail($id);
        $teacherId = Auth::id();
        $academicYearId = session('academic_year_id');

        $classes = AssignClassTeacherModel::where([
            'teacher_id' => $teacherId,
            'academic_year_id' => $academicYearId
        ])->with('class')->get()->pluck('class');

        // Récupérer les matières pour la classe de l'examen
        $subjects = AssignClassTeacherModel::where([
            'teacher_id' => $teacherId,
            'class_id' => $exam->class_id,
            'academic_year_id' => $academicYearId
        ])->with('subject')->get()->pluck('subject');

        return view('teacher.exams.edit', [
            'exam' => $exam,
            'classes' => $classes,
            'subjects' => $subjects,
            'header_title' => 'Modifier l\'examen'
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'class_id' => 'required|exists:class,id',
            'subject_id' => 'required|exists:subject,id',
            'question_count' => 'required|integer|min:1',
            'choices_per_question' => 'required|integer|min:2|max:6',
            'duration_minutes' => 'required|integer|min:5',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $exam = Exam::findOrFail($id);
        $exam->update($request->only([
            'title',
            'class_id',
            'subject_id',
            'question_count',
            'choices_per_question',
            'duration_minutes',
            'start_time',
            'end_time'
        ]));

        return redirect()->route('teacher.exams.index')->with('success', 'Examen mis à jour avec succès.');
    }


    public function destroy($id)
    {
        $exam = Exam::findOrFail($id);
        $exam->delete();

        return redirect()->route('teacher.exams.index')->with('success', 'Examen supprimé avec succès.');
    }

    // public function results($examId)
    // {
    //     $exam = Exam::findOrFail($examId);

    //     // Récupérer les résultats des étudiants pour cet examen
    //     $results = DB::table('student_exam_results')
    //         ->join('users', 'users.id', '=', 'student_exam_results.student_id')
    //         ->where('exam_id', $examId)
    //         ->select('users.name', 'student_exam_results.score', 'student_exam_results.submitted_at')
    //         ->get();

    //     // Calculer des statistiques
    //     $average = $results->avg('score');
    //     $max = $results->max('score');
    //     $min = $results->min('score');

    //     return view('teacher.exams.results', [
    //         'exam' => $exam,
    //         'results' => $results,
    //         'average' => $average,
    //         'max' => $max,
    //         'min' => $min,
    //         'header_title' => 'Résultats de l\'examen'
    //     ]);
    // }

    public function results($examId)
    {
        $exam = Exam::with('questions.choices')->findOrFail($examId);

        // Score maximal possible = somme des points de toutes les questions
        $maxScore = $exam->questions->sum('points');
        // Note minimale = maxScore / 2 (arrondi à l'entier inférieur)
        $minScore = floor($maxScore / 2);

        // Récupérer les résultats des étudiants pour cet examen
        $results = DB::table('student_exam_results')
            ->join('users', 'users.id', '=', 'student_exam_results.student_id')
            ->where('exam_id', $examId)
            ->select('users.name', 'student_exam_results.score', 'student_exam_results.submitted_at')
            ->get();

        $average = $results->avg('score');
        $max = $results->max('score');
        $min = $results->min('score');

        // Taux de réussite et d'échec
        $total = $results->count();
        $success = $results->where('score', '>=', $minScore)->count();
        $fail = $total - $success;
        $successRate = $total > 0 ? round(($success / $total) * 100, 2) : 0;
        $failRate = $total > 0 ? round(($fail / $total) * 100, 2) : 0;

        // Statistiques par question : taux d'échec par question
        $questionStats = [];
        foreach ($exam->questions as $question) {
            $totalResponses = DB::table('student_responses')
                ->where('exam_id', $examId)
                ->where('question_id', $question->id)
                ->count();
            $correctResponses = DB::table('student_responses')
                ->where('exam_id', $examId)
                ->where('question_id', $question->id)
                ->where('score', '>', 0)
                ->count();
            $failResponses = $totalResponses - $correctResponses;
            $failRateQ = $totalResponses > 0 ? round(($failResponses / $totalResponses) * 100, 2) : 0;
            $questionStats[] = [
                'text' => $question->question_text,
                'failRate' => $failRateQ,
                'successRate' => 100 - $failRateQ,
            ];
        }

        return view('teacher.exams.results', [
            'exam' => $exam,
            'results' => $results,
            'average' => $average,
            'max' => $max,
            'min' => $min,
            'maxScore' => $maxScore,
            'minScore' => $minScore,
            'successRate' => $successRate,
            'failRate' => $failRate,
            'questionStats' => $questionStats,
            'header_title' => 'Résultats de l\'examen'
        ]);
    }
}
