<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\ExamModel;
use App\Models\ClassModel;
use App\Models\SubjectModel;
use App\Models\StudentAddFeesModel;
use App\Models\NoticeBoardModel;
use App\Models\AssignClassTeacherModel;
use App\Models\ClassSubjectModel;
use App\Models\HomeworkModel;
use App\Models\HomeworkSubmitModel;
use App\Models\StudentAttendanceModel;
use App\Models\AcademicYear;
use App\Models\FeeType;
use Illuminate\Support\Facades\DB;






class DashboardController extends Controller
{
    public function dashboard()
    {
        $data['header_title'] = 'Dashboard';
        if (Auth::user()->user_type == 1) {
            $data['getTotalFees'] = StudentAddFeesModel::getTotalFees();
            $data['getTotalTodayFees'] = StudentAddFeesModel::getTotalTodayFees();

            $data['TotalAdmin'] = User::getTotalUser(1);
            $data['TotalTeacher'] = User::getTotalUser(2);
            $data['TotalStudent'] = User::getTotalUser(3);
            $data['TotalParent'] = User::getTotalUser(4);

            $data['TotalExam'] = ExamModel::getTotalExam();
            $data['TotalClass'] = ClassModel::getTotalClass();
            $data['TotalSubject'] = SubjectModel::getTotalSubject();

            return view('admin.dashboard', $data);
        } else if (Auth::user()->user_type == 2) {
            // Récupérer l'année académique sélectionnée
            $academicYearId = session('academic_year_id', AcademicYear::where('is_active', 1)->value('id'));

            // Données pour le filtre
            $data['academicYears'] = AcademicYear::orderBy('start_date', 'desc')->get();
            $data['selectedAcademicYear'] = AcademicYear::find($academicYearId);

            // Statistiques filtrées par année académique
            $data['TotalStudent'] = User::getTeacherStudentCount(Auth::user()->id, $academicYearId);
            $data['TotalClass'] = AssignClassTeacherModel::getMyClassSubjectGroupCount(Auth::user()->id, $academicYearId);
            $data['TotalSubject'] = AssignClassTeacherModel::getMyClassSubjectCount(Auth::user()->id, $academicYearId);
            $data['TotalNoticeBoard'] = NoticeBoardModel::getRecordUserCount(Auth::user()->user_type);

            return view('teacher.dashboard', $data);
        }

        //else if (Auth::user()->user_type == 3) {
        //     $data['academicYears'] = AcademicYear::orderBy('start_date', 'desc')->get();
        //     $data['TotalPaidAmount'] = StudentAddFeesModel::TotalPaidAmountStudent(Auth::user()->id);
        //     $data['TotalSubject'] = ClassSubjectModel::MySubjectTotal(Auth::user()->class_id);
        //     $data['TotalNoticeBoard'] = NoticeBoardModel::getRecordUserCount(Auth::user()->user_type);
        //     $data['TotalHomework'] = HomeworkModel::getRecordStudentCount(Auth::user()->class_id, Auth::user()->id);
        //     $data['getRecord'] = ClassSubjectModel::MySubject(Auth::user()->class_id);

        //     $data['TotalSubmittedHomework'] = HomeworkSubmitModel::getRecordStudentCount(Auth::user()->id);

        //     $data['TotalAttendance'] = StudentAttendanceModel::getRecordStudentCount(Auth::user()->id);


        //     return view('student.dashboard', $data);
        // }
        else if (Auth::user()->user_type == 4) {
            $student_ids = User::getMyStudentIds(Auth::user()->id);
            $class_ids = User::getMyStudentClassIds(Auth::user()->id);

            if (!empty($student_ids)) {
                $data['TotalPaidAmount'] = StudentAddFeesModel::TotalPaidAmountStudentParent($student_ids);
                $data['TotalAttendance'] = StudentAttendanceModel::getRecordStudentParentCount($student_ids);

                $data['TotalSubmittedHomework'] = HomeworkSubmitModel::getRecordStudentParentCount($student_ids);
            } else {
                $data['TotalPaidAmount'] = 0;
                $data['TotalAttendance'] = 0;
                $data['TotalSubmittedHomework'] = 0;
            }


            $data['getTotalFees'] = StudentAddFeesModel::getTotalFees();
            $data['TotalStudent'] = User::getMyStudentCount(Auth::user()->id);
            $data['TotalNoticeBoard'] = NoticeBoardModel::getRecordUserCount(Auth::user()->user_type);
            return view('parent.dashboard', $data);
        }
    }


    // Dans le DashboardController

    // public function dash(Request $request)
    // {
    //     $academicYearId = session('academic_year_id') ?? AcademicYear::where('is_active', 1)->value('id');

    //     // Récupère la classe de l'étudiant pour cette année (via la table student_class)
    //     $studentClass = DB::table('student_class')
    //         ->where('student_id', Auth::id())
    //         ->where('academic_year_id', $academicYearId)
    //         ->value('class_id');

    //     // S'il n'est pas inscrit pour cette année, sortie propre
    //     if (!$studentClass) {
    //         $data['TotalSubject'] = 0;
    //         $data['getRecord'] = collect();
    //         $data['message'] = "Vous n'êtes pas inscrit dans une classe pour l'année académique sélectionnée.";
    //         return view('student.dashboard', $data);
    //     }

    //     $data['TotalSubject'] = ClassSubjectModel::MySubjectTotal($studentClass, $academicYearId);
    //     $data['getRecord'] = ClassSubjectModel::MySubject($studentClass, $academicYearId);

    //     // ... autres indicateurs dashboard

    //     return view('student.dashboard', $data);
    // }

    public function dash(Request $request)
    {
        $academicYearId = session('academic_year_id') ?? AcademicYear::where('is_active', 1)->value('id');

        // 1. La classe actuelle de l'étudiant pour l'année académique
        $studentClass = DB::table('student_class')
            ->where('student_id', Auth::id())
            ->where('academic_year_id', $academicYearId)
            ->value('class_id');

        if (!$studentClass) {
            $data['TotalSubject'] = 0;
            $data['getRecord'] = collect();
            $data['message'] = "Vous n'êtes pas inscrit dans une classe pour l'année académique sélectionnée.";

            // Aucun frais à montrer si pas de classe
            $data['fees'] = [];
            return view('student.dashboard', $data);
        }

        $data['TotalSubject'] = ClassSubjectModel::MySubjectTotal($studentClass, $academicYearId);
        $data['getRecord'] = ClassSubjectModel::MySubject($studentClass, $academicYearId);

        // 2. Récupération de tous les frais assignés à cette classe
        $feeTypes = FeeType::whereHas('classes', function ($query) use ($studentClass) {
            $query->where('class_id', $studentClass);
        })->get();

        // 3. Pour chaque frais, récupération du paiement de l'étudiant
        $fees = [];
        foreach ($feeTypes as $feeType) {
            $payment = StudentAddFeesModel::where('student_id', Auth::id())
                ->where('class_id', $studentClass)
                ->where('fee_type_id', $feeType->id)
                ->first();
            $fees[] = [
                'fee_type' => $feeType,
                'payment' => $payment
            ];
        }
        $data['fees'] = $fees;

        return view('student.dashboard', $data);
    }
}
