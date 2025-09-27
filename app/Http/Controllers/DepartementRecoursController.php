<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\ClassModel;
use App\Models\ExamScheduleModel;
use App\Models\Recours;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartementRecoursController extends Controller
{
    public function list(Request $request)
    {
        $departmentId = Auth::user()->department_id;

        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();

        $filteredClasses = collect();
        $selectedAcademicYearId = $request->get('academic_year_id');
        $selectedClassId = $request->get('class_id');

        if ($selectedAcademicYearId) {
            // Classes du département pour l'année académique sélectionnée
            $filteredClasses = ClassModel::where('department_id', $departmentId)
                ->where('academic_year_id', $selectedAcademicYearId)
                ->get();
        }

        // Filtrer les recours liés aux classes du département
        $recours = Recours::query()
            ->when($selectedAcademicYearId, function ($query) use ($selectedAcademicYearId) {
                $query->whereHas('class', function ($q) use ($selectedAcademicYearId) {
                    $q->where('academic_year_id', $selectedAcademicYearId);
                });
            })
            ->when($selectedClassId, function ($query) use ($selectedClassId) {
                $query->where('class_id', $selectedClassId);
            })
            ->whereHas('class', function ($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            })
            ->with(['student', 'class', 'subject'])
            ->get()
            ->each(function ($recour) {
                $recour->exam_id = ExamScheduleModel::getExamIdBySubject($recour->subject_id, $recour->class_id);
            });

        return view('departement.recours.list', compact('academicYears', 'filteredClasses', 'recours', 'selectedAcademicYearId', 'selectedClassId'));
    }

    public function toggleStatus($id)
    {
        $recour = Recours::findOrFail($id);

        // Vérifier que le recours appartient à une classe du département de l'utilisateur
        $departmentId = Auth::user()->department_id;
        if ($recour->class->department_id != $departmentId) {
            return redirect()->back()->with('error', 'Action non autorisée');
        }

        // Basculer le statut
        $recour->status = !$recour->status;
        $recour->save();

        return redirect()->back()->with('success', 'Statut du recours mis à jour !');
    }

    public function destroy($id)
    {
        $recour = Recours::findOrFail($id);

        // Vérifier permission
        $departmentId = Auth::user()->department_id;
        if ($recour->class->department_id != $departmentId) {
            return redirect()->back()->with('error', 'Action non autorisée');
        }

        $recour->delete();

        return redirect()->back()->with('success', 'Recours supprimé avec succès');
    }
}
