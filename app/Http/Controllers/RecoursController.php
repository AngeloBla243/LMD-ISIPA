<?php

namespace App\Http\Controllers;

use App\Models\ExamScheduleModel;

use App\Models\AssignClassTeacherModel; // Modèle pour les classes assignées
use Illuminate\Support\Facades\Auth; //
use App\Models\ClassModel;
use App\Models\AcademicYear;

use Illuminate\Http\Request;
use App\Models\recours;

class RecoursController extends Controller
{
    // public function list()
    // {
    //     // Récupérer tous les recours avec les étudiants, classes et matières associés
    //     $recours = Recours::getAllRecours();
    //     // dd($recours);

    //     foreach ($recours as $recour) {
    //         $examId = ExamScheduleModel::getExamIdBySubject($recour->subject_id, $recour->class_id);
    //         // verifier toujours
    //         $recour->exam_id = $examId; // Ajoutez l'ID d'examen au recours
    //         //
    //     }

    //     // Passer les données à la vue
    //     return view('admin.recours.list', compact('recours'));
    // }

    public function list(Request $request)
    {
        // Récupérer toutes les années académiques
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();

        // Récupérer les classes filtrées
        $filteredClasses = collect();
        $selectedAcademicYearId = $request->get('academic_year_id');
        $selectedClassId = $request->get('class_id');


        if ($selectedAcademicYearId) {
            $filteredClasses = ClassModel::where('academic_year_id', $selectedAcademicYearId)->get();
        }

        // Filtrer les recours
        $recours = Recours::query()
            ->when($selectedAcademicYearId, function ($query) use ($selectedAcademicYearId) {
                $query->whereHas('class', function ($q) use ($selectedAcademicYearId) {
                    $q->where('academic_year_id', $selectedAcademicYearId);
                });
            })
            ->when($selectedClassId, function ($query) use ($selectedClassId) {
                $query->where('class_id', $selectedClassId);
            })
            ->with(['student', 'class', 'subject'])
            ->get()
            ->each(function ($recour) {
                // Ajouter exam_id dynamiquement (exemple)
                $recour->exam_id = ExamScheduleModel::getExamIdBySubject(
                    $recour->subject_id,
                    $recour->class_id
                );
            });
        // dd($recours);

        return view('admin.recours.list', compact('academicYears', 'filteredClasses', 'recours', 'selectedAcademicYearId', 'selectedClassId'));
    }

    // public function toggleStatus($id)
    // {
    //     $recour = Recours::findOrFail($id);
    //     $recour->status = !$recour->status;
    //     $recour->save();
    //     return redirect()->back()->with('success', 'Statut mis à jour');
    // }

    public function toggleStatus($id)
    {
        $recour = Recours::findOrFail($id);
        // Toggle : si déjà traité on remet à zéro, sinon on marque traité
        $recour->status = !$recour->status;
        $recour->save();
        return redirect()->back()->with('success', 'Statut du recours mis à jour !');
    }




    // public function listForTeacher()
    // {
    //     // Récupérer l'ID de l'enseignant connecté
    //     $teacherId = Auth::id();


    //     // Récupérer les classes assignées à cet enseignant
    //     $assignedClasses = AssignClassTeacherModel::where('teacher_id', $teacherId)
    //         ->pluck('class_id')
    //         ->toArray();

    //     // Vérifiez si des classes sont assignées
    //     if (empty($assignedClasses)) {
    //         // Si aucune classe n'est assignée, retourner une vue avec un message ou une liste vide
    //         return view('teacher.recours.list', ['recours' => collect()]); // Passer une collection vide
    //     }

    //     // Récupérer les recours uniquement pour les classes assignées
    //     $recours = Recours::whereIn('class_id', $assignedClasses)
    //         ->with(['student', 'class', 'subject']) // Charger les relations
    //         ->get();


    //     foreach ($recours as $recour) {
    //         $examId = ExamScheduleModel::getExamIdBySubject($recour->subject_id, $recour->class_id);
    //         // verifier toujours
    //         $recour->exam_id = $examId; // Ajoutez l'ID d'examen au recours
    //         //
    //     }

    //     // Filtrer les recours pour ne garder que ceux qui correspondent aux sujets assignés à l'enseignant
    //     $filteredRecours = $recours->filter(function ($recour) use ($teacherId) {
    //         // Vérifier si le sujet est assigné à l'enseignant pour cette classe
    //         return AssignClassTeacherModel::where('teacher_id', $teacherId)
    //             ->where('class_id', $recour->class_id)
    //             ->where('subject_id', $recour->subject_id)
    //             ->exists();
    //     });

    //     // Passer les données filtrées à la vue
    //     return view('teacher.recours.list', ['recours' => $filteredRecours]);
    // }

    public function listForTeacher(Request $request)
    {
        // 1. Récupérer l'année académique sélectionnée
        $academicYearId = $request->get(
            'academic_year_id',
            session('academic_year_id', AcademicYear::where('is_active', 1)->value('id'))
        );

        // 2. Récupérer les classes assignées avec filtre académique
        $assignedClasses = AssignClassTeacherModel::join('class', 'class.id', '=', 'assign_class_teacher.class_id')
            ->where('assign_class_teacher.teacher_id', Auth::id())
            ->when($academicYearId, function ($q) use ($academicYearId) {
                $q->where('class.academic_year_id', $academicYearId);
            })
            ->pluck('class_id')
            ->toArray();

        // 3. Gestion cas aucune classe
        if (empty($assignedClasses)) {
            return view('teacher.recours.list', [
                'recours' => collect(),
                'academicYears' => AcademicYear::all(),
                'selectedAcademicYear' => AcademicYear::find($academicYearId)
            ])->with('error', 'Aucune classe assignée pour cette année');
        }

        // 4. Récupérer les recours avec relations
        $recours = Recours::whereIn('class_id', $assignedClasses)
            ->with(['student', 'class', 'subject.academicYear'])
            ->get();

        // 5. Filtrer par matière enseignée + année
        $filteredRecours = $recours->filter(function ($recour) use ($academicYearId) {
            return AssignClassTeacherModel::where('teacher_id', Auth::id())
                ->where('class_id', $recour->class_id)
                ->where('subject_id', $recour->subject_id)
                ->whereHas('class', function ($q) use ($academicYearId) {
                    $q->where('academic_year_id', $academicYearId);
                })
                ->exists();
        });

        // 6. Ajouter l'ID d'examen
        $filteredRecours->each(function ($recour) {
            $recour->exam_id = ExamScheduleModel::getExamIdBySubject(
                $recour->subject_id,
                $recour->class_id
            );
        });

        foreach ($recours as $recour) {
            $recour->exam_id = ExamScheduleModel::getExamIdBySubject(
                $recour->subject_id,
                $recour->class_id
            );
            $recour->academic_year_id = $academicYearId; // Ajouter l'année académique
        }

        return view('teacher.recours.list', [
            'recours' => $filteredRecours,
            'academicYears' => AcademicYear::orderBy('start_date', 'desc')->get(),
            'selectedAcademicYear' => AcademicYear::find($academicYearId)
        ]);
    }



    public function toggleStatusTeacher($id)
    {
        $recour = Recours::findOrFail($id);

        // Vérifier que le recours appartient à une classe/matière assignée
        $isAllowed = AssignClassTeacherModel::where('teacher_id', Auth::id())
            ->where('class_id', $recour->class_id)
            ->where('subject_id', $recour->subject_id)
            ->exists();

        if (!$isAllowed) {
            return redirect()->back()->with('error', 'Action non autorisée');
        }

        $recour->status = !$recour->status;
        $recour->save();

        return redirect()->back()->with('success', 'Statut du recours mis à jour !');
    }
}
