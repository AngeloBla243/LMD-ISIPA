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
use App\Models\AcademicYear;
use Illuminate\Support\Facades\DB;
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
        $data['academicYears'] = \App\Models\AcademicYear::orderBy('start_date', 'desc')->get();
        $data['header_title'] = "Add New Exam";
        return view('admin.examinations.exam.add', $data);
    }


    public function exam_insert(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'name' => 'required|array',
            'name.*' => 'nullable|string|max:255',
            'enabled' => 'required|array',
            'note' => 'nullable|string'
        ]);
        $names = $request->name;
        $enabled = $request->enabled ?? [];

        foreach ($names as $k => $examName) {
            // Vérifie si activé (case cochée)
            if (isset($enabled[$k]) && $enabled[$k] && trim($examName) != '') {
                $exam = new ExamModel();
                $exam->academic_year_id = $request->academic_year_id;
                $exam->name = trim($examName);
                $exam->note = trim($request->note);
                $exam->created_by = auth()->id();
                $exam->save();
            }
        }
        return redirect('admin/examinations/exam/list')->with('success', "Exams successfully created.");
    }


    public function exam_edit($id)
    {
        $data['getRecord'] = ExamModel::getSingle($id);

        // Récupérer toutes les années académiques
        $data['academicYears'] = AcademicYear::orderBy('start_date', 'desc')->get();

        if (!empty($data['getRecord'])) {
            $data['header_title'] = "Edit Exam";
            return view('admin.examinations.exam.edit', $data);
        } else {
            abort(404);
        }
    }

    public function exam_update($id, Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'name' => 'required',
            'note' => 'nullable'
        ]);

        $exam = ExamModel::getSingle($id);
        $exam->name = trim($request->name);
        $exam->note = trim($request->note);
        $exam->academic_year_id = $request->academic_year_id; // Nouveau champ
        $exam->save();

        return redirect('admin/examinations/exam/list')
            ->with('success', "Exam successfully updated");
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
        $data['academicYears'] = AcademicYear::orderBy('start_date', 'desc')->get();

        // Initialiser les variables
        $filteredClasses = collect();
        $filteredExams = collect();
        $selectedAcademicYearId = $request->get('academic_year_id');

        if ($selectedAcademicYearId) {
            // Récupérer les classes de l'année sélectionnée
            $filteredClasses = ClassModel::where('academic_year_id', $selectedAcademicYearId)->get();

            // Récupérer les examens de l'année sélectionnée
            $filteredExams = ExamModel::where('academic_year_id', $selectedAcademicYearId)->get();
        }

        $result = [];
        // if ($request->filled('exam_id') && $request->filled('class_id')) {
        //     // Vérifier que la classe appartient à l'année sélectionnée
        //     $class = ClassModel::find($request->get('class_id'));
        //     if ($class->academic_year_id != $selectedAcademicYearId) {
        //         return redirect()->back()->with('error', 'La classe ne correspond pas à l\'année académique sélectionnée');
        //     }

        $result = array();
        if (!empty($request->get('exam_id')) && !empty($request->get('class_id'))) {
            $getSubject = ClassSubjectModel::MySubjectAdmin($request->get('class_id'));
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

        $data['filteredClasses'] = $filteredClasses;
        $data['filteredExams'] = $filteredExams;
        $data['selectedAcademicYearId'] = $selectedAcademicYearId;
        $data['header_title'] = "Exam Schedule";
        return view('admin.examinations.exam_schedule', $data);
    }

    public function exam_schedule_insert(Request $request)
    {

        $request->validate([
            'exam_id' => 'required|exists:exam,id',
            'class_id' => 'required|exists:class,id',
            'academic_year_id' => 'required|exists:academic_years,id'
        ]);

        $academicYearId = $request->academic_year_id;

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
                    $exam->academic_year_id = $academicYearId;
                    $exam->created_by = Auth::user()->id;
                    $exam->save();
                }
            }
        }

        return redirect()->back()->with('success', "Exam Schedule Successfully Saved");
    }

    public function marks_register(Request $request)
    {
        // Récupérer toutes les années académiques
        $data['academicYears'] = AcademicYear::orderBy('start_date', 'desc')->get();
        $academicYearId = $request->get('academic_year_id');

        // Récupérer les étudiants avec ou sans filtre académique



        // Initialiser les variables filtrées
        $filteredClasses = collect();
        $filteredExams = collect();

        // Si une année est sélectionnée
        if ($request->filled('academic_year_id')) {
            $academicYearId = $request->get('academic_year_id');

            // Récupérer les classes de cette année
            $filteredClasses = ClassModel::where('academic_year_id', $academicYearId)->get();

            // Récupérer les examens de cette année
            $filteredExams = ExamModel::where('academic_year_id', $academicYearId)->get();
        }

        // Passer les données à la vue
        $data['filteredClasses'] = $filteredClasses;
        $data['filteredExams'] = $filteredExams;

        // Logique existante pour les matières et étudiants
        if ($request->filled('exam_id') && $request->filled('class_id')) {
            $data['getSubject'] = ExamScheduleModel::getSubject($request->get('exam_id'), $request->get('class_id'));
            // $data['getStudent'] = User::getStudentClass($request->get('class_id'));
            $data['getStudent'] = User::getStudentClass(
                $request->get('class_id'),
                $academicYearId // Transmettre l'année académique (peut être null)
            );
            // dd($data);
        }

        $data['header_title'] = "Registre des Notes";
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
                $class = ClassModel::find($request->class_id);
                $academicYearId = $class->academic_year_id;

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
                    $save->academic_year_id = $academicYearId; // Ajouté
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



    public function markRegisterModal(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:class,id',
            'exam_id' => 'required|exists:exam,id',
            'student_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subject,id',
            'academic_year_id' => 'required|exists:academic_years,id'
        ]);

        // Récupère l'étudiant
        $student = User::findOrFail($request->student_id);

        // Récupère la note (si existante) depuis mark_register
        $mark = MarksRegisterModel::where('student_id', $request->student_id)
            ->where('class_id', $request->class_id)
            ->where('exam_id', $request->exam_id)
            ->where('subject_id', $request->subject_id)
            ->where('academic_year_id', $request->academic_year_id) // Ajouté
            ->first();


        // Récupère les infos du sujet depuis la table subject (via la relation)
        $subject = SubjectModel::findOrFail($request->subject_id);

        $subject = ExamScheduleModel::with('subject')
            ->where('class_id', $request->class_id)
            ->where('exam_id', $request->exam_id)
            ->where('subject_id', $request->subject_id)
            ->first();

        return view('admin.recours.mark_register_modal', compact('student', 'subject', 'mark'));
    }



    public function updateSingleMark(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'class_id' => 'required|exists:class,id',
            'exam_id' => 'required|exists:exam,id',
            'subject_id' => 'required|exists:subject,id',
            'class_work' => 'required|numeric|min:0|max:20',
            'academic_year_id' => 'required|exists:academic_years,id',
            'exam' => 'required|numeric|min:0|max:20'
        ]);

        $markModel = MarksRegisterModel::firstOrNew([
            'student_id' => $request->student_id,
            'class_id' => $request->class_id,
            'exam_id' => $request->exam_id,
            'subject_id' => $request->subject_id,
            'academic_year_id' => $request->academic_year_id
        ]);

        $markModel->class_work = $request->class_work;
        $markModel->exam = $request->exam;
        $markModel->created_by = auth()->id();
        $markModel->save();

        return response()->json(['message' => 'Note enregistrée avec succès']);
    }






    public function single_submit_marks_register(Request $request)
    {
        $id = $request->id;
        $getExamSchedule = ExamScheduleModel::getSingle($id);

        $full_marks = $getExamSchedule->full_marks;
        $ponde = $getExamSchedule->ponde;
        $class = ClassModel::find($request->class_id);
        $academicYearId = $class->academic_year_id;

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
            $save->academic_year_id = $academicYearId; // Ajouté
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
        // Récupérer l'année académique
        $academicYearId = session('academic_year_id', AcademicYear::where('is_active', 1)->value('id'));

        // Récupérer la classe de l'étudiant via student_class
        $studentClass = DB::table('student_class')
            ->where('student_id', Auth::id())
            ->where('academic_year_id', $academicYearId)
            ->first();

        if (!$studentClass) {
            return redirect()->back()->with('error', 'Aucune classe assignée pour cette année académique');
        }

        $classId = $studentClass->class_id;

        // Récupérer les examens pour cette classe et année
        $getExam = ExamScheduleModel::getExam($classId, $academicYearId);

        $result = array();
        foreach ($getExam as $value) {
            $dataE = array();
            $dataE['name'] = $value->exam_name;
            $getExamTimetable = ExamScheduleModel::getExamTimetableS($value->exam_id, $classId, $academicYearId);

            $resultS = array();
            foreach ($getExamTimetable as $valueS) {
                $dataS = array();
                $dataS['subject_name'] = $valueS->subject_name;
                $dataS['exam_date'] = $valueS->exam_date;
                $dataS['start_time'] = date('H:i', strtotime($valueS->start_time));
                $dataS['end_time'] = date('H:i', strtotime($valueS->end_time));
                $dataS['room_number'] = $valueS->room_number;
                $dataS['full_marks'] = $valueS->full_marks;
                $dataS['passing_mark'] = $valueS->passing_mark;
                $resultS[] = $dataS;
            }

            $dataE['exam'] = $resultS;
            $result[] = $dataE;
        }

        // Données pour le filtre
        $data['academicYears'] = AcademicYear::orderBy('start_date', 'desc')->get();
        $data['selectedAcademicYear'] = AcademicYear::find($academicYearId);
        $data['getRecord'] = $result;
        $data['header_title'] = "Mon emploi du temps d'examens";

        return view('student.my_exam_timetable', $data);
    }

    public function myExamResult(Request $request)
    {
        // Récupérer l'année académique depuis la session ou la requête
        $academicYearId = session(
            'academic_year_id',
            $request->get(
                'academic_year_id',
                AcademicYear::where('is_active', 1)->value('id')
            )
        );

        $result = array();
        $getExam = MarksRegisterModel::getExams(Auth::user()->id, $academicYearId); // Ajout du paramètre

        foreach ($getExam as $value) {
            $dataE = array();
            $dataE['exam_name'] = $value->exam_name;
            $dataE['exam_id'] = $value->exam_id;
            $getExamSubject = MarksRegisterModel::getExamSubjects($value->exam_id, Auth::user()->id, $academicYearId); // Ajout du paramètre

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

        // Données supplémentaires pour le filtre
        $data['academicYears'] = AcademicYear::orderBy('start_date', 'desc')->get();
        $data['selectedAcademicYear'] = AcademicYear::find($academicYearId);
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

        // Récupérer l'année académique
        // $academicYear = AcademicYear::find($data['getClass']->academic_year_id);
        // $data['academicYear'] = $academicYear ? $academicYear->name : 'N/A';

        if ($data['getClass']) {
            $class = ClassModel::find($data['getClass']->class_id);
            $data['academicYear'] = $class->academicYear->name ?? 'N/A';
        } else {
            $data['academicYear'] = 'N/A';
        }
        // dd($data);

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

        // Vérifiez que les paramètres existent
        if (!$exam_id || !$class_id) {
            return redirect()->back()->with('error', 'Paramètres manquants.');
        }

        // Récupérez les données nécessaires
        $class = ClassModel::find($class_id);

        // Vérifier si la classe existe
        if (!$class) {
            return redirect()->back()->with('error', 'Classe non trouvée.');
        }

        // Récupérer les matières associées à la classe (Utiliser les Models pour ne pas devoir refaire le code)
        $subjects = ExamScheduleModel::getSubject($exam_id, $class_id);  // Utilisation du Model

        // Récupérer les étudiants de la classe
        $students = User::getStudentClass($class_id);

        $getSetting = SettingModel::getSingle();

        // Récupérer les résultats pour tous les étudiants de la classe
        $results = MarksRegisterModel::select(
            'marks_register.class_work',
            'marks_register.exam',
            'marks_register.ponde',
            'marks_register.subject_id',
            'marks_register.student_id',
            'marks_register.class_id',
            'marks_register.exam_id'
        )
            ->where('marks_register.exam_id', $exam_id)
            ->where('marks_register.class_id', $class_id)
            ->get();

        // Relier les résultats aux étudiants
        foreach ($students as $student) {
            $student->results = $results->where('student_id', $student->id);
        }

        // Récupérer l'option de la classe
        $opt = $class->opt;

        // Préparer les données pour la vue
        $data = [
            'class' => $class,
            'getSetting' => $getSetting,
            'students' => $students,
            'subjects' => $subjects,
            'results' => $results,
            'exam_id' => $exam_id,
            'opt' => $opt
        ];

        // Transmettez les données à la vue d'impression
        return view('result_print', $data);
    }


    public function MyExamTimetableTeacher()
    {
        // 1. Récupérer l'année académique sélectionnée
        $academicYearId = session('academic_year_id', AcademicYear::where('is_active', 1)->value('id'));

        $result = [];

        // 2. Récupérer les classes du professeur pour CETTE année
        $getClass = AssignClassTeacherModel::select('assign_class_teacher.*', 'class.*')
            ->join('class', 'class.id', '=', 'assign_class_teacher.class_id')
            ->where('assign_class_teacher.teacher_id', Auth::id())
            ->where('class.academic_year_id', $academicYearId)
            ->groupBy('class.id')
            ->get();

        foreach ($getClass as $class) {
            $dataC = [
                'class_name' => $class->name,
                'class_opt' => $class->opt,
                'academic_year' => AcademicYear::find($academicYearId)->name
            ];

            // 3. Récupérer les examens de la classe pour l'année
            $getExam = ExamScheduleModel::getExam($class->id, $academicYearId);
            $examData = [];

            foreach ($getExam as $exam) {
                $getExamTimetable = ExamScheduleModel::getExamTimetable1(
                    $exam->exam_id,
                    $class->id,
                    Auth::id(),
                    $academicYearId // Nouveau paramètre
                );

                if ($getExamTimetable->isNotEmpty()) {
                    $examData[] = [
                        'exam_name' => $exam->name,
                        'subjects' => $getExamTimetable,
                    ];
                }
            }

            if (!empty($examData)) {
                $dataC['exam'] = $examData;
                $result[] = $dataC;
            }
        }

        // 4. Données pour le filtre
        $data = [
            'getRecord' => $result,
            'academicYears' => AcademicYear::orderBy('start_date', 'desc')->get(),
            'selectedAcademicYear' => AcademicYear::find($academicYearId),
            'header_title' => "Mon emploi du temps d'examens"
        ];

        return view('teacher.my_exam_timetable', $data);
    }

    public function markRegisterModalTeacher(Request $request)
    {

        $request->validate([
            'class_id' => 'required|exists:class,id',
            'exam_id' => 'required|exists:exam,id',
            'student_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subject,id',
            'academic_year_id' => 'required|exists:academic_years,id'
        ]);


        // Vérifier les permissions du professeur
        $isAllowed = AssignClassTeacherModel::where('teacher_id', Auth::id())
            ->where('class_id', $request->class_id)
            ->where('subject_id', $request->subject_id)
            ->exists();

        if (!$isAllowed) {
            return response()->json([
                'success' => false,
                'message' => 'Accès non autorisé à cette matière'
            ], 403);
        }

        // Récupère l'étudiant
        $student = User::findOrFail($request->student_id);

        // Récupère la note (si existante) depuis mark_register
        $mark = MarksRegisterModel::where('student_id', $request->student_id)
            ->where('class_id', $request->class_id)
            ->where('exam_id', $request->exam_id)
            ->where('subject_id', $request->subject_id)
            ->where('academic_year_id', $request->academic_year_id) // Ajouté
            ->first();


        // Récupère les infos du sujet depuis la table subject (via la relation)
        $subject = SubjectModel::findOrFail($request->subject_id);

        $subject = ExamScheduleModel::with('subject')
            ->where('class_id', $request->class_id)
            ->where('exam_id', $request->exam_id)
            ->where('subject_id', $request->subject_id)
            ->first();

        return view('teacher.recours.mark_register_modal', compact('student', 'subject', 'mark'));
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
