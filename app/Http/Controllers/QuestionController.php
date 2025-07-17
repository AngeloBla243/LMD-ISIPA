<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Choice;

class QuestionController extends Controller
{
    // public function create($examId)
    // {
    //     $exam = Exam::findOrFail($examId);
    //     return view('teacher.questions.create', [
    //         'exam' => $exam,
    //         'header_title' => 'Ajouter des questions'
    //     ]);
    // }

    public function create($examId)
    {
        $exam = Exam::with(['questions.choices'])->findOrFail($examId);
        return view('teacher.questions.create', [
            'exam' => $exam,
            'header_title' => 'Ajouter des questions'
        ]);
    }



    // public function store(Request $request, $examId)
    // {
    //     $exam = Exam::findOrFail($examId);

    //     foreach ($request->questions as $qIndex => $questionData) {
    //         $question = Question::create([
    //             'exam_id' => $exam->id,
    //             'question_text' => $questionData['text'],
    //             'type' => 'multiple_choice',
    //             'points' => $questionData['points']
    //         ]);

    //         // Récupère le numéro du choix correct pour cette question
    //         $correctChoice = $questionData['correct'] ?? null;

    //         foreach ($questionData['choices'] as $cIndex => $choiceData) {
    //             Choice::create([
    //                 'question_id' => $question->id,
    //                 'choice_text' => $choiceData['text'],
    //                 // Compare l'index du choix avec la valeur du radio "correct"
    //                 'is_correct' => ((string)$cIndex === (string)$correctChoice) ? 1 : 0
    //             ]);
    //         }
    //     }

    //     return redirect()->route('teacher.exams.index')
    //         ->with('success', 'Questionnaire créé avec succès!');
    // }

    public function store(Request $request, $examId)
    {
        $exam = Exam::findOrFail($examId);

        // 1. Supprimer toutes les anciennes questions et choix de cet examen
        foreach ($exam->questions as $question) {
            // Supprime d'abord les choix de chaque question
            $question->choices()->delete();
            // Puis la question elle-même
            $question->delete();
        }

        // 2. Créer les nouvelles questions et choix
        foreach ($request->questions as $qIndex => $questionData) {
            $question = Question::create([
                'exam_id' => $exam->id,
                'question_text' => $questionData['text'],
                'type' => 'multiple_choice',
                'points' => $questionData['points']
            ]);

            $correctChoice = $questionData['correct'] ?? null;

            foreach ($questionData['choices'] as $cIndex => $choiceData) {
                Choice::create([
                    'question_id' => $question->id,
                    'choice_text' => $choiceData['text'],
                    'is_correct' => ((string)$cIndex === (string)$correctChoice) ? 1 : 0
                ]);
            }
        }

        return redirect()->route('teacher.exams.index')
            ->with('success', 'Questionnaire mis à jour avec succès!');
    }
}
