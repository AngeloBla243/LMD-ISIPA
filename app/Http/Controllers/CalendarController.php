<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassSubjectModel;
use App\Models\WeekModel;
use App\Models\ClassSubjectTimetableModel;
use App\Models\ExamScheduleModel;
use App\Models\AssignClassTeacherModel;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\DB;
use App\Models\User;

use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    // public function MyCalendar()
    // {
    //     // Récupère l'ID de l'année académique dans l'ordre de priorité :
    //     // 1. Session > 2. Requête GET > 3. Année active par défaut
    //     $academicYearId = session(
    //         'academic_year_id',
    //         request()->get(
    //             'academic_year_id',
    //             AcademicYear::where('is_active', 1)->value('id')
    //         )
    //     );

    //     // Passe academicYearId aux méthodes
    //     $data['getMyTimetable'] = $this->getTimetable(
    //         Auth::user()->class_id,
    //         $academicYearId
    //     );

    //     $data['getExamTimetable'] = $this->getExamTimetable(
    //         Auth::user()->class_id,
    //         $academicYearId
    //     );

    //     // Pour le filtre dans la vue
    //     $data['academicYears'] = AcademicYear::orderBy('start_date', 'desc')->get();
    //     $data['selectedAcademicYear'] = AcademicYear::find($academicYearId);

    //     return view('student.my_calendar', $data);
    // }

    public function MyCalendar()
    {
        // 1. Récupérer l'année académique
        $academicYearId = session(
            'academic_year_id',
            request()->get(
                'academic_year_id',
                AcademicYear::where('is_active', 1)->value('id')
            )
        );

        // 2. Récupérer la classe de l'étudiant pour cette année académique
        $studentClass = DB::table('student_class')
            ->where('student_id', Auth::id())
            ->where('academic_year_id', $academicYearId)
            ->value('class_id');

        // 3. Vérifier si la classe existe
        if (!$studentClass) {
            return redirect()->back()->with('error', 'Aucune classe assignée pour cette année académique.');
        }

        // 4. Récupérer les données avec la classe correcte
        $data['getMyTimetable'] = $this->getTimetable($studentClass, $academicYearId);
        $data['getExamTimetable'] = $this->getExamTimetable($studentClass, $academicYearId);

        // 5. Données pour le filtre
        $data['academicYears'] = AcademicYear::orderBy('start_date', 'desc')->get();
        $data['selectedAcademicYear'] = AcademicYear::find($academicYearId);

        return view('student.my_calendar', $data);
    }



    public function getExamTimetable($class_id, $academic_year_id)
    {
        $getExam = ExamScheduleModel::getExam($class_id, $academic_year_id); // Passer 2 arguments
        // $getExam = ExamScheduleModel::getExam($class_id);

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

        return $result;
    }

    public function getTimetable($class_id, $academic_year_id)
    {
        $result = array();

        // Ajouter academic_year_id dans la requête
        $getRecord = ClassSubjectModel::MySubject($class_id, $academic_year_id);


        // $getRecord = ClassSubjectModel::MySubject($class_id);
        foreach ($getRecord as $value) {
            $dataS['name'] = $value->subject_name;

            $getWeek = WeekModel::getRecord();
            $week = array();
            foreach ($getWeek as $valueW) {
                $dataW = array();
                $dataW['week_name'] = $valueW->name;
                $dataW['fullcalendar_day'] = $valueW->fullcalendar_day;

                $ClassSubject = ClassSubjectTimetableModel::getRecordClassSubject($value->class_id, $value->subject_id, $valueW->id);

                if (!empty($ClassSubject)) {
                    $dataW['start_time'] = $ClassSubject->start_time;
                    $dataW['end_time'] = $ClassSubject->end_time;
                    $dataW['room_number'] = $ClassSubject->room_number;
                    $week[] = $dataW;
                }
            }

            $dataS['week'] = $week;
            $result[] = $dataS;
        }

        return $result;
    }

    // parent side

    public function MyCalendarParent($student_id)
    {
        $getStudent = User::getSingle($student_id);

        $data['getMyTimetable'] = $this->getTimetable($getStudent->class_id);
        $data['getExamTimetable'] = $this->getExamTimetable($getStudent->class_id);

        $data['getStudent'] = $getStudent;
        $data['header_title'] = "Student Calendar";
        return view('parent.my_calendar', $data);
    }

    // teacher side

    public function MyCalendarTeacher()
    {
        $teacher_id = Auth::user()->id;
        $data['getClassTimetable'] = AssignClassTeacherModel::getCalendarTeacher($teacher_id);
        $data['getExamTimetable'] = ExamScheduleModel::getExamTimetableTeacher($teacher_id);
        $data['header_title'] = "My Calendar";
        return view('teacher.my_calendar', $data);
    }
}
