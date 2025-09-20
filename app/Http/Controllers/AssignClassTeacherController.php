<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassModel;
use App\Models\User;
use App\Models\AssignClassTeacherModel;
use App\Models\ClassSubjectModel;
use App\Models\SubjectModel;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\Auth;


class AssignClassTeacherController extends Controller
{
    public function list(Request $request)
    {

        $data['academicYears'] = AcademicYear::orderBy('start_date', 'desc')->get();

        $data['getRecord'] = AssignClassTeacherModel::getRecord()->paginate(10);;
        $data['header_title'] = "Assign Class Teacher";
        return view('admin.assign_class_teacher.list', $data);
    }

    public function add(Request $request)
    {
        $data['academicYears'] = AcademicYear::orderBy('start_date', 'desc')->get();

        $data['getClass'] = ClassModel::getClass();
        $data['getTeacher'] = User::getTeacherClass();

        $data['header_title'] = "Add Assign Class Teacher";
        return view('admin.assign_class_teacher.add', $data);
    }



    public function insert(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'class_id' => 'required|exists:class,id',
            'teacher_id' => 'required|array',
            'teacher_id.*' => 'exists:users,id',
            'status' => 'required|integer',
        ]);

        try {
            foreach ($request->teacher_id as $teacher_id) {
                $existing = AssignClassTeacherModel::getAlreadyFirst(
                    $request->class_id,
                    $teacher_id
                );

                if ($existing) {
                    $existing->update([
                        'status' => $request->status,
                        'academic_year_id' => $request->academic_year_id, // À partir du formulaire
                    ]);
                } else {
                    AssignClassTeacherModel::create([
                        'class_id' => $request->class_id,
                        'teacher_id' => $teacher_id,
                        'status' => $request->status,
                        'academic_year_id' => $request->academic_year_id, // À partir du formulaire
                        'created_by' => Auth::id(),
                    ]);
                }
            }

            return redirect()->route('admin.assign_class_teacher.list')
                ->with('success', 'Assignation réussie !');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur : ' . $e->getMessage())
                ->withInput();
        }
    }



    public function edit($id)
    {
        $getRecord = AssignClassTeacherModel::getSingle($id);

        if (!empty($getRecord)) {
            // Récupérer toutes les années académiques
            $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();

            // Récupérer l'année académique actuelle de l'assignation
            $selectedAcademicYear = $getRecord->academic_year_id;

            $data = [
                'getRecord' => $getRecord,
                'academicYears' => $academicYears,
                'selectedAcademicYear' => $selectedAcademicYear,
                'getAssignTeacherID' => AssignClassTeacherModel::getAssignTeacherID($getRecord->class_id),
                'getClass' => ClassModel::getClass(),
                'getTeacher' => User::getTeacherClass(),
                'header_title' => "Modifier l'assignation"
            ];

            return view('admin.assign_class_teacher.edit', $data);
        } else {
            abort(404);
        }
    }


    public function update($id, Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'class_id' => 'required|exists:class,id',
            'teacher_id' => 'required|array',
            'status' => 'required|integer',
        ]);

        // Désactive les anciennes assignations de la classe
        AssignClassTeacherModel::where('class_id', $request->class_id)->update(['is_delete' => 1]);

        // Crée ou met à jour les assignations
        foreach ($request->teacher_id as $teacher_id) {
            AssignClassTeacherModel::updateOrCreate(
                [
                    'class_id' => $request->class_id,
                    'teacher_id' => $teacher_id,
                    'is_delete' => 1 // on cible les assignations désactivées
                ],
                [
                    'academic_year_id' => $request->academic_year_id,
                    'status' => $request->status,
                    'is_delete' => 0, // réactive l'assignation
                    'created_by' => auth()->id(),
                ]
            );
        }

        return redirect()->route('admin.assign_class_teacher.list')
            ->with('success', "Assignation mise à jour avec succès");
    }


    public function edit_single($id)
    {
        $getRecord = AssignClassTeacherModel::getSingle($id);
        if (!empty($getRecord)) {
            $data['getRecord'] = $getRecord;
            $data['getClass'] = ClassModel::getClass();
            $data['getTeacher'] = User::getTeacherClass();
            $data['header_title'] = "Edit Assign Class Teacher";
            return view('admin.assign_class_teacher.edit_single', $data);
        } else {
            abort(404);
        }
    }



    public function update_single($id, Request $request)
    {

        $getAlreadyFirst = AssignClassTeacherModel::getAlreadyFirst($request->class_id, $request->teacher_id);
        if (!empty($getAlreadyFirst)) {
            $getAlreadyFirst->status = $request->status;
            $getAlreadyFirst->save();

            return redirect('admin/assign_class_teacher/list')->with('success', "Status Successfully Updated");
        } else {
            $save = AssignClassTeacherModel::getSingle($id);
            $save->class_id = $request->class_id;
            $save->teacher_id = $request->teacher_id;
            $save->status = $request->status;
            $save->save();

            return redirect('admin/assign_class_teacher/list')->with('success', "Assign Class to Teacher Successfully Updated");
        }
    }


    public function delete($id)
    {
        $save = AssignClassTeacherModel::getSingle($id);
        $save->delete();

        return redirect()->back()->with('success', "Assign Class to Teacher Successfully Deleted");
    }

    public function getSubjectsByTeacher(Request $request)
    {
        $teacherId = $request->input('teacher_id');
        $assignment = AssignClassTeacherModel::where('teacher_id', $teacherId)->first();

        if (!$assignment) {
            return response()->json(['error' => 'Enseignant non assigné à une classe'], 404);
        }

        $subjects = ClassSubjectModel::where('class_id', $assignment->class_id)
            ->with('subject')
            ->get()
            ->pluck('subject');

        return response()->json($subjects);
    }


    public function insert_assign_subject(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'class_id' => 'required|exists:class,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'subject_ids' => 'required|array',
            'subject_ids.*' => 'exists:subject,id'
        ]);

        // Récupérer toutes les assignations existantes (avec ou sans matière)
        $existingAssignments = AssignClassTeacherModel::where([
            'teacher_id' => $request->teacher_id,
            'class_id' => $request->class_id,
            'academic_year_id' => $request->academic_year_id,
            'is_delete' => 0
        ])->get();

        // Mettre à jour chaque assignation existante
        foreach ($existingAssignments as $index => $assignment) {
            if (isset($request->subject_ids[$index])) {
                // Mettre à jour la matière si elle existe dans la sélection
                $assignment->update([
                    'subject_id' => $request->subject_ids[$index],
                    'status' => 0,
                    'created_by' => Auth::id()
                ]);
            } else {
                // Réinitialiser si aucune matière correspondante
                $assignment->update([
                    'subject_id' => null,
                    'status' => 0
                ]);
            }
        }

        return redirect()->route('admin.assign_class_teacher.list')
            ->with('success', 'Matières mises à jour avec succès');
    }




    public function assign_subject($teacher_id)
    {
        $selectedTeacher = User::findOrFail($teacher_id);

        // Récupérer toutes les assignations du prof
        $assignments = AssignClassTeacherModel::where('teacher_id', $teacher_id)
            ->with(['class.academicYear', 'class.subjects'])
            ->get();

        // S'il n'a aucune assignation, retourne une erreur
        if ($assignments->isEmpty()) {
            return back()->with('error', "Aucune classe assignée à cet enseignant !");
        }

        // Les classes pour le select
        $classes = $assignments->pluck('class')->unique('id');

        // Par défaut, on prend la première classe
        $selectedClass = $classes->first();

        // Selon la classe sélectionnée, matières et assignations existantes
        $class_id = request('class_id', $selectedClass->id); // Permet d'arriver sur une classe choisie après postback
        $class = $classes->where('id', $class_id)->first();
        $subjects = $class->subjects;
        $assignedSubjectIds = AssignClassTeacherModel::where('teacher_id', $teacher_id)
            ->where('class_id', $class_id)
            ->pluck('subject_id')
            ->filter()
            ->toArray();

        return view('admin.assign_class_teacher.assign_subject_subject', [
            'selectedTeacher'    => $selectedTeacher,
            'classes'            => $classes,
            'selectedClass'      => $class,
            'academicYear'       => $class->academicYear,
            'subjects'           => $subjects,
            'assignedSubjectIds' => $assignedSubjectIds,
            'teacher_id'         => $teacher_id,
        ]);
    }







    // teacher side work

    public function MyClassSubject()
    {
        // Récupérer l'année académique sélectionnée
        $academicYearId = session('academic_year_id', AcademicYear::where('is_active', 1)->value('id'));

        $data = [
            'getRecord' => AssignClassTeacherModel::getMyClassSubject(Auth::user()->id, $academicYearId),
            'academicYears' => AcademicYear::orderBy('start_date', 'desc')->get(),
            'selectedAcademicYear' => AcademicYear::find($academicYearId),
            'header_title' => "Mes Classes & Matières"
        ];

        return view('teacher.my_class_subject', $data);
    }


    public function getClassesByYear($yearId)
    {
        return \App\Models\ClassModel::where('academic_year_id', $yearId)
            ->where('is_delete', 0)
            ->get(['id', 'name', 'opt']);
    }
}
