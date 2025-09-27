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

class JuryMarksRegisterController extends Controller
{
    public function list(Request $request)
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
                ->where('status', 0)
                ->where('is_delete', 0)
                ->get();

            $filteredExams = ExamModel::where('academic_year_id', $selectedAcademicYearId)->get();
        }

        $data['filteredClasses'] = $filteredClasses;
        $data['filteredExams'] = $filteredExams;

        // Récupérer sujets, étudiants et notes si filtres appliqués
        if ($request->filled('exam_id') && $request->filled('class_id')) {
            $data['getSubject'] = ExamScheduleModel::getSubject($request->exam_id, $request->class_id);

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
        $data['header_title'] = "Fiche de Jury";

        return view('jury.marks_register', $data);
    }

    // Méthode optionnelle équivalente à printClassResults si besoin
    //...

    public function saveAllMarks(Request $request)
    {
        $validationFlag = 0;
        $marksInput = $request->input('marks');

        if (!empty($marksInput) && $request->filled('exam_id') && $request->filled('class_id')) {
            $currentExam = ExamModel::with('semester')->findOrFail($request->exam_id);

            if (!$currentExam->semester_id || !$currentExam->semester) {
                return response()->json([
                    'success' => false,
                    'message' => "Le semestre n'est pas défini pour cet examen."
                ]);
            }

            $semesterId = $currentExam->semester_id;
            $class = ClassModel::findOrFail($request->class_id);
            $academicYearId = $class->academic_year_id;

            foreach ($marksInput as $studentId => $subjectsMarks) {
                foreach ($subjectsMarks as $subjectId => $markData) {
                    $getExamSchedule = ExamScheduleModel::where('exam_id', $currentExam->id)
                        ->where('subject_id', $subjectId)
                        ->first();

                    if (!$getExamSchedule) {
                        $validationFlag = 1;
                        continue;
                    }

                    $classWork = $markData['class_work'] ?? 0;
                    $examScore = $markData['exam'] ?? 0;
                    $totalScore = $classWork + $examScore;
                    $full_marks = $getExamSchedule->full_marks;

                    if ($totalScore > $full_marks) {
                        $validationFlag = 1;
                        continue;
                    }

                    // ✅ Session 1
                    MarksRegisterModel::updateOrCreate(
                        [
                            'student_id' => $studentId,
                            'exam_id' => $currentExam->id,
                            'subject_id' => $subjectId
                        ],
                        [
                            'semester_id' => $semesterId,
                            'class_work' => $classWork,
                            'exam' => $examScore,
                            'full_marks' => $full_marks,
                            'passing_mark' => $getExamSchedule->passing_mark,
                            'ponde' => $getExamSchedule->ponde,
                            'status' => 1,
                            'class_id' => $request->class_id,
                            'academic_year_id' => $academicYearId,
                            'created_by' => Auth::id()
                        ]
                    );

                    // ✅ Session 2 (si Session 1)
                    if ($currentExam->session == 1) {
                        $session2Exam = ExamModel::firstOrCreate(
                            [
                                'semester_id' => $semesterId,
                                'session' => 2,
                                'academic_year_id' => $currentExam->academic_year_id,
                            ],
                            [
                                'name' => $currentExam->name . ' - Session Rattrapage',
                                'created_by' => Auth::id(),
                            ]
                        );

                        MarksRegisterModel::updateOrCreate(
                            [
                                'student_id' => $studentId,
                                'exam_id' => $session2Exam->id,
                                'subject_id' => $subjectId
                            ],
                            [
                                'semester_id' => $semesterId,
                                'class_work' => $classWork,
                                'exam' => $examScore,
                                'full_marks' => $full_marks,
                                'passing_mark' => $getExamSchedule->passing_mark,
                                'ponde' => $getExamSchedule->ponde,
                                'status' => 0,
                                'class_id' => $request->class_id,
                                'academic_year_id' => $academicYearId,
                                'created_by' => Auth::id()
                            ]
                        );
                    }
                }
            }
        }

        return response()->json([
            'success' => ($validationFlag == 0),
            'message' => $validationFlag
                ? "Enregistré avec certaines notes dépassant le maximum ou données manquantes"
                : "Toutes les notes sont valides et enregistrées"
        ]);
    }
}
