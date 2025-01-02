<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ExamModel;
use App\Models\ClassModel;
use App\Models\ClassSubjectModel;
use App\Models\ExamScheduleModel;
use App\Models\MarksRegisterModel;
use App\Models\AssignClassTeacherModel;
use App\Models\User;
use App\Models\MarksGradeModel;
use App\Models\SettingModel;
use App\Models\SubjectModel;

class ExaminationsController extends Controller
{
    public function exam_list()
    {
        $data['getRecord'] = ExamModel::getRecord();
        $data['header_title'] = "Exam List";
        return view('admin.examinations.exam.list', $data);
    }


    public function exam_add()
    {
        $data['header_title'] = "Add New Exam";
        return view('admin.examinations.exam.add', $data);
    }

    public function exam_insert(Request $request)
    {
        $exam = new ExamModel;
        $exam->name = trim($request->name);
        $exam->note = trim($request->note);
        $exam->created_by = Auth::user()->id;
        $exam->save();

        return redirect('admin/examinations/exam/list')->with('success', "Exam successfully created");
    }


    public function exam_edit($id)
    {
        $data['getRecord'] = ExamModel::getSingle($id);
        if (!empty($data['getRecord'])) {
            $data['header_title'] = "Edit Exam";
            return view('admin.examinations.exam.edit', $data);
        } else {
            abort(404);
        }
    }


    public function exam_update($id, Request $request)
    {
        $exam = ExamModel::getSingle($id);;
        $exam->name = trim($request->name);
        $exam->note = trim($request->note);
        $exam->save();

        return redirect('admin/examinations/exam/list')->with('success', "Exam successfully updated");
    }


    public function exam_delete($id)
    {
        $getRecord = ExamModel::getSingle($id);
        if (!empty($getRecord)) {
            $getRecord->is_delete = 1;
            $getRecord->save();

            return redirect()->back()->with('success', "Exam successfully deleted");
        } else {
            abort(404);
        }
    }


    public function exam_schedule(Request $request)
    {
        $data['getClass'] = ClassModel::getClass();
        $data['getExam'] = ExamModel::getExam();

        $result = array();
        if (!empty($request->get('exam_id')) && !empty($request->get('class_id'))) {
            $getSubject = ClassSubjectModel::MySubject($request->get('class_id'));
            foreach ($getSubject as $value) {
                $dataS = array();
                $dataS['subject_id'] = $value->subject_id;
                $dataS['class_id'] = $value->class_id;
                $dataS['subject_name'] = $value->subject_name;
                $dataS['subject_type'] = $value->subject_type;

                $ExamSchedule = ExamScheduleModel::getRecordSingle($request->get('exam_id'), $request->get('class_id'), $value->subject_id);

                if (!empty($ExamSchedule)) {
                    $dataS['exam_date'] = $ExamSchedule->exam_date;
                    $dataS['start_time'] = $ExamSchedule->start_time;
                    $dataS['end_time'] = $ExamSchedule->end_time;
                    $dataS['room_number'] = $ExamSchedule->room_number;
                    $dataS['full_marks'] = $ExamSchedule->full_marks;
                    $dataS['passing_mark'] = $ExamSchedule->passing_mark;
                    $dataS['ponde'] = $ExamSchedule->ponde;
                } else {
                    $dataS['exam_date'] = '';
                    $dataS['start_time'] = '';
                    $dataS['end_time'] = '';
                    $dataS['room_number'] = '';
                    $dataS['full_marks'] = '';
                    $dataS['passing_mark'] = '';
                    $dataS['ponde'] = '';
                }


                $result[] = $dataS;
            }
        }


        $data['getRecord'] = $result;


        $data['header_title'] = "Exam Schedule";
        return view('admin.examinations.exam_schedule', $data);
    }

    public function exam_schedule_insert(Request $request)
    {
        ExamScheduleModel::deleteRecord($request->exam_id, $request->class_id);

        if (!empty($request->schedule)) {
            foreach ($request->schedule as $schedule) {
                if (!empty($schedule['subject_id']) && !empty($schedule['exam_date']) && !empty($schedule['start_time']) && !empty($schedule['end_time']) && !empty($schedule['room_number']) && !empty($schedule['full_marks']) && !empty($schedule['passing_mark'])) {
                    $exam = new ExamScheduleModel;
                    $exam->exam_id = $request->exam_id;
                    $exam->class_id = $request->class_id;
                    $exam->subject_id = $schedule['subject_id'];
                    $exam->exam_date = $schedule['exam_date'];
                    $exam->start_time = $schedule['start_time'];
                    $exam->end_time = $schedule['end_time'];
                    $exam->room_number = $schedule['room_number'];
                    $exam->full_marks = $schedule['full_marks'];
                    $exam->passing_mark = $schedule['passing_mark'];
                    $exam->ponde = $schedule['ponde'];
                    $exam->created_by = Auth::user()->id;
                    $exam->save();
                }
            }
        }

        return redirect()->back()->with('success', "Exam Schedule Successfully Saved");
    }


    public function marks_register(Request $request)
    {
        $data['getClass'] = ClassModel::getClass();
        $data['getExam'] = ExamModel::getExam();

        if (!empty($request->get('exam_id')) && !empty($request->get('class_id'))) {
            $data['getSubject'] = ExamScheduleModel::getSubject($request->get('exam_id'), $request->get('class_id'));

            $data['getStudent'] = User::getStudentClass($request->get('class_id'));
        }

        $data['header_title'] = "Marks Register";
        return view('admin.examinations.marks_register', $data);
    }



    public function marks_register_teacher(Request $request)
    {
        $data['getClass'] = AssignClassTeacherModel::getMyClassSubjectGroup(Auth::user()->id);
        // dd($data['getClass']);
        $data['getExam'] = ExamScheduleModel::getExamTeacher(Auth::user()->id);
        // dd($data['getExam']);

        if (!empty($request->get('exam_id')) && !empty($request->get('class_id'))) {
            $data['getSubject'] = ExamScheduleModel::getSubject_teacher($request->get('exam_id'), $request->get('class_id'), Auth::user()->id);
            // dd($data['getSubject']);


            $data['getStudent'] = User::getStudentClass($request->get('class_id'));
        }

        $data['header_title'] = "Marks Register";
        return view('teacher.marks_register', $data);
    }

    public function submit_marks_register(Request $request)
    {
        $valiation = 0;
        if (!empty($request->mark)) {
            foreach ($request->mark as $mark) {
                $getExamSchedule = ExamScheduleModel::getSingle($mark['id']);
                $full_marks = $getExamSchedule->full_marks;
                $ponde = $getExamSchedule->ponde;

                $class_work = !empty($mark['class_work']) ? $mark['class_work'] : 0;
                $exam = !empty($mark['exam']) ? $mark['exam'] : 0;

                $full_marks = !empty($mark['full_marks']) ? $mark['full_marks'] : 0;
                $passing_mark = !empty($mark['passing_mark']) ? $mark['passing_mark'] : 0;
                $ponde = !empty($mark['ponde']) ? $mark['ponde'] : 0;

                $total_mark = $class_work + $exam;

                if (($full_marks) >= $total_mark) {

                    $getMark = MarksRegisterModel::checkAlreadyMark($request->student_id,  $request->exam_id,  $request->class_id, $mark['subject_id']);
                    if (!empty($getMark)) {
                        $save = $getMark;
                    } else {
                        $save                   = new MarksRegisterModel;
                        $save->created_by       = Auth::user()->id;
                    }
                    $save->student_id       = $request->student_id;
                    $save->exam_id          = $request->exam_id;
                    $save->class_id         = $request->class_id;
                    $save->subject_id       = $mark['subject_id'];
                    $save->class_work       = $class_work;
                    $save->exam             = $exam;
                    $save->full_marks       = $full_marks;
                    $save->passing_mark    = $passing_mark;
                    $save->ponde            = $ponde;
                    $save->save();
                } else {
                    $valiation = 1;
                }
            }
        }

        if ($valiation == 0) {
            $json['message'] = "Mark Register successfully saved";
        } else {
            $json['message'] = "Mark Register successfully saved. Some Subject mark greather than full mark";
        }

        echo json_encode($json);
    }


    public function single_submit_marks_register(Request $request)
    {
        $id = $request->id;
        $getExamSchedule = ExamScheduleModel::getSingle($id);

        $full_marks = $getExamSchedule->full_marks;
        $ponde = $getExamSchedule->ponde;

        $class_work = !empty($request->class_work) ? $request->class_work : 0;

        $exam = !empty($request->exam) ? $request->exam : 0;


        $total_mark = $class_work + $exam;

        if ($full_marks >= $total_mark) {
            $getMark = MarksRegisterModel::checkAlreadyMark($request->student_id,  $request->exam_id,  $request->class_id, $request->subject_id);

            if (!empty($getMark)) {
                $save = $getMark;
            } else {
                $save                   = new MarksRegisterModel;
                $save->created_by       = Auth::user()->id;
            }

            $save->student_id       = $request->student_id;
            $save->exam_id          = $request->exam_id;
            $save->class_id         = $request->class_id;
            $save->subject_id       = $request->subject_id;
            $save->class_work       = $class_work;

            $save->exam             = $exam;
            $save->full_marks       = $getExamSchedule->full_marks;
            $save->passing_mark    = $getExamSchedule->passing_mark;
            $save->ponde           = $getExamSchedule->ponde;
            $save->save();

            $json['message'] = "Mark Register successfully saved";
        } else {
            $json['message'] = "Your total mark greather than full mark";
        }

        echo json_encode($json);
    }


    public function marks_grade()
    {
        $data['getRecord'] = MarksGradeModel::getRecord();
        $data['header_title'] = "Marks Grade";
        return view('admin.examinations.marks_grade.list', $data);
    }

    public function marks_grade_add()
    {
        $data['header_title'] = "Add New Marks Grade";
        return view('admin.examinations.marks_grade.add', $data);
    }

    public function marks_grade_insert(Request $request)
    {
        $mark = new MarksGradeModel;
        $mark->name = trim($request->name);
        $mark->percent_from = trim($request->percent_from);
        $mark->percent_to = trim($request->percent_to);
        $mark->created_by = Auth::user()->id;
        $mark->save();

        return redirect('admin/examinations/marks_grade')->with('success', "Marks Grade successfully created");
    }

    public function marks_grade_edit($id)
    {
        $data['getRecord'] = MarksGradeModel::getSingle($id);
        $data['header_title'] = "Edit Marks Grade";
        return view('admin.examinations.marks_grade.edit', $data);
    }

    public function marks_grade_update($id, Request $request)
    {
        $mark = MarksGradeModel::getSingle($id);
        $mark->name = trim($request->name);
        $mark->percent_from = trim($request->percent_from);
        $mark->percent_to = trim($request->percent_to);
        $mark->save();

        return redirect('admin/examinations/marks_grade')->with('success', "Marks Grade successfully updated");
    }

    public function marks_grade_delete($id)
    {
        $mark = MarksGradeModel::getSingle($id);
        $mark->delete();

        return redirect('admin/examinations/marks_grade')->with('success', "Marks Grade successfully deleted");
    }

    // student side

    public function MyExamTimetable(Request $request)
    {
        $class_id = Auth::user()->class_id;
        $getExam = ExamScheduleModel::getExam($class_id);
        $result = array();
        foreach ($getExam as $value) {
            $dataE = array();
            $dataE['name'] = $value->exam_name;
            $getExamTimetable = ExamScheduleModel::getExamTimetable($value->exam_id, $class_id);
            $resultS = array();
            foreach ($getExamTimetable as $valueS) {
                $dataS = array();
                $dataS['subject_name'] = $valueS->subject_name;
                $dataS['exam_date'] = $valueS->exam_date;
                $dataS['start_time'] = $valueS->start_time;
                $dataS['end_time'] = $valueS->end_time;
                $dataS['room_number'] = $valueS->room_number;
                $dataS['full_marks'] = $valueS->full_marks;
                $dataS['passing_mark'] = $valueS->passing_mark;
                $resultS[] = $dataS;
            }

            $dataE['exam'] = $resultS;
            $result[] = $dataE;
        }

        $data['getRecord'] = $result;

        $data['header_title'] = "My Exam Timetable";
        return view('student.my_exam_timetable', $data);
    }



    public function myExamResult()
    {
        $result = array();
        $getExam = MarksRegisterModel::getExam(Auth::user()->id);
        foreach ($getExam as $value) {
            $dataE = array();
            $dataE['exam_name'] = $value->exam_name;
            $dataE['exam_id'] = $value->exam_id;
            $getExamSubject = MarksRegisterModel::getExamSubject($value->exam_id, Auth::user()->id);

            $dataSubject = array();
            foreach ($getExamSubject as $exam) {
                $total_score = $exam['class_work'] + $exam['exam'];
                $totals_score = $total_score * $exam['ponde'];
                $dataS = array();
                $dataS['subject_name'] = $exam['subject_name'];
                $dataS['class_work'] = $exam['class_work'];
                $dataS['exam'] = $exam['exam'];
                $dataS['total_score'] = $total_score;
                $dataS['totals_score'] = $totals_score;
                $dataS['full_marks'] = $exam['full_marks'];
                $dataS['passing_mark'] = $exam['passing_mark'];
                $dataS['ponde'] = $exam['ponde'];
                $dataSubject[] = $dataS;
            }
            $dataE['subject'] = $dataSubject;
            $result[] = $dataE;
        }

        $data['getRecord'] = $result;
        $data['header_title'] = "My Exam Result";
        return view('student.my_exam_result', $data);
    }


    public function myExamResultPrint(Request $request)
    {
        $exam_id = $request->exam_id;
        $student_id = $request->student_id;

        $data['getExam'] = ExamModel::getSingle($exam_id);
        $data['getStudent'] = User::getSingle($student_id);

        $data['getClass'] = MarksRegisterModel::getClass($exam_id, $student_id);

        $data['getSetting'] = SettingModel::getSingle();

        $getExamSubject = MarksRegisterModel::getExamSubject($exam_id, $student_id);

        $dataSubject = array();
        foreach ($getExamSubject as $exam) {
            $total_score = $exam['class_work'] + $exam['exam'];
            $totals_score = $total_score * $exam['ponde'];

            $dataS = array();
            $dataS['subject_code'] = $exam['subject_code'];
            $dataS['subject_name'] = $exam['subject_name'];
            $dataS['class_work'] = $exam['class_work'];
            $dataS['exam'] = $exam['exam'];
            $dataS['total_score'] = $total_score;
            $dataS['totals_score'] = $totals_score;
            $dataS['ponde'] = $exam['ponde'];
            $dataS['full_marks'] = $exam['full_marks'];
            $dataS['passing_mark'] = $exam['passing_mark'];
            $dataSubject[] = $dataS;
        }

        $data['getExamMark'] = $dataSubject;

        return view('exam_result_print', $data);
    }

    public function printClassResults(Request $request)
    {
        $exam_id = $request->input('exam_id');
        $class_id = $request->input('class_id');

        $getSetting = SettingModel::getSingle();

        // Vérifiez que les paramètres existent
        if (!$exam_id || !$class_id) {
            return redirect()->back()->with('error', 'Paramètres manquants.');
        }

        // Récupérez les données nécessaires (exemple)
        $class = ClassModel::find($class_id); // Récupération de la classe
        // Vérifier si la classe existe
        if (!$class) {
            return redirect()->back()->with('error', 'Classe non trouvée.');
        }

        // Récupérer les matières associées à la classe
        $subjects = $class->subjects; // Cela récupère toutes les matières associées à la classe




        // Récupérer les étudiants de la classe depuis la table 'users' avec 'type = 3'
        $students = User::getStudentClass($request->get('class_id'));

        // Récupérer les résultats avec les informations nécessaires
        $results = MarksRegisterModel::select(
            'marks_register.class_work',
            'marks_register.exam',
            'marks_register.ponde',
            'marks_register.subject_id',
            // 'subject.name as subject_name',
            'marks_register.student_id',
            'marks_register.class_id',
            'marks_register.exam_id'
        )
            ->join('subject', 'subject.id', '=', 'marks_register.subject_id')  // Jointure avec la table 'subject' pour récupérer le nom du cours
            ->where('marks_register.exam_id', $exam_id)
            ->where('marks_register.class_id', $class_id)
            ->get(); // Résultats pour l'examen et la classe
        // Relier les résultats aux étudiants

        foreach ($students as $student) {
            // Récupérer tous les résultats de l'étudiant
            $student->results = $results->where('student_id', $student->id);
        }

        // Transmettez les données à la vue d'impression
        return view('result_print', compact('class', 'getSetting', 'students', 'subjects', 'results', 'exam_id'));
    }

    // teacher side work



    public function MyExamTimetableTeacher()
    {
        $result = [];
        $getClass = AssignClassTeacherModel::getMyClassSubjectGroup(Auth::user()->id);

        foreach ($getClass as $class) {
            $dataC = [];
            $dataC['class_name'] = $class->class_name;
            $dataC['class_opt'] = $class->class_opt;
            $classId = $class->class_id; // On récupère l'ID de la classe

            // Vérifie si la classe a déjà été ajoutée au résultat
            if (isset($result[$classId])) {
                continue; // Passer si la classe existe déjà
            }

            // Récupérer les examens pour cette classe
            $getExam = ExamScheduleModel::getExam($classId);
            $examData = [];

            foreach ($getExam as $exam) {
                $getExamTimetable = ExamScheduleModel::getExamTimetable1($exam->exam_id, $classId, Auth::user()->id);

                // Si l'examen a des sujets, on les ajoute à l'examen
                if ($getExamTimetable->count() > 0) {
                    $examData[] = [
                        'exam_name' => $exam->exam_name,
                        'subjects' => $getExamTimetable,
                    ];
                }
            }

            // Si l'examen a été trouvé pour la classe, on ajoute à $result
            if (!empty($examData)) {
                $dataC['exam'] = $examData;
                $result[$classId] = $dataC; // Utilise l'ID de la classe comme clé
            }
        }

        $data['getRecord'] = array_values($result); // Réinitialise les clés
        $data['header_title'] = "My Exam Timetable";
        return view('teacher.my_exam_timetable', $data);
    }



    // parent side

    public function ParentMyExamTimetable($student_id)
    {
        $getStudent = User::getSingle($student_id);

        $class_id = $getStudent->class_id;
        $getExam = ExamScheduleModel::getExam($class_id);
        $result = array();
        foreach ($getExam as $value) {
            $dataE = array();
            $dataE['name'] = $value->exam_name;
            $getExamTimetable = ExamScheduleModel::getExamTimetable($value->exam_id, $class_id);
            $resultS = array();
            foreach ($getExamTimetable as $valueS) {
                $dataS = array();
                $dataS['subject_name'] = $valueS->subject_name;
                $dataS['exam_date'] = $valueS->exam_date;
                $dataS['start_time'] = $valueS->start_time;
                $dataS['end_time'] = $valueS->end_time;
                $dataS['room_number'] = $valueS->room_number;
                $dataS['full_marks'] = $valueS->full_marks;
                $dataS['passing_mark'] = $valueS->passing_mark;
                $resultS[] = $dataS;
            }

            $dataE['exam'] = $resultS;
            $result[] = $dataE;
        }
        // dd($result);

        $data['getRecord'] = $result;
        $data['getStudent'] = $getStudent;
        $data['header_title'] = "Exam Timetable";
        return view('parent.my_exam_timetable', $data);
    }


    public function ParentMyExamResult($student_id)
    {
        $data['getStudent'] = User::getSingle($student_id);
        $result = array();
        $getExam = MarksRegisterModel::getExam($student_id);
        foreach ($getExam as $value) {
            $dataE = array();
            $dataE['exam_id'] = $value->exam_id;
            $dataE['exam_name'] = $value->exam_name;
            $getExamSubject = MarksRegisterModel::getExamSubject($value->exam_id, $student_id);

            $dataSubject = array();
            foreach ($getExamSubject as $exam) {
                $total_score = $exam['class_work'] + $exam['exam'];
                $totals_score = $total_score * $exam['ponde'];
                $dataS = array();
                $dataS['subject_name'] = $exam['subject_name'];
                $dataS['class_work'] = $exam['class_work'];
                $dataS['exam'] = $exam['exam'];
                $dataS['total_score'] = $total_score;
                $dataS['totals_score'] = $totals_score;
                $dataS['full_marks'] = $exam['full_marks'];
                $dataS['passing_mark'] = $exam['passing_mark'];
                $dataS['ponde'] = $exam['ponde'];
                $dataSubject[] = $dataS;
            }
            $dataE['subject'] = $dataSubject;
            $result[] = $dataE;
        }

        $data['getRecord'] = $result;
        $data['header_title'] = "My Exam Result";
        return view('parent.my_exam_result', $data);
    }
}
