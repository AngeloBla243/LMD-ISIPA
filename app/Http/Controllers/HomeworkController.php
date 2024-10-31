<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassModel;
use App\Models\ClassSubjectModel;
use App\Models\HomeworkModel;
use App\Models\AssignClassTeacherModel;
use App\Models\HomeworkSubmitModel;
use App\Models\SubjectModel;
use App\Models\User;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class HomeworkController extends Controller
{
    public function homework_report()
    {
        $data['getRecord'] = HomeworkSubmitModel::getHomeworkReport();
        $data['header_title'] = 'Homework Report';
        return view('admin.homework.report', $data);
    }

    public function homework()
    {
        $data['getRecord'] = HomeworkModel::getRecord();
        $data['header_title'] = 'Homework';
        return view('admin.homework.list', $data);
    }


    public function add()
    {
        $data['getClass'] = ClassModel::getClass();
        $data['header_title'] = 'Add New Homework';
        return view('admin.homework.add', $data);
    }

    public function insert(Request $request)
    {
        $homwork = new HomeworkModel;
        $homwork->class_id = trim($request->class_id);
        $homwork->subject_id = trim($request->subject_id);
        $homwork->homework_date = trim($request->homework_date);
        $homwork->submission_date = trim($request->submission_date);
        $homwork->description = trim($request->description);
        $homwork->created_by = Auth::user()->id;

        if (!empty($request->file('document_file'))) {
            $ext = $request->file('document_file')->getClientOriginalExtension();
            $file = $request->file('document_file');
            $randomStr = date('Ymdhis') . Str::random(20);
            $filename = strtolower($randomStr) . '.' . $ext;
            $file->move('upload/homework/', $filename);

            $homwork->document_file = $filename;
        }

        $homwork->save();

        return redirect('admin/homework/homework')->with('success', "Homework successfully created");
    }

    public function ajax_get_subject(Request $request)
    {
        $class_id = $request->class_id;
        $getSubject = ClassSubjectModel::MySubject($class_id);
        $html = '';
        $html .= '<option value="">Select Subject</option>';
        foreach ($getSubject as $value) {
            $html .= '<option value="' . $value->subject_id . '">' . $value->subject_name . '</option>';
        }

        $json['success'] = $html;
        echo json_encode($json);
    }

    public function edit($id)
    {
        $getRecord = HomeworkModel::getSingle($id);
        $data['getRecord'] = $getRecord;
        $data['getSubject'] = ClassSubjectModel::MySubject($getRecord->class_id);
        $data['getClass'] = ClassModel::getClass();
        $data['header_title'] = 'Edit Homework';
        return view('admin.homework.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $homwork = HomeworkModel::getSingle($id);;
        $homwork->class_id = trim($request->class_id);
        $homwork->subject_id = trim($request->subject_id);
        $homwork->homework_date = trim($request->homework_date);
        $homwork->submission_date = trim($request->submission_date);
        $homwork->description = trim($request->description);

        if (!empty($request->file('document_file'))) {
            $ext = $request->file('document_file')->getClientOriginalExtension();
            $file = $request->file('document_file');
            $randomStr = date('Ymdhis') . Str::random(20);
            $filename = strtolower($randomStr) . '.' . $ext;
            $file->move('upload/homework/', $filename);

            $homwork->document_file = $filename;
        }

        $homwork->save();

        return redirect('admin/homework/homework')->with('success', "Homework successfully updated");
    }

    public function delete($id)
    {
        $homwork = HomeworkModel::getSingle($id);
        $homwork->is_delete = 1;
        $homwork->save();

        return redirect()->back()->with('success', "Homework successfully deleted");
    }

    public function submitted($homework_id)
    {
        $homwork = HomeworkModel::getSingle($homework_id);
        if (!empty($homwork)) {
            $data['homework_id'] = $homework_id;
            $data['getRecord'] = HomeworkSubmitModel::getRecord($homework_id);
            $data['header_title'] = 'Submitted Homework';
            return view('admin.homework.submitted', $data);
        } else {
            abort(404);
        }
    }

    // teacher side

    // public function HomeworkTeacher()
    // {
    //     $class_ids = array();
    //     $getClass = AssignClassTeacherModel::getMyClassSubjectGroup(Auth::user()->id);
    //     dd($getClass);
    //     foreach($getClass as $class)
    //     {
    //         $class_ids[] = $class->class_id;
    //     }

    //     $data['getRecord'] = HomeworkModel::getRecordTeacher($class_ids);
    //     $data['header_title'] = 'Homework';
    //     return view('teacher.homework.list', $data);
    // }

    public function HomeworkTeacher()
    {
        $class_ids = [];
        $subject_ids = []; // Pour stocker les IDs de matières assignées

        // Récupérer les classes assignées à l'enseignant
        $getClass = AssignClassTeacherModel::getMyClassSubjectGroup(Auth::user()->id);

        foreach ($getClass as $class) {
            $class_ids[] = $class->class_id;

            // Récupérer les sujets assignés à cette classe
            $assignedSubjects = AssignClassTeacherModel::where('class_id', $class->class_id)
                ->pluck('subject_id'); // Assurez-vous que vous avez un champ subject_id dans cette table

            $subject_ids = array_merge($subject_ids, $assignedSubjects->toArray());
        }

        // Récupérer les devoirs uniquement pour les classes et sujets assignés
        $data['getRecord'] = HomeworkModel::getRecordTeacher($class_ids, $subject_ids);
        $data['header_title'] = 'Homework';
        return view('teacher.homework.list', $data);
    }


    // public function addTeacher()
    // {
    //     $data['getClass'] = AssignClassTeacherModel::getMyClassSubjectGroup(Auth::user()->id);
    //     $data['header_title'] = 'Add New Homework';
    //     return view('teacher.homework.add', $data);
    // }

    public function addTeacher()
    {
        // Récupérer les classes assignées à l'enseignant connecté
        $assignedClasses = AssignClassTeacherModel::getMyClassSubjectGroup(Auth::user()->id);

        // Récupérer les IDs de classes assignées
        $class_ids = $assignedClasses->pluck('class_id')->toArray();

        // Récupérer les matières assignées aux classes de cet enseignant
        $assignedSubjects = SubjectModel::whereIn('id', function ($query) use ($class_ids) {
            $query->select('subject_id')
                ->from('assign_class_teacher')
                ->whereIn('class_id', $class_ids)
                ->where('is_delete', 0); // Optionnel : vérifie si l'assignation n'est pas supprimée
        })->get();

        // Passer les données à la vue
        $data['getClass'] = $assignedClasses; // Garder les classes assignées si besoin
        $data['getSubjects'] = $assignedSubjects; // Passer les matières assignées
        $data['header_title'] = 'Add New Homework';

        return view('teacher.homework.add', $data);
    }

    public function insertTeacher(Request $request)
    {
        $homwork = new HomeworkModel;
        $homwork->class_id = trim($request->class_id);
        $homwork->subject_id = trim($request->subject_id);
        $homwork->homework_date = trim($request->homework_date);
        $homwork->submission_date = trim($request->submission_date);
        $homwork->description = trim($request->description);
        $homwork->created_by = Auth::user()->id;

        if (!empty($request->file('document_file'))) {
            $ext = $request->file('document_file')->getClientOriginalExtension();
            $file = $request->file('document_file');
            $randomStr = date('Ymdhis') . Str::random(20);
            $filename = strtolower($randomStr) . '.' . $ext;
            $file->move('upload/homework/', $filename);

            $homwork->document_file = $filename;
        }

        $homwork->save();

        return redirect('teacher/homework/homework');
    }


    public function editTeacher($id)
    {
        $getRecord = HomeworkModel::getSingle($id);
        $data['getRecord'] = $getRecord;
        $data['getSubject'] = ClassSubjectModel::MySubject($getRecord->class_id);
        $data['getClass'] = AssignClassTeacherModel::getMyClassSubjectGroup(Auth::user()->id);
        $data['header_title'] = 'Edit Homework';
        return view('teacher.homework.edit', $data);
    }

    public function updateTeacher(Request $request, $id)
    {
        $homwork = HomeworkModel::getSingle($id);;
        $homwork->class_id = trim($request->class_id);
        $homwork->subject_id = trim($request->subject_id);
        $homwork->homework_date = trim($request->homework_date);
        $homwork->submission_date = trim($request->submission_date);
        $homwork->description = trim($request->description);

        if (!empty($request->file('document_file'))) {
            $ext = $request->file('document_file')->getClientOriginalExtension();
            $file = $request->file('document_file');
            $randomStr = date('Ymdhis') . Str::random(20);
            $filename = strtolower($randomStr) . '.' . $ext;
            $file->move('upload/homework/', $filename);

            $homwork->document_file = $filename;
        }

        $homwork->save();

        return redirect('teacher/homework/homework')->with('success', "Homework successfully updated");
    }

    public function submittedTeacher($homework_id)
    {
        $homwork = HomeworkModel::getSingle($homework_id);
        if (!empty($homwork)) {
            $data['homework_id'] = $homework_id;
            $data['getRecord'] = HomeworkSubmitModel::getRecord($homework_id);
            $data['header_title'] = 'Submitted Homework';
            return view('teacher.homework.submitted', $data);
        } else {
            abort(404);
        }
    }


    // student side work

    public function HomeworkStudent()
    {
        $data['getRecord'] = HomeworkModel::getRecordStudent(Auth::user()->class_id, Auth::user()->id);
        $data['header_title'] = 'My Homework';
        return view('student.homework.list', $data);
    }

    public function SubmitHomework($homework_id)
    {
        $data['getRecord'] = HomeworkModel::getSingle($homework_id);
        $data['header_title'] = 'Submit My Homework';
        return view('student.homework.submit', $data);
    }

    public function SubmitHomeworkInsert($homework_id, Request $request)
    {
        $homework = new HomeworkSubmitModel;
        $homework->homework_id = $homework_id;
        $homework->student_id = Auth::user()->id;
        $homework->description = trim($request->description);

        if (!empty($request->file('document_file'))) {
            $ext = $request->file('document_file')->getClientOriginalExtension();
            $file = $request->file('document_file');
            $randomStr = date('Ymdhis') . Str::random(20);
            $filename = strtolower($randomStr) . '.' . $ext;
            $file->move('upload/homework/', $filename);

            $homework->document_file = $filename;
        }

        $homework->save();

        return redirect('student/my_homework')->with('success', "Homework successfully submited");
    }

    public function HomeworkSubmittedStudent(Request $request)
    {
        $data['getRecord'] = HomeworkSubmitModel::getRecordStudent(Auth::user()->id);
        $data['header_title'] = 'My Submitted Homework';
        return view('student.homework.submitted_list', $data);
    }

    // parent side work



    public function HomeworkStudentParent($student_id)
    {
        $getStudent = User::getSingle($student_id);
        $data['getRecord'] = HomeworkModel::getRecordStudent($getStudent->class_id, $getStudent->id);
        $data['header_title'] = 'Student Homework';
        $data['getStudent'] = $getStudent;
        return view('parent.homework.list', $data);
    }


    public function SubmittedHomeworkStudentParent($student_id)
    {
        $getStudent = User::getSingle($student_id);
        $data['getRecord'] = HomeworkSubmitModel::getRecordStudent($getStudent->id);
        $data['header_title'] = 'Student Submitted Homework';
        $data['getStudent'] = $getStudent;
        return view('parent.homework.submitted_list', $data);
    }
}
