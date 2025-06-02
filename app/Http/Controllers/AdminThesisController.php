<?php

namespace App\Http\Controllers;

use App\Models\ThesisSubmissio;
use App\Models\ThesisSubmissionSetting;
use Illuminate\Support\Facades\Storage; // Ajout de l'import pour Storage
use Illuminate\Support\Facades\Http;
use App\Models\SettingModel;
use Illuminate\Http\Request;
use App\Models\AcademicYear;
use App\Models\ClassModel;
use Barryvdh\DomPDF\Facade\PDF;

class AdminThesisController extends Controller
{
    // Liste des mémoires soumis
    public function index(Request $request)
    {
        $query = ThesisSubmissio::with([
            'student.class',
            'directeur',
            'encadreur'
        ]);

        // Filtres de recherche
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('student', function ($qs) use ($search) {
                    $qs->where('name', 'like', "%$search%")
                        ->orWhere('last_name', 'like', "%$search%");
                })
                    ->orWhereHas('student.class', function ($qc) use ($search) {
                        $qc->where('name', 'like', "%$search%");
                    })
                    ->orWhere('subject', 'like', "%$search%");
            });
        }

        // Numérotation par ordre de dépôt (plus récent = 1)
        $submissions = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.theses.index', compact('submissions'));
    }


    // Détail d'une soumission
    public function show($id)
    {
        $submission = ThesisSubmissio::with('student')->findOrFail($id);
        return view('admin.theses.show', compact('submission'));
    }

    // AdminThesisController.php
    public function update(Request $request, $id)
    {
        $submission = ThesisSubmissio::findOrFail($id);

        $request->validate([
            'subject' => 'required|string|max:255',
            'status' => 'required|in:pending,accepted,rejected',
        ]);

        $submission->subject = $request->subject;
        $submission->status = $request->status;
        $submission->save();

        return redirect()->route('admin.theses.show', $id)
            ->with('success', 'Modifications enregistrées avec succès !');
    }




    // public function downloadReport($id)
    // {
    //     $submission = ThesisSubmissio::findOrFail($id);

    //     // On récupère le contenu texte
    //     $content = $submission->content ?: 'Mémoire non disponible';
    //     $filename = str_replace(' ', '_', $submission->subject) . '.txt';

    //     return response($content)
    //         ->header('Content-Type', 'text/plain; charset=UTF-8')
    //         ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    // }


    // public function exportThesesPDF()
    // {
    //     $academicYear = AcademicYear::where('is_active', true)->firstOrFail();
    //     $getSetting = SettingModel::getSingle();

    //     // Récupérer les classes avec les soumissions de l'année active
    //     $classes = ClassModel::with(['submissions' => function ($query) use ($academicYear) {
    //         $query->where('academic_year_id', $academicYear->id)
    //             ->orderBy('type')
    //             ->orderBy('student_id');
    //     }])->get();

    //     // Structurer les données
    //     $groupedSubmissions = [];
    //     foreach ($classes as $class) {
    //         $groupedSubmissions[$class->id] = [
    //             'class'     => $class,
    //             'memoires'  => $class->submissions->where('type', 1),
    //             'projets'   => $class->submissions->where('type', 2)
    //         ];
    //     }

    //     $pdf = PDF::loadView('admin.theses.export-pdf', compact(
    //         'groupedSubmissions',
    //         'academicYear',
    //         'getSetting'
    //     ));

    //     return $pdf->download('liste_soumissions_' . now()->format('Ymd') . '.pdf');
    // }

    public function exportThesesPDF()
    {
        $academicYear = AcademicYear::where('is_active', true)->firstOrFail();
        $getSetting = SettingModel::getSingle();

        // Récupérer toutes les soumissions de l'année active
        $submissions = ThesisSubmissio::with(['student.classes'])
            ->where('academic_year_id', $academicYear->id)
            ->orderBy('type')
            ->orderBy('student_id')
            ->get();

        // Grouper les soumissions par classe (via student->classes)
        $groupedSubmissions = [];
        foreach ($submissions as $submission) {
            $student = $submission->student;
            // Prend la première classe de l'étudiant pour l'année active (à adapter selon votre besoin)
            $studentClass = $student->classes
                ->where('pivot.academic_year_id', $academicYear->id)
                ->first();

            if ($studentClass) {
                if (!isset($groupedSubmissions[$studentClass->id])) {
                    $groupedSubmissions[$studentClass->id] = [
                        'class'     => $studentClass,
                        'memoires'  => collect(),
                        'projets'   => collect()
                    ];
                }
                if ($submission->type == 1) {
                    $groupedSubmissions[$studentClass->id]['memoires']->push($submission);
                } else {
                    $groupedSubmissions[$studentClass->id]['projets']->push($submission);
                }
            }
        }

        $pdf = PDF::loadView('admin.theses.export-pdf', compact(
            'groupedSubmissions',
            'academicYear',
            'getSetting'
        ));

        return $pdf->download('liste_soumissions_' . now()->format('Ymd') . '.pdf');
    }








    public function destroy($id)
    {
        $submission = ThesisSubmissio::findOrFail($id);
        $submission->delete();
        return back()->with('success', 'Soumission supprimée.');
    }
}
