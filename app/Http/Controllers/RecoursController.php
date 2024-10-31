<?php

namespace App\Http\Controllers;

use App\Models\ExamScheduleModel;

use App\Models\AssignClassTeacherModel; // Modèle pour les classes assignées
use Illuminate\Support\Facades\Auth; //

use Illuminate\Http\Request;
use App\Models\recours;

class RecoursController extends Controller
{
    public function list()
    {
        // Récupérer tous les recours avec les étudiants, classes et matières associés
        $recours = Recours::getAllRecours();
        // dd($recours);

        foreach ($recours as $recour) {
            $examId = ExamScheduleModel::getExamIdBySubject($recour->subject_id, $recour->class_id);
            // verifier toujours
            $recour->exam_id = $examId; // Ajoutez l'ID d'examen au recours
            //
        }

        // Passer les données à la vue
        return view('admin.recours.list', compact('recours'));
    }


    // public function listForTeacher()
    // {
    //     // Récupérer l'ID de l'enseignant connecté
    //     $teacherId = Auth::id();

    //     // Récupérer les classes assignées à cet enseignant
    //     $assignedClasses = AssignClassTeacherModel::where('teacher_id', $teacherId)
    //         ->pluck('class_id')
    //         ->toArray();

    //     // Récupérer les recours pour les classes assignées
    //     $recours = Recours::whereIn('class_id', $assignedClasses)
    //         ->with(['student', 'class', 'subject']) // Charger les relations
    //         ->get();

    //     foreach ($recours as $recour) {
    //         $examId = ExamScheduleModel::getExamIdBySubject($recour->subject_id, $recour->class_id);
    //         $recour->exam_id = $examId; // Ajoutez l'ID d'examen au recours
    //     }


    //     // Passer les données à la vue
    //     return view('teacher.recours.list', compact('recours'));
    // }

    public function listForTeacher()
    {
        // Récupérer l'ID de l'enseignant connecté
        $teacherId = Auth::id();


        // Récupérer les classes assignées à cet enseignant
        $assignedClasses = AssignClassTeacherModel::where('teacher_id', $teacherId)
            ->pluck('class_id')
            ->toArray();

        // Vérifiez si des classes sont assignées
        if (empty($assignedClasses)) {
            // Si aucune classe n'est assignée, retourner une vue avec un message ou une liste vide
            return view('teacher.recours.list', ['recours' => collect()]); // Passer une collection vide
        }

        // Récupérer les recours uniquement pour les classes assignées
        $recours = Recours::whereIn('class_id', $assignedClasses)
            ->with(['student', 'class', 'subject']) // Charger les relations
            ->get();


        foreach ($recours as $recour) {
            $examId = ExamScheduleModel::getExamIdBySubject($recour->subject_id, $recour->class_id);
            // verifier toujours
            $recour->exam_id = $examId; // Ajoutez l'ID d'examen au recours
            //
        }

        // Filtrer les recours pour ne garder que ceux qui correspondent aux sujets assignés à l'enseignant
        $filteredRecours = $recours->filter(function ($recour) use ($teacherId) {
            // Vérifier si le sujet est assigné à l'enseignant pour cette classe
            return AssignClassTeacherModel::where('teacher_id', $teacherId)
                ->where('class_id', $recour->class_id)
                ->where('subject_id', $recour->subject_id)
                ->exists();
        });

        // Passer les données filtrées à la vue
        return view('teacher.recours.list', ['recours' => $filteredRecours]);
    }
}
