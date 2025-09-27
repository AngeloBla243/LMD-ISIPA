<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcademicYear;
use App\Models\ClassModel;
use App\Models\ExamModel;
use App\Models\User;
use App\Models\MarksRegisterModel;
use App\Models\ExamScheduleModel;
use App\Models\SettingModel;
use Illuminate\Support\Facades\Auth;

class DepartementMarksRegisterController extends Controller
{
    public function marksRegister(Request $request)
    {
        $departmentId = Auth::user()->department_id;

        $data['academicYears'] = AcademicYear::orderBy('start_date', 'desc')->get();
        $selectedAcademicYearId = $request->get('academic_year_id');

        $filteredClasses = collect();
        $filteredExams = collect();

        // Récupérer classes et examens filtrés selon département et année académique
        if ($selectedAcademicYearId) {
            $filteredClasses = ClassModel::where('department_id', $departmentId)
                ->where('academic_year_id', $selectedAcademicYearId)
                ->get();

            $filteredExams = ExamModel::where('academic_year_id', $selectedAcademicYearId)->get();
        }

        $data['filteredClasses'] = $filteredClasses;
        $data['filteredExams'] = $filteredExams;

        // Récupérer sujets, étudiants et notes si filtres appliqués
        if ($request->filled('exam_id') && $request->filled('class_id')) {
            $data['getSubject'] = ExamScheduleModel::getSubject($request->exam_id, $request->class_id);

            // Récupérer les IDs de classes du département et de l'année pour filtrer les étudiants
            $classIdsInDept = $filteredClasses->pluck('id')->toArray();

            $data['getStudent'] = User::whereHas('studentClasses', function ($q) use ($classIdsInDept, $request) {
                $q->whereIn('student_class.class_id', $classIdsInDept)
                    ->where('student_class.class_id', $request->class_id)
                    ->where('student_class.academic_year_id', $request->academic_year_id);
            })->get();

            $data['marks'] = MarksRegisterModel::where('exam_id', $request->exam_id)
                ->where('class_id', $request->class_id)
                ->get()
                ->groupBy('student_id');
        }

        $data['selectedAcademicYearId'] = $selectedAcademicYearId;
        $data['header_title'] = "Fiche de Cotation (Lecture)";

        return view('departement.marks_register', $data);
    }

    public function printClassResults(Request $request)
    {
        $departmentId = Auth::user()->department_id;
        $exam_id = $request->input('exam_id');
        $class_id = $request->input('class_id');

        if (!$exam_id || !$class_id) {
            return redirect()->back()->with('error', 'Paramètres manquants.');
        }

        // Vérifier que la classe appartient au département
        $class = ClassModel::where('id', $class_id)
            ->where('department_id', $departmentId)
            ->first();

        if (!$class) {
            return redirect()->back()->with('error', 'Classe non trouvée ou non autorisée.');
        }

        // Récupérer les matières avec info UE
        $subjects = ExamScheduleModel::getSubject($exam_id, $class_id);
        foreach ($subjects as $subject) {
            $subjectModel = \App\Models\SubjectModel::with('ue')->find($subject->subject_id);
            $subject->ue_id = $subjectModel->ue_id ?? null;
            $subject->ue_code = $subjectModel->ue->code ?? null;
            $subject->ue_name = $subjectModel->ue->name ?? null;
            $subject->ue_credits = $subjectModel->ue->credits ?? null;
        }

        // Regrouper les matières par UE pour l'affichage
        $ues = [];
        $subjectsWithoutUe = [];
        foreach ($subjects as $subject) {
            if ($subject->ue_id && $subject->ue_code) {
                if (!isset($ues[$subject->ue_id])) {
                    $ues[$subject->ue_id] = [
                        'ue_code' => $subject->ue_code,
                        'ue_name' => $subject->ue_name,
                        'ue_credits' => $subject->ue_credits,
                        'subjects' => [],
                    ];
                }
                $ues[$subject->ue_id]['subjects'][] = $subject;
            } else {
                $subjectsWithoutUe[] = $subject;
            }
        }

        // Récupérer les étudiants de la classe (assurée dans ce département)
        $students = User::getStudentClass($class_id);

        $getSetting = \App\Models\SettingModel::getSingle();

        // Récupérer tous les résultats pour examen et classe
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

        // Associer les résultats aux étudiants
        foreach ($students as $student) {
            $student->results = $results->where('student_id', $student->id);
        }

        $opt = $class->opt;

        $data = [
            'class' => $class,
            'getSetting' => $getSetting,
            'students' => $students,
            'ues' => $ues,
            'subjectsWithoutUe' => $subjectsWithoutUe,
            'results' => $results,
            'exam_id' => $exam_id,
            'opt' => $opt
        ];

        return view('result_print', $data);
    }
}
