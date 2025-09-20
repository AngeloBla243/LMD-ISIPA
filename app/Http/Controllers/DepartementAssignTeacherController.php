<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassModel;
use App\Models\User;
use App\Models\AssignClassTeacherModel;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\Auth;

class DepartementAssignTeacherController extends Controller
{
    public function list()
    {
        $departmentId = Auth::user()->department_id;

        $data['getRecord'] = AssignClassTeacherModel::with([
            'class' => function ($query) use ($departmentId) {
                $query->where('department_id', $departmentId);
            },
            'academicYear',
            'teacher',
            'creator'
        ])->whereHas('class', function ($query) use ($departmentId) {
            $query->where('department_id', $departmentId);
        })->paginate(10);

        $data['header_title'] = "Liste des affectations professeurs (Département)";

        return view('departement.assign_teacher.list', $data);
    }


    public function add()
    {
        $departmentId = Auth::user()->department_id;
        $data['academicYears'] = AcademicYear::orderBy('start_date', 'desc')->get();
        $data['getClass'] = ClassModel::where('department_id', $departmentId)->get();

        // Appel à la méthode personnalisée au lieu de filtrer sur 'role' qui n'existe pas
        $data['getTeacher'] = User::getTeacherClass();

        $data['header_title'] = "Ajouter une affectation de professeur";
        return view('departement.assign_teacher.add', $data);
    }


    public function insert(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'class_id' => 'required|exists:classes,id',
            'teacher_id' => 'required|array',
            'teacher_id.*' => 'exists:users,id',
            'status' => 'required|integer',
        ]);

        $departmentId = Auth::user()->department_id;
        $class = ClassModel::where('department_id', $departmentId)->findOrFail($request->class_id);

        foreach ($request->teacher_id as $teacher_id) {
            $existing = AssignClassTeacherModel::where('class_id', $request->class_id)
                ->where('teacher_id', $teacher_id)
                ->first();

            if ($existing) {
                $existing->update([
                    'status' => $request->status,
                    'academic_year_id' => $request->academic_year_id,
                ]);
            } else {
                AssignClassTeacherModel::create([
                    'class_id' => $request->class_id,
                    'teacher_id' => $teacher_id,
                    'status' => $request->status,
                    'academic_year_id' => $request->academic_year_id,
                    'created_by' => Auth::id(),
                ]);
            }
        }

        return redirect()->route('departement.assign_teacher.list')->with('success', 'Assignations enregistrées avec succès');
    }

    public function edit($id)
    {
        $departmentId = Auth::user()->department_id;
        $getRecord = AssignClassTeacherModel::findOrFail($id);

        $class = ClassModel::where('department_id', $departmentId)->findOrFail($getRecord->class_id);

        if (!$class) {
            abort(403);
        }

        $data = [
            'getRecord' => $getRecord,
            'academicYears' => AcademicYear::orderBy('start_date', 'desc')->get(),
            'getClass' => ClassModel::where('department_id', $departmentId)->get(),
            'getTeacher' => User::getTeacherClass(),
            'selectedAcademicYear' => $getRecord->academic_year_id,
            'header_title' => "Modifier une assignation de professeur",
        ];

        return view('departement.assign_teacher.edit', $data);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'class_id' => 'required|exists:classes,id',
            'teacher_id' => 'required|exists:users,id',
            'status' => 'required|integer',
        ]);

        $assign = AssignClassTeacherModel::findOrFail($id);
        $assign->update([
            'class_id' => $request->class_id,
            'teacher_id' => $request->teacher_id,
            'academic_year_id' => $request->academic_year_id,
            'status' => $request->status,
        ]);

        return redirect()->route('departement.assign_teacher.list')->with('success', 'Assignation modifiée avec succès');
    }

    public function delete($id)
    {
        $assign = AssignClassTeacherModel::findOrFail($id);
        $assign->delete();

        return redirect()->back()->with('success', 'Assignation supprimée avec succès');
    }

    public function getClassesByYear($yearId)
    {
        $departmentId = Auth::user()->department_id;

        $classes = ClassModel::where('academic_year_id', $yearId)
            ->where('department_id', $departmentId)
            ->where('is_delete', 0)
            ->get(['id', 'name', 'opt']);

        return response()->json($classes);
    }

    public function assign_subject($teacher_id)
    {
        $departmentId = Auth::user()->department_id;

        $selectedTeacher = User::findOrFail($teacher_id);

        // Récupérer uniquement les assignations du département pour ce prof
        $assignments = AssignClassTeacherModel::where('teacher_id', $teacher_id)
            ->whereHas('class', function ($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            })
            ->with(['class.academicYear', 'class.subjects'])
            ->get();

        if ($assignments->isEmpty()) {
            return back()->with('error', "Aucune classe assignée à cet enseignant dans ton département !");
        }

        $classes = $assignments->pluck('class')->unique('id');

        $selectedClass = $classes->first();

        $class_id = request('class_id', $selectedClass->id);
        $class = $classes->where('id', $class_id)->first();

        $subjects = $class->subjects;

        $assignedSubjectIds = AssignClassTeacherModel::where('teacher_id', $teacher_id)
            ->where('class_id', $class_id)
            ->pluck('subject_id')
            ->filter()
            ->toArray();

        return view('departement.assign_teacher.assign_subject', [
            'selectedTeacher' => $selectedTeacher,
            'classes' => $classes,
            'selectedClass' => $class,
            'academicYear' => $class->academicYear,
            'subjects' => $subjects,
            'assignedSubjectIds' => $assignedSubjectIds,
            'teacher_id' => $teacher_id,
        ]);
    }

    public function insert_assign_subject(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'class_id' => 'required|exists:classes,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'subject_ids' => 'required|array',
            'subject_ids.*' => 'exists:subjects,id',
        ]);

        $assignments = AssignClassTeacherModel::where([
            'teacher_id' => $request->teacher_id,
            'class_id' => $request->class_id,
            'academic_year_id' => $request->academic_year_id,
            'is_delete' => 0,
        ])->get();

        foreach ($assignments as $index => $assignment) {
            if (isset($request->subject_ids[$index])) {
                $assignment->update([
                    'subject_id' => $request->subject_ids[$index],
                    'status' => 0,
                    'created_by' => Auth::id(),
                ]);
            } else {
                $assignment->update([
                    'subject_id' => null,
                    'status' => 0,
                ]);
            }
        }

        return redirect()->route('departement.assign_teacher.list')
            ->with('success', 'Matières mises à jour avec succès');
    }
}
