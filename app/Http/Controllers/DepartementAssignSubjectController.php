<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassSubjectModel;
use App\Models\ClassModel;
use App\Models\SubjectModel;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\Auth;

class DepartementAssignSubjectController extends Controller
{
    public function list()
    {
        $departmentId = Auth::user()->department_id;

        $data['getRecord'] = ClassSubjectModel::getRecordByDepartment($departmentId);

        // Récupérer la liste des classes du département pour le filtre select
        $data['classes'] = ClassModel::where('department_id', $departmentId)->get();

        $data['header_title'] = "Liste des matières assignées (Département)";

        return view('departement.assign_subject.list', $data);
    }




    public function add()
    {
        $departmentId = Auth::user()->department_id;
        $data['academicYears'] = AcademicYear::orderBy('start_date', 'desc')->get();

        // récupérer les classes du département uniquement
        $data['getClass'] = ClassModel::where('department_id', $departmentId)->get();

        $data['getSubject'] = SubjectModel::orderBy('name')->get(); // ou filtrer par année si besoin
        $data['header_title'] = "Assigner une matière (Département)";
        return view('departement.assign_subject.add', $data);
    }

    public function insert(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|array',
            'status' => 'required|integer',
        ]);

        $departmentId = Auth::user()->department_id;
        $class = ClassModel::where('department_id', $departmentId)->findOrFail($request->class_id);

        if ($class->academic_year_id != $request->academic_year_id) {
            return back()->with('error', 'La classe ne correspond pas à l\'année sélectionnée');
        }

        foreach ($request->subject_id as $subject_id) {
            $subject = SubjectModel::findOrFail($subject_id);
            if ($subject->academic_year_id != $request->academic_year_id) {
                continue;
            }
            $getAlreadyFirst = ClassSubjectModel::getAlreadyFirst($request->class_id, $subject_id);
            if (!empty($getAlreadyFirst)) {
                $getAlreadyFirst->status = $request->status;
                $getAlreadyFirst->academic_year_id = $request->academic_year_id;
                $getAlreadyFirst->save();
            } else {
                $save = new ClassSubjectModel();
                $save->class_id = $request->class_id;
                $save->subject_id = $subject_id;
                $save->status = $request->status;
                $save->academic_year_id = $request->academic_year_id;
                $save->created_by = Auth::user()->id;
                $save->save();
            }
        }

        return redirect()->route('departement.assign_subject.list')->with('success', 'Matières assignées avec succès');
    }

    // Ajoutez les méthodes edit, update_single, update, delete similaires au modèle example

    public function edit($id)
    {
        $departmentId = Auth::user()->department_id;
        $getRecord = ClassSubjectModel::findOrFail($id);

        // Vérifier que la classe appartient au département de l'utilisateur
        $class = ClassModel::where('department_id', $departmentId)->findOrFail($getRecord->class_id);

        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();

        $data = [
            'getRecord' => $getRecord,
            'getAssignSubjectID' => ClassSubjectModel::getAssignSubjectID($getRecord->class_id),
            'getClass' => ClassModel::where('department_id', $departmentId)->get(),
            'getSubject' => SubjectModel::orderBy('name')->get(),
            'academicYears' => $academicYears,
            'selectedAcademicYear' => $class->academic_year_id ?? null,
            'header_title' => "Modifier l'assignation",
        ];

        return view('departement.assign_subject.edit', $data);
    }

    public function update_single($id, Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subject,id',
            'status' => 'required|integer',
        ]);

        $departmentId = Auth::user()->department_id;

        // Vérifier la classe et sa cohérence avec le département et l'année
        $class = ClassModel::where('department_id', $departmentId)->findOrFail($request->class_id);
        if ($class->academic_year_id != $request->academic_year_id) {
            return back()->with('error', 'La classe ne correspond pas à l\'année sélectionnée');
        }

        // Vérifier la cohérence année/matière
        $subject = SubjectModel::findOrFail($request->subject_id);
        if ($subject->academic_year_id != $request->academic_year_id) {
            return back()->with('error', 'La matière ne correspond pas à l\'année sélectionnée');
        }

        $getAlreadyFirst = ClassSubjectModel::getAlreadyFirst($request->class_id, $request->subject_id);

        if (!empty($getAlreadyFirst)) {
            $getAlreadyFirst->status = $request->status;
            $getAlreadyFirst->academic_year_id = $request->academic_year_id;
            $getAlreadyFirst->save();
        } else {
            $save = ClassSubjectModel::findOrFail($id);
            $save->class_id = $request->class_id;
            $save->subject_id = $request->subject_id;
            $save->status = $request->status;
            $save->academic_year_id = $request->academic_year_id;
            $save->save();
        }

        return redirect()->route('departement.assign_subject.list')->with('success', 'Matière assignée avec succès');
    }

    public function update(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|array',
            'status' => 'required|integer',
        ]);

        $departmentId = Auth::user()->department_id;
        $class = ClassModel::where('department_id', $departmentId)->findOrFail($request->class_id);

        if ($class->academic_year_id != $request->academic_year_id) {
            return back()->with('error', 'La classe ne correspond pas à l\'année sélectionnée');
        }

        // Supprimer les matières existantes pour cette classe avant de réassigner
        ClassSubjectModel::where('class_id', $request->class_id)->delete();

        if (!empty($request->subject_id)) {
            foreach ($request->subject_id as $subject_id) {
                $subject = SubjectModel::findOrFail($subject_id);
                if ($subject->academic_year_id != $request->academic_year_id) {
                    continue;
                }

                $getAlreadyFirst = ClassSubjectModel::getAlreadyFirst($request->class_id, $subject_id);

                if (!empty($getAlreadyFirst)) {
                    $getAlreadyFirst->status = $request->status;
                    $getAlreadyFirst->academic_year_id = $request->academic_year_id;
                    $getAlreadyFirst->save();
                } else {
                    $save = new ClassSubjectModel();
                    $save->class_id = $request->class_id;
                    $save->subject_id = $subject_id;
                    $save->status = $request->status;
                    $save->academic_year_id = $request->academic_year_id;
                    $save->created_by = Auth::user()->id;
                    $save->save();
                }
            }
        }

        return redirect()->route('departement.assign_subject.list')->with('success', 'Matières assignées avec succès');
    }

    public function delete($id)
    {
        $departmentId = Auth::user()->department_id;

        $assignSubject = ClassSubjectModel::findOrFail($id);

        // Vérifiez que la classe appartient au département de l'utilisateur
        $class = ClassModel::where('department_id', $departmentId)->findOrFail($assignSubject->class_id);

        $assignSubject->is_delete = 1;
        $assignSubject->save();

        return redirect()->back()->with('success', 'Assignation supprimée avec succès');
    }
}
