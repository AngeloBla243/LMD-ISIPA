<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassSubjectModel;
use App\Models\ClassModel;
use App\Models\SubjectModel;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\Auth;

class ClassSubjectController extends Controller
{
    public function list(Request $request)
    {
        $data['getRecord'] = ClassSubjectModel::getRecord();

        $data['header_title'] = "Assign Subject List";
        return view('admin.assign_subject.list', $data);
    }



    public function add()
    {
        $data['academicYears'] = AcademicYear::orderBy('start_date', 'desc')->get();
        $data['getClass'] = ClassModel::getClass();
        $data['getSubject'] = SubjectModel::getSubject();
        $data['header_title'] = "Assigner une matière";
        return view('admin.assign_subject.add', $data);
    }


    public function insert(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'class_id' => 'required|exists:class,id',
            'subject_id' => 'required|array'
        ]);

        // Vérifier la cohérence année/classe
        $class = ClassModel::findOrFail($request->class_id);
        if ($class->academic_year_id != $request->academic_year_id) {
            return back()->with('error', 'La classe ne correspond pas à l\'année sélectionnée');
        }

        foreach ($request->subject_id as $subject_id) {
            // Vérifier la cohérence année/matière
            $subject = SubjectModel::findOrFail($subject_id);
            if ($subject->academic_year_id != $request->academic_year_id) {
                continue;
            }

            $getAlreadyFirst = ClassSubjectModel::getAlreadyFirst($request->class_id, $subject_id);

            if (!empty($getAlreadyFirst)) {
                $getAlreadyFirst->status = $request->status;
                $getAlreadyFirst->save();
            } else {
                $save = new ClassSubjectModel;
                $save->class_id = $request->class_id;
                $save->subject_id = $subject_id;
                $save->status = $request->status;
                $save->created_by = Auth::user()->id;
                $save->save();
            }
        }

        return redirect('admin/assign_subject/list')
            ->with('success', "Matières assignées avec succès");
    }




    // public function edit($id)
    // {
    //     $getRecord = ClassSubjectModel::getSingle($id);
    //     if (!empty($getRecord)) {
    //         $data['getRecord'] = $getRecord;
    //         $data['getAssignSubjectID'] = ClassSubjectModel::getAssignSubjectID($getRecord->class_id);
    //         $data['getClass'] = ClassModel::getClass();
    //         $data['getSubject'] = SubjectModel::getSubject();
    //         $data['header_title'] = "Edit Assign Subject";
    //         return view('admin.assign_subject.edit', $data);
    //     } else {
    //         abort(404);
    //     }
    // }

    public function edit($id)
    {
        $getRecord = ClassSubjectModel::getSingle($id);

        if (!empty($getRecord)) {
            $class = ClassModel::find($getRecord->class_id);
            $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();

            $data = [
                'getRecord' => $getRecord,
                'getAssignSubjectID' => ClassSubjectModel::getAssignSubjectID($getRecord->class_id),
                'getClass' => ClassModel::getClass(),
                'getSubject' => SubjectModel::getSubject(),
                'academicYears' => $academicYears,
                'selectedAcademicYear' => $class->academic_year_id ?? null,
                'header_title' => "Modifier l'assignation"
            ];

            return view('admin.assign_subject.edit', $data);
        } else {
            abort(404);
        }
    }


    // public function update(Request $request)
    // {
    //     ClassSubjectModel::deleteSubject($request->class_id);

    //     if (!empty($request->subject_id)) {
    //         foreach ($request->subject_id as $subject_id) {
    //             $getAlreadyFirst = ClassSubjectModel::getAlreadyFirst($request->class_id, $subject_id);
    //             if (!empty($getAlreadyFirst)) {
    //                 $getAlreadyFirst->status = $request->status;
    //                 $getAlreadyFirst->save();
    //             } else {
    //                 $save = new ClassSubjectModel;
    //                 $save->class_id = $request->class_id;
    //                 $save->subject_id = $subject_id;
    //                 $save->status = $request->status;
    //                 $save->created_by = Auth::user()->id;
    //                 $save->save();
    //             }
    //         }
    //     }

    //     return redirect('admin/assign_subject/list')->with('success', "Subject Sucessfully Assign to Class");
    // }

    public function update_single($id, Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'class_id' => 'required|exists:class,id',
            'subject_id' => 'required|exists:subject,id',
            'status' => 'required|integer'
        ]);

        // Vérifier la cohérence année/classe
        $class = ClassModel::findOrFail($request->class_id);
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
            $getAlreadyFirst->academic_year_id = $request->academic_year_id; // Ajouté
            $getAlreadyFirst->save();
        } else {
            $save = ClassSubjectModel::getSingle($id);
            $save->class_id = $request->class_id;
            $save->subject_id = $request->subject_id;
            $save->status = $request->status;
            $save->academic_year_id = $request->academic_year_id; // Ajouté
            $save->save();
        }

        return redirect('admin/assign_subject/list')->with('success', "Matière assignée avec succès");
    }

    public function update(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'class_id' => 'required|exists:class,id',
            'subject_id' => 'required|array'
        ]);

        // Vérifier la cohérence année/classe
        $class = ClassModel::findOrFail($request->class_id);
        if ($class->academic_year_id != $request->academic_year_id) {
            return back()->with('error', 'La classe ne correspond pas à l\'année sélectionnée');
        }

        ClassSubjectModel::deleteSubject($request->class_id);

        if (!empty($request->subject_id)) {
            foreach ($request->subject_id as $subject_id) {
                // Vérifier la cohérence année/matière
                $subject = SubjectModel::findOrFail($subject_id);
                if ($subject->academic_year_id != $request->academic_year_id) {
                    continue;
                }

                $getAlreadyFirst = ClassSubjectModel::getAlreadyFirst($request->class_id, $subject_id);

                if (!empty($getAlreadyFirst)) {
                    $getAlreadyFirst->status = $request->status;
                    $getAlreadyFirst->academic_year_id = $request->academic_year_id; // Ajouté
                    $getAlreadyFirst->save();
                } else {
                    $save = new ClassSubjectModel;
                    $save->class_id = $request->class_id;
                    $save->subject_id = $subject_id;
                    $save->status = $request->status;
                    $save->academic_year_id = $request->academic_year_id; // Ajouté
                    $save->created_by = Auth::user()->id;
                    $save->save();
                }
            }
        }

        return redirect('admin/assign_subject/list')->with('success', "Matières assignées avec succès");
    }

    public function delete($id)
    {
        $save = ClassSubjectModel::getSingle($id);
        $save->is_delete = 1;
        $save->save();

        return redirect()->back()->with('success', 'Record Successfully Deleted');
    }

    // public function edit_single($id)
    // {
    //     $getRecord = ClassSubjectModel::getSingle($id);
    //     if (!empty($getRecord)) {
    //         $data['getRecord'] = $getRecord;
    //         $data['getClass'] = ClassModel::getClass();
    //         $data['getSubject'] = SubjectModel::getSubject();
    //         $data['header_title'] = "Edit Assign Subject";
    //         return view('admin.assign_subject.edit_single', $data);
    //     } else {
    //         abort(404);
    //     }
    // }

    public function edit_single($id)
    {
        $getRecord = ClassSubjectModel::getSingle($id);

        if (!empty($getRecord)) {
            $class = ClassModel::find($getRecord->class_id);
            $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();

            $data = [
                'getRecord' => $getRecord,
                'getClass' => ClassModel::getClass(),
                'getSubject' => SubjectModel::getSubject(),
                'academicYears' => $academicYears,
                'selectedAcademicYear' => $class->academic_year_id ?? null,
                'header_title' => "Modifier l'assignation"
            ];

            return view('admin.assign_subject.edit_single', $data);
        } else {
            abort(404);
        }
    }


    // public function update_single($id, Request $request)
    // {
    //     $getAlreadyFirst = ClassSubjectModel::getAlreadyFirst($request->class_id, $request->subject_id);
    //     if (!empty($getAlreadyFirst)) {
    //         $getAlreadyFirst->status = $request->status;
    //         $getAlreadyFirst->save();

    //         return redirect('admin/assign_subject/list')->with('success', "Status Successfully Updated");
    //     } else {
    //         $save = ClassSubjectModel::getSingle($id);
    //         $save->class_id = $request->class_id;
    //         $save->subject_id = $request->subject_id;
    //         $save->status = $request->status;
    //         $save->save();

    //         return redirect('admin/assign_subject/list')->with('success', "Subject Sucessfully Assign to Class");
    //     }
    // }

    public function getClassesSubjects(Request $request)
    {
        $yearId = $request->year_id;

        $classes = ClassModel::where('academic_year_id', $yearId)
            ->where('is_delete', 0)
            ->get();

        $subjects = SubjectModel::where('academic_year_id', $yearId)
            ->where('is_delete', 0)
            ->get();

        return response()->json([
            'classes' => $classes,
            'subjects' => $subjects
        ]);
    }

    public function getClassesByYear($yearId)
    {
        $classes = ClassModel::where('academic_year_id', $yearId)
            ->where('is_delete', 0)
            ->get(['id', 'name', 'opt']);

        return response()->json($classes);
    }

    public function getSubjectsByYear($yearId)
    {
        $subjects = SubjectModel::where('academic_year_id', $yearId)
            ->where('is_delete', 0)
            ->get(['id', 'name', 'code']);

        return response()->json($subjects);
    }


    // public function getClassesByYear($yearId)
    // {
    //     $classes = ClassModel::getClassesByYear($yearId);
    //     return response()->json($classes);
    // }

    // public function getSubjectsByYear($yearId)
    // {
    //     $subjects = SubjectModel::getSubjectsByYear($yearId);
    //     return response()->json($subjects);
    // }
}
