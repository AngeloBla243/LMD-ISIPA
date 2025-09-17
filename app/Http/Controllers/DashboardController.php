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
        } else if (Auth::user()->user_type == 4) {
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

    public function departementDashboard()
    {
        $data['header_title'] = 'Dashboard Département';

        $user = Auth::user();

        // Total étudiants affectés à ce département
        $data['TotalStudent'] = User::where('user_type', 3)
            ->where('department_id', $user->department_id)
            ->count();

        // Autres statistiques spécifiques au département (à compléter selon besoin)
        // Exemple : total teachers dans le département (si lié), etc.

        return view('departement.dashboard', $data);
    }

    public function juryDashboard()
    {
        $data['header_title'] = 'Dashboard Jury';

        $user = Auth::user();

        // Récupérer les étudiants affectés au département du jury connecté
        $data['TotalStudent'] = User::where('user_type', 3)
            ->where('department_id', $user->department_id)  // jury lié à un département
            ->count();

        $data['students'] = User::where('user_type', 3)
            ->where('department_id', $user->department_id)
            ->get(['id', 'name', 'email']); // récupérer quelques champs utiles

        return view('jury.dashboard', $data);
    }


    public function apparitoratDashboard()
    {
        $data['header_title'] = 'Dashboard Apparitorat';

        // Récupérer tous les étudiants
        $data['TotalStudent'] = User::where('user_type', 3)->count();

        $data['students'] = User::where('user_type', 3)->get(['id', 'name', 'email']);

        return view('apparitorat.dashboard', $data);
    }
}
