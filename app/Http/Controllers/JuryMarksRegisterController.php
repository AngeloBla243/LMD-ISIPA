<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcademicYear;
use App\Models\ClassModel;
use App\Models\ExamModel;
use App\Models\User;
use App\Models\MarksRegisterModel;
use App\Models\ExamScheduleModel;
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

            // Détection simple de péréquation possible
            $data['perequationNeeded'] = $this->detectPerequationNeed($data['getStudent'], $data['getSubject'], $request);
        } else {
            $data['perequationNeeded'] = false;
        }

        if (!empty($data['getStudent']) && !empty($data['getSubject'])) {
            $studentStats = [];
            foreach ($data['getStudent'] as $student) {
                $marks = MarksRegisterModel::where('student_id', $student->id)
                    ->where('exam_id', $request->exam_id)
                    ->where('class_id', $request->class_id)
                    ->get();

                $totalWeightedScore = 0;
                $totalWeights = 0;
                $totalCreditsObtained = 0;
                $totalCredits = 0;

                foreach ($marks as $mark) {
                    $score = ($mark->class_work ?? 0) + ($mark->exam ?? 0);
                    $weight = $mark->ponde ?? 1;
                    $totalWeightedScore += $score * $weight;
                    $totalWeights += $weight;
                    $totalCredits += $weight;
                    if ($score >= 10) $totalCreditsObtained += $weight;
                }
                $average = $totalWeights > 0 ? round($totalWeightedScore / $totalWeights, 2) : 0;

                $studentStats[$student->id] = [
                    'average' => $average,
                    'credits' => $totalCreditsObtained,
                ];
            }
            $data['studentStats'] = $studentStats;
        } else {
            $data['studentStats'] = [];
        }


        $data['selectedAcademicYearId'] = $selectedAcademicYearId;
        $data['header_title'] = "Fiche de Jury";

        return view('jury.marks_register', $data);
    }

    protected function detectPerequationNeed($students, $subjects, $request)
    {
        // Implémentation basique pour détecter péréquation :
        // si pour une UE il y a au moins un EC ≥ 10 et un EC < 10, on retourne true.
        $classId = $request->class_id;
        $examId = $request->exam_id;

        foreach ($students as $student) {
            $marks = MarksRegisterModel::where('student_id', $student->id)
                ->where('class_id', $classId)
                ->where('exam_id', $examId)
                ->with('subject.ue')
                ->get()
                ->groupBy(function ($mark) {
                    return $mark->subject->ue_id ?? 0;
                });

            foreach ($marks as $ueId => $markGroup) {
                if ($ueId == 0) continue;
                $hasPass = false;
                $hasFail = false;
                foreach ($markGroup as $mark) {
                    $score = ($mark->class_work ?? 0) + ($mark->exam ?? 0);
                    if ($score >= 10) $hasPass = true;
                    else $hasFail = true;
                    if ($hasPass && $hasFail) {
                        return true; // Péréquation détectée
                    }
                }
            }
        }
        return false;
    }

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
                                'subject_id' => $subjectId,
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
                                'created_by' => Auth::id(),
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

    // public function perequateMarks(Request $request)
    // {
    //     $examId = $request->exam_id;
    //     $classId = $request->class_id;

    //     if (!$examId || !$classId) {
    //         return response()->json(['success' => false, 'message' => 'Paramètres manquants.']);
    //     }

    //     $students = User::whereHas('studentClasses', function ($q) use ($classId) {
    //         $q->where('student_class.class_id', $classId);
    //     })->get();

    //     foreach ($students as $student) {
    //         $marks = MarksRegisterModel::where('student_id', $student->id)
    //             ->where('exam_id', $examId)
    //             ->where('class_id', $classId)
    //             ->with('subject.ue')
    //             ->get();

    //         $marksGroupedByUe = $marks->groupBy(function ($mark) {
    //             return $mark->subject->ue_id ?? 0;
    //         });

    //         foreach ($marksGroupedByUe as $ueId => $ueMarks) {
    //             if ($ueId == 0) continue; // Ignorer matières hors UE

    //             // Calculer moyenne pondérée UE
    //             $totalWeightedScore = 0;
    //             $totalWeights = 0;
    //             foreach ($ueMarks as $mark) {
    //                 $score = ($mark->class_work ?? 0) + ($mark->exam ?? 0);
    //                 $weight = $mark->ponde ?? 1;
    //                 $totalWeightedScore += $score * $weight;
    //                 $totalWeights += $weight;
    //             }
    //             $ueAverage = $totalWeights > 0 ? $totalWeightedScore / $totalWeights : 0;

    //             // Si moyenne UE >= 10, appliquer la péréquation EC par EC
    //             if ($ueAverage >= 10) {
    //                 // Détecter EC en dessous de 10 (notés faibles)
    //                 $under10 = $ueMarks->filter(function ($mark) {
    //                     $score = ($mark->class_work ?? 0) + ($mark->exam ?? 0);
    //                     return $score < 10;
    //                 });
    //                 $over10 = $ueMarks->filter(function ($mark) {
    //                     $score = ($mark->class_work ?? 0) + ($mark->exam ?? 0);
    //                     return $score >= 10;
    //                 });

    //                 if ($under10->count() > 0 && $over10->count() > 0) {
    //                     // Calcul du delta nécessaire pour chaque EC sous 10 et redistribution sur EC au-dessus
    //                     foreach ($under10 as $mark) {
    //                         $currentScore = ($mark->class_work ?? 0) + ($mark->exam ?? 0);
    //                         $neededTo10 = 10 - $currentScore;

    //                         // Ajuster cet EC à 10
    //                         // Ici on augmente class_work uniquement (exemple)
    //                         $newClassWork = ($mark->class_work ?? 0) + $neededTo10;
    //                         if ($newClassWork > $mark->full_marks) {
    //                             $newClassWork = $mark->full_marks; // limite max
    //                         }
    //                         // Mettre à jour la note
    //                         MarksRegisterModel::where('id', $mark->id)->update([
    //                             'class_work' => $newClassWork,
    //                             'status' => 1,
    //                         ]);
    //                     }

    //                     // Réduire l'excès sur EC > 10 (redistribuer équitablement)
    //                     $excessPoints = 0;
    //                     foreach ($over10 as $mark) {
    //                         $score = ($mark->class_work ?? 0) + ($mark->exam ?? 0);
    //                         $excessPoints += $score - $ueAverage; // surplus relatif à la moyenne UE
    //                     }
    //                     $reducePerEC = $over10->count() > 0 ? $excessPoints / $over10->count() : 0;

    //                     foreach ($over10 as $mark) {
    //                         $score = ($mark->class_work ?? 0) + ($mark->exam ?? 0);
    //                         $newScore = $score - $reducePerEC;
    //                         if ($newScore < 10) $newScore = 10; // ne pas descendre sous 10

    //                         // Ajuster class_work selon nouvelle note cible (exemple)
    //                         $newClassWork = $newScore > ($mark->exam ?? 0) ? $newScore - ($mark->exam ?? 0) : 0;
    //                         if ($newClassWork > $mark->full_marks) {
    //                             $newClassWork = $mark->full_marks;
    //                         }
    //                         MarksRegisterModel::where('id', $mark->id)->update([
    //                             'class_work' => $newClassWork,
    //                             'status' => 1,
    //                         ]);
    //                     }
    //                 }
    //             }
    //         }
    //     }

    //     return response()->json(['success' => true, 'message' => 'Péréquation appliquée selon règle EC.']);
    // }

    public function perequateMarks(Request $request)
    {
        $examId = $request->exam_id;
        $classId = $request->class_id;

        if (!$examId || !$classId) {
            return response()->json(['success' => false, 'message' => 'Paramètres manquants.']);
        }

        // Récupérer les étudiants de la classe
        $students = User::whereHas('studentClasses', function ($q) use ($classId) {
            $q->where('student_class.class_id', $classId);
        })->get();

        foreach ($students as $student) {
            // Récupérer notes avec relations sujet -> UE
            $marks = MarksRegisterModel::where('student_id', $student->id)
                ->where('exam_id', $examId)
                ->where('class_id', $classId)
                ->with('subject.ue')
                ->get();

            // Regrouper par UE
            $marksGroupedByUe = $marks->groupBy(function ($mark) {
                return $mark->subject->ue_id ?? 0;
            });

            foreach ($marksGroupedByUe as $ueId => $ueMarks) {
                if ($ueId == 0) continue; // Ignorer sans UE

                // Calculer moyenne pondérée UE
                $totalWeightedScore = 0;
                $totalWeights = 0;
                foreach ($ueMarks as $mark) {
                    $score = ($mark->class_work ?? 0) + ($mark->exam ?? 0);
                    $weight = $mark->ponde ?? 1;
                    $totalWeightedScore += $score * $weight;
                    $totalWeights += $weight;
                }
                $ueAverage = $totalWeights > 0 ? $totalWeightedScore / $totalWeights : 0;

                // Trouver si péréquation possible : un EC <10 et un EC >10
                $underTenMarks = $ueMarks->filter(function ($m) {
                    return (($m->class_work ?? 0) + ($m->exam ?? 0)) < 10;
                });
                $overTenMarks = $ueMarks->filter(function ($m) {
                    return (($m->class_work ?? 0) + ($m->exam ?? 0)) > 10;
                });

                if ($ueAverage >= 10 && $underTenMarks->count() > 0 && $overTenMarks->count() > 0) {
                    // Calculer total points à compenser
                    $compensateTotal = 0;
                    foreach ($underTenMarks as $m) {
                        $score = ($m->class_work ?? 0) + ($m->exam ?? 0);
                        $compensateTotal += (10 - $score); // points manquants sur chaque EC faible
                    }

                    // Appliquer compensation sur EC faibles (remonter à 10)
                    foreach ($underTenMarks as $m) {
                        $score = ($m->class_work ?? 0) + ($m->exam ?? 0);
                        $needed = 10 - $score;
                        $newClassWork = ($m->class_work ?? 0) + $needed;
                        if ($newClassWork > $m->full_marks) {
                            $newClassWork = $m->full_marks; // borne max
                        }
                        MarksRegisterModel::where('id', $m->id)->update([
                            'class_work' => $newClassWork,
                            'status' => 1,
                        ]);
                    }

                    // Répartir diminution sur EC >10 (proportionnellement au surplus)
                    $surplusTotal = 0;
                    foreach ($overTenMarks as $m) {
                        $score = ($m->class_work ?? 0) + ($m->exam ?? 0);
                        $surplusTotal += $score - 10; // surplus par EC
                    }

                    foreach ($overTenMarks as $m) {
                        $score = ($m->class_work ?? 0) + ($m->exam ?? 0);
                        $surplus = $score - 10;
                        $reducePoints = 0;
                        if ($surplusTotal > 0) {
                            $reducePoints = ($surplus / $surplusTotal) * $compensateTotal;
                        }
                        $newScore = $score - $reducePoints;
                        if ($newScore < 10) $newScore = 10; // pas descendre sous 10

                        $newClassWork = $newScore - ($m->exam ?? 0);
                        if ($newClassWork > $m->full_marks) $newClassWork = $m->full_marks;
                        if ($newClassWork < 0) $newClassWork = 0;

                        MarksRegisterModel::where('id', $m->id)->update([
                            'class_work' => $newClassWork,
                            'status' => 1,
                        ]);
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Péréquation appliquée avec succès.'
        ]);
    }
}
