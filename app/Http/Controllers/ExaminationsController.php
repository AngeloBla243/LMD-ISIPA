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
use App\Models\semestre;
use App\Models\SettingModel;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\DB;
use App\Models\SubjectModel;
use App\Models\recours;
use Termwind\Components\Dd;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ExaminationsController extends Controller
{


    public function exam_list()
    {
        $getRecord = ExamModel::getRecord();
        // dd($getRecord);

        if ($getRecord->currentPage() > $getRecord->lastPage()) {
            return redirect($getRecord->url(1));
        }

        return view('admin.examinations.exam.list', compact('getRecord'));
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
            'session' => 'required|in:1,2', // Validation ajoutée
            'name' => 'required|array',
            'name.*' => 'nullable|string|max:255',
            'enabled' => 'required|array',
            'note' => 'nullable|string'
        ]);

        $session = $request->session; // Récupération correcte

        foreach ($request->name as $k => $examName) {
            if (isset($request->enabled[$k]) && $request->enabled[$k] && trim($examName) != '') {
                ExamModel::create([
                    'academic_year_id' => $request->academic_year_id,
                    'session' => $session, // Utilisation correcte
                    'name' => trim($examName),
                    'is_active' => $request->has('is_active') ? 1 : 0,
                    'note' => trim($request->note),
                    'created_by' => Auth::id()
                ]);
            }
        }

        return redirect('admin/examinations/exam/list')->with('success', "Examens créés avec succès.");
    }



    public function createSemester(Request $request)
    {
        $semesterName = $request->semester_name_custom ?: $request->semester_name;

        $request->validate([
            'session1_type' => 'required|in:1',
            'session2_type' => 'required|in:2',
            // ... autres validations
        ]);

        // Création automatique des deux sessions pour ce semestre
        ExamModel::create([
            'semester_name' => $semesterName,
            'name' => $semesterName . ' - Session Ordinaire',
            'session' => 1,
            'created_by' => Auth::id()
        ]);
        ExamModel::create([
            'semester_name' => $semesterName,
            'name' => $semesterName . ' - Session Rattrapage',
            'session' => 2,
            'created_by' => Auth::id()
        ]);

        // return redirect()->route('admin/examinations/exam/list')->with('success', 'Semestre et sessions créés avec succès');
        return redirect()->back()->with('success', 'Semestre créé avec deux sessions');
    }

    public function showCreateSemesterForm()
    {
        // Récupérer les semestres distincts déjà créés dans exam
        $semesters = ExamModel::select('name')->distinct()->pluck('name')->filter()->values();

        return view('admin.examinations.create_semester', [
            'semesters' => $semesters,
            'header_title' => "Créer un semestre et ses sessions"
        ]);
    }



    public function toggleExamActive($id)
    {
        $exam = ExamModel::findOrFail($id);
        $exam->is_active = !$exam->is_active;
        $exam->save();
        return back()->with('success', 'Statut de la session mis à jour.');
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
            'session' => 'required|in:1,2', // Ajouté
            'is_active' => 'nullable|boolean', // Ajouté
            'note' => 'nullable'
        ]);

        $exam = ExamModel::getSingle($id);

        $exam->name = trim($request->name);
        $exam->note = trim($request->note);
        $exam->academic_year_id = $request->academic_year_id;

        $exam->session = $request->session; // Ajouté
        $exam->is_active = $request->has('is_active') && $request->is_active == 1 ? 1 : 0; // Ajouté

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
        $teacherId = Auth::user()->id;
        // Récupérer l'année académique sélectionnée ou active
        $allAcademicYears = \App\Models\AcademicYear::orderBy('start_date', 'desc')->get();
        $currentYearId = session('academic_year_id', $allAcademicYears->where('is_active', 1)->first()?->id);

        // $data['getClass'] = AssignClassTeacherModel::getMyClassSubjectGroup($teacherId, $currentYearId);
        $data['getClass'] = \App\Models\AssignClassTeacherModel::where('teacher_id', $teacherId)
            ->where('academic_year_id', $currentYearId)
            ->with('class')
            ->get()
            ->map(function ($item) {
                return [
                    'class_id' => $item->class_id,
                    'class_name' => $item->class->name,
                    'class_opt' => $item->class->opt,
                ];
            });

        // Filtrer les examens par année académique
        $data['getExam'] = \App\Models\ExamModel::where('academic_year_id', $currentYearId)->get();

        if (!empty($request->get('exam_id')) && !empty($request->get('class_id'))) {
            $data['getSubject'] = ExamScheduleModel::getSubject_teacher($request->get('exam_id'), $request->get('class_id'), $teacherId);
            $data['getStudent'] = User::getStudentClass($request->get('class_id'), $currentYearId);
        }

        $data['header_title'] = "Marks Register";
        return view('teacher.marks_register', $data);
    }



    // public function submit_marks_register(Request $request)
    // {
    //     $validationFlag = 0;

    //     if (!empty($request->mark)) {
    //         $currentExam = ExamModel::findOrFail($request->exam_id);
    //         $semesterId = $currentExam->semester_id;

    //         foreach ($request->mark as $mark) {
    //             $getExamSchedule = ExamScheduleModel::getSingle($mark['id']);

    //             // Récupération des valeurs du planning
    //             $full_marks = $getExamSchedule->full_marks;
    //             $class = ClassModel::findOrFail($request->class_id);
    //             $academicYearId = $class->academic_year_id;

    //             $classWork = $mark['class_work'] ?? 0;
    //             $examScore = $mark['exam'] ?? 0;
    //             $totalScore = $classWork + $examScore;

    //             if ($totalScore <= $full_marks) {
    //                 // Enregistrement pour la session actuelle (STATUT TOUJOURS 1)
    //                 MarksRegisterModel::updateOrCreate(
    //                     [
    //                         'student_id' => $request->student_id,
    //                         'exam_id' => $currentExam->id,
    //                         'subject_id' => $mark['subject_id']
    //                     ],
    //                     [
    //                         'class_work' => $classWork,
    //                         'exam' => $examScore,
    //                         'full_marks' => $getExamSchedule->full_marks,
    //                         'passing_mark' => $getExamSchedule->passing_mark,
    //                         'ponde' => $getExamSchedule->ponde,
    //                         'status' => 1, // Modification manuelle → toujours 1
    //                         'class_id' => $request->class_id,
    //                         'academic_year_id' => $academicYearId,
    //                         'created_by' => Auth::id()
    //                     ]
    //                 );

    //                 // Copie automatique vers Session 2 UNIQUEMENT si Session 1
    //                 if ($currentExam->session == 1) {
    //                     $session2Exam = ExamModel::firstOrCreate(
    //                         ['semester_id' => $semesterId, 'session' => 2],
    //                         [
    //                             'name' => $currentExam->name . ' - Session Rattrapage',
    //                             'academic_year_id' => $currentExam->academic_year_id,
    //                             'created_by' => Auth::id()
    //                         ]
    //                     );

    //                     MarksRegisterModel::updateOrCreate(
    //                         [
    //                             'student_id' => $request->student_id,
    //                             'exam_id' => $session2Exam->id,
    //                             'subject_id' => $mark['subject_id']
    //                         ],
    //                         [
    //                             'class_work' => $classWork,
    //                             'exam' => $examScore,
    //                             'full_marks' => $getExamSchedule->full_marks,
    //                             'passing_mark' => $getExamSchedule->passing_mark,
    //                             'ponde' => $getExamSchedule->ponde,
    //                             'status' => 0, // Copie automatique → reste 0
    //                             'class_id' => $request->class_id,
    //                             'academic_year_id' => $academicYearId,
    //                             'created_by' => Auth::id()
    //                         ]
    //                     );
    //                 }
    //             } else {
    //                 $validationFlag = 1;
    //             }
    //         }
    //     }

    //     return response()->json([
    //         'success' => ($validationFlag == 0),
    //         'message' => $validationFlag
    //             ? "Enregistré avec certaines notes dépassant le maximum"
    //             : "Toutes les notes sont valides et enregistrées"
    //     ]);
    // }

    public function submit_marks_register(Request $request)
    {
        $validationFlag = 0;

        if (!empty($request->mark)) {
            // Récupérer l'examen avec la relation semestre
            $currentExam = ExamModel::with('semester')->findOrFail($request->exam_id);

            // Vérification robuste du semester_id
            if (!$currentExam->semester_id || !$currentExam->semester) {
                return response()->json([
                    'success' => false,
                    'message' => "Configuration invalide : le semestre n'est pas défini pour cet examen."
                ]);
            }

            $semesterId = $currentExam->semester_id;

            foreach ($request->mark as $mark) {
                // Vérification de la présence du subject_id
                if (!isset($mark['subject_id'])) {
                    $validationFlag = 1;
                    continue;
                }

                // Récupération du planning d'examen
                $getExamSchedule = ExamScheduleModel::where('id', $mark['id'])
                    ->where('exam_id', $currentExam->id)
                    ->first();

                if (!$getExamSchedule) {
                    $validationFlag = 1;
                    continue;
                }

                $full_marks = $getExamSchedule->full_marks;
                $class = ClassModel::findOrFail($request->class_id);
                $academicYearId = $class->academic_year_id;

                $classWork = $mark['class_work'] ?? 0;
                $examScore = $mark['exam'] ?? 0;
                $totalScore = $classWork + $examScore;

                if ($totalScore > $full_marks) {
                    $validationFlag = 1;
                    continue; // Passer au marqueur suivant
                }

                // Enregistrement Session 1 (STATUT 1)
                MarksRegisterModel::updateOrCreate(
                    [
                        'student_id' => $request->student_id,
                        'exam_id' => $currentExam->id,
                        'subject_id' => $mark['subject_id']
                    ],
                    [
                        'semester_id' => $semesterId, // Ajout explicite
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

                // Gestion Session 2 uniquement si Session 1
                if ($currentExam->session == 1) {
                    $session2Exam = ExamModel::firstOrCreate(
                        [
                            'semester_id' => $semesterId,
                            'session' => 2,
                            'academic_year_id' => $currentExam->academic_year_id
                        ],
                        [
                            'name' => $currentExam->name . ' - Session Rattrapage',
                            'created_by' => Auth::id()
                        ]
                    );

                    MarksRegisterModel::updateOrCreate(
                        [
                            'student_id' => $request->student_id,
                            'exam_id' => $session2Exam->id,
                            'subject_id' => $mark['subject_id']
                        ],
                        [
                            'semester_id' => $semesterId,
                            'class_work' => $classWork,
                            'exam' => $examScore,
                            'full_marks' => $full_marks,
                            'passing_mark' => $getExamSchedule->passing_mark,
                            'ponde' => $getExamSchedule->ponde,
                            'status' => 0, // Non validé par défaut
                            'class_id' => $request->class_id,
                            'academic_year_id' => $academicYearId,
                            'created_by' => Auth::id()
                        ]
                    );
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



    public function submit_all_marks_register(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'exam_id'   => 'required|exists:exam,id',
                'class_id'  => 'required|exists:class,id',
                'marks'     => 'required|array'
            ]);

            $currentExam = ExamModel::with('semester')->findOrFail($request->exam_id);
            $class = ClassModel::findOrFail($request->class_id);
            $academicYearId = $class->academic_year_id;

            if (!$currentExam->semester_id || !$currentExam->semester) {
                throw new \Exception("Configuration invalide : semestre non défini");
            }

            $session2Exam = null;
            if ($currentExam->session == 1) {
                $session2Exam = ExamModel::firstOrCreate(
                    [
                        'semester_id' => $currentExam->semester_id,
                        'session' => 2,
                        'academic_year_id' => $currentExam->academic_year_id
                    ],
                    [
                        'name' => $currentExam->name . ' - Rattrapage',
                        'created_by' => Auth::id()
                    ]
                );
            }

            $validationErrors = [];

            foreach ($request->marks as $studentId => $subjects) {
                foreach ($subjects as $subjectId => $mark) {
                    if (empty($mark['subject_id'])) {
                        $validationErrors[] = "Subject ID manquant pour l'étudiant $studentId";
                        continue;
                    }

                    $examSchedule = ExamScheduleModel::where([
                        'exam_id' => $currentExam->id,
                        'class_id' => $class->id,
                        'subject_id' => $subjectId
                    ])->first();

                    if (!$examSchedule) {
                        $validationErrors[] = "Aucun planning d'examen trouvé pour la matière {$subjectId}";
                        continue;
                    }

                    $classWork = $mark['class_work'] ?? 0;
                    $examScore = $mark['exam'] ?? 0;
                    $totalScore = $classWork + $examScore;

                    if ($totalScore > $examSchedule->full_marks) {
                        $validationErrors[] = "Note totale ($totalScore) > max ({$examSchedule->full_marks}) pour étudiant $studentId";
                        continue;
                    }

                    // Enregistrement Session 1
                    MarksRegisterModel::updateOrCreate(
                        [
                            'student_id' => $studentId,
                            'exam_id' => $currentExam->id,
                            'subject_id' => $subjectId
                        ],
                        [
                            'semester_id' => $currentExam->semester_id,
                            'class_work' => $classWork,
                            'exam' => $examScore,
                            'full_marks' => $examSchedule->full_marks,
                            'passing_mark' => $examSchedule->passing_mark,
                            'ponde' => $examSchedule->ponde,
                            'status' => 1,
                            'class_id' => $class->id,
                            'academic_year_id' => $academicYearId,
                            'created_by' => Auth::id()
                        ]
                    );

                    // Gestion Session 2
                    if ($session2Exam) {
                        MarksRegisterModel::updateOrCreate(
                            [
                                'student_id' => $studentId,
                                'exam_id' => $session2Exam->id,
                                'subject_id' => $subjectId
                            ],
                            [
                                'semester_id' => $currentExam->semester_id,
                                'class_work' => $classWork,
                                'exam' => $examScore,
                                'full_marks' => $examSchedule->full_marks,
                                'passing_mark' => $examSchedule->passing_mark,
                                'ponde' => $examSchedule->ponde,
                                'status' => 0,
                                'class_id' => $class->id,
                                'academic_year_id' => $academicYearId,
                                'created_by' => Auth::id()
                            ]
                        );
                    }
                }
            }

            if (!empty($validationErrors)) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Erreurs de validation',
                    'errors' => $validationErrors
                ], 422);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Toutes les notes ont été enregistrées avec succès'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur : ' . $e->getMessage()
            ], 500);
        }
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
        try {
            $request->validate([
                'student_id' => 'required|exists:users,id',
                'exam_id' => 'required|exists:exam,id',
                'class_id' => 'required|exists:class,id',
                'subject_id' => 'required|exists:subject,id',
                'class_work' => 'nullable|numeric|min:0',
                'exam' => 'nullable|numeric|min:0'
            ]);

            $currentExam = ExamModel::findOrFail($request->exam_id);
            $semesterId = $currentExam->semester_id;

            // Récupération du planning d'examen
            $getExamSchedule = ExamScheduleModel::findOrFail($request->id);
            $class = ClassModel::findOrFail($request->class_id);

            $classWork = $request->class_work ?? 0;
            $examScore = $request->exam ?? 0;
            $totalScore = $classWork + $examScore;

            if ($totalScore > $getExamSchedule->full_marks) {
                return response()->json([
                    'success' => false,
                    'message' => "La note totale dépasse le maximum autorisé"
                ]);
            }

            // Enregistrement pour la session actuelle (STATUT TOUJOURS 1)
            MarksRegisterModel::updateOrCreate(
                [
                    'student_id' => $request->student_id,
                    'exam_id' => $currentExam->id,
                    'subject_id' => $request->subject_id
                ],
                [
                    'class_work' => $classWork,
                    'exam' => $examScore,
                    'full_marks' => $getExamSchedule->full_marks,
                    'passing_mark' => $getExamSchedule->passing_mark,
                    'ponde' => $getExamSchedule->ponde,
                    'status' => 1, // Modification manuelle → toujours 1
                    'class_id' => $request->class_id,
                    'academic_year_id' => $class->academic_year_id,
                    'created_by' => Auth::id()
                ]
            );

            // Copie automatique vers Session 2 UNIQUEMENT si Session 1
            if ($currentExam->session == 1) {
                $session2Exam = ExamModel::firstOrCreate(
                    ['semester_id' => $semesterId, 'session' => 2],
                    [
                        'name' => $currentExam->name . ' - Session Rattrapage',
                        'academic_year_id' => $currentExam->academic_year_id,
                        'created_by' => Auth::id()
                    ]
                );

                MarksRegisterModel::updateOrCreate(
                    [
                        'student_id' => $request->student_id,
                        'exam_id' => $session2Exam->id,
                        'subject_id' => $request->subject_id
                    ],
                    [
                        'class_work' => $classWork,
                        'exam' => $examScore,
                        'full_marks' => $getExamSchedule->full_marks,
                        'passing_mark' => $getExamSchedule->passing_mark,
                        'ponde' => $getExamSchedule->ponde,
                        'status' => 0, // Copie automatique → reste 0
                        'class_id' => $request->class_id,
                        'academic_year_id' => $class->academic_year_id,
                        'created_by' => Auth::id()
                    ]
                );
            }

            return response()->json([
                'success' => true,
                'message' => "Note enregistrée avec succès"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Erreur : " . $e->getMessage()
            ]);
        }
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


    // public function myExamResult(Request $request)
    // {
    //     // Récupérer l'année académique
    //     $academicYearId = session(
    //         'academic_year_id',
    //         $request->get(
    //             'academic_year_id',
    //             AcademicYear::where('is_active', 1)->value('id')
    //         )
    //     );

    //     $result = array();

    //     // Récupérer les examens avec au moins une matière valide (status = 1)
    //     $getExam = MarksRegisterModel::getExams(Auth::user()->id, $academicYearId)
    //         ->filter(function ($exam) use ($academicYearId) {
    //             return MarksRegisterModel::getExamSubjects($exam->exam_id, Auth::user()->id, $academicYearId)
    //                 ->where('status', 1)
    //                 ->isNotEmpty();
    //         });

    //     foreach ($getExam as $value) {
    //         $dataE = array();
    //         $dataE['exam_name'] = $value->exam_name;
    //         $dataE['exam_id'] = $value->exam_id;

    //         // Récupérer uniquement les matières avec status = 1
    //         $getExamSubject = MarksRegisterModel::getExamSubjects($value->exam_id, Auth::user()->id, $academicYearId)
    //             ->where('status', 1);

    //         $dataSubject = array();
    //         foreach ($getExamSubject as $exam) {
    //             // Vérification redondante du statut
    //             if ($exam['status'] == 1) {
    //                 $total_score = $exam['class_work'] + $exam['exam'];
    //                 $totals_score = $total_score * $exam['ponde'];

    //                 $dataS = array(
    //                     'subject_name' => $exam['subject_name'],
    //                     'class_work' => $exam['class_work'],
    //                     'exam' => $exam['exam'],
    //                     'total_score' => $total_score,
    //                     'totals_score' => $totals_score,
    //                     'full_marks' => $exam['full_marks'],
    //                     'passing_mark' => $exam['passing_mark'],
    //                     'ponde' => $exam['ponde']
    //                 );
    //                 $dataSubject[] = $dataS;
    //             }
    //         }

    //         // Ne garder que les examens avec des matières valides
    //         if (!empty($dataSubject)) {
    //             $dataE['subject'] = $dataSubject;
    //             $result[] = $dataE;
    //         }
    //     }

    //     // Données pour le filtre
    //     $data['academicYears'] = AcademicYear::orderBy('start_date', 'desc')->get();
    //     $data['selectedAcademicYear'] = AcademicYear::find($academicYearId);
    //     $data['getRecord'] = $result;
    //     $data['header_title'] = "Mes résultats validés";

    //     return view('student.my_exam_result', $data);
    // }
    public function myExamResult(Request $request)
    {
        // Récupérer l'année académique
        $academicYearId = session(
            'academic_year_id',
            $request->get(
                'academic_year_id',
                AcademicYear::where('is_active', 1)->value('id')
            )
        );

        // Récupérer les examens avec au moins une matière valide (status = 1)
        $getExam = MarksRegisterModel::getExams(Auth::user()->id, $academicYearId)
            ->filter(function ($exam) use ($academicYearId) {
                return MarksRegisterModel::getExamSubjects($exam->exam_id, Auth::user()->id, $academicYearId)
                    ->where('status', 1)
                    ->isNotEmpty();
            });

        $result = [];
        foreach ($getExam as $value) {
            $dataE = [
                'exam_name' => $value->exam_name,
                'exam_id' => $value->exam_id,
                'session' => $value->session ?? 1, // Ajout de la session
            ];

            // Récupérer uniquement les matières avec status = 1
            $getExamSubject = MarksRegisterModel::getExamSubjects($value->exam_id, Auth::user()->id, $academicYearId)
                ->where('status', 1);

            $dataSubject = [];
            foreach ($getExamSubject as $exam) {
                if ($exam['status'] == 1) {
                    $total_score = $exam['class_work'] + $exam['exam'];
                    $totals_score = $total_score * $exam['ponde'];

                    $dataS = [
                        'subject_name' => $exam['subject_name'],
                        'subject_id' => $exam['subject_id'], // Ajout explicite
                        'class_work' => $exam['class_work'],
                        'exam' => $exam['exam'],
                        'total_score' => $total_score,
                        'totals_score' => $totals_score,
                        'full_marks' => $exam['full_marks'],
                        'passing_mark' => $exam['passing_mark'],
                        'ponde' => $exam['ponde'],
                        'session' => $value->session ?? 1, // Ajout de la session
                    ];
                    $dataSubject[] = $dataS;
                }
            }

            if (!empty($dataSubject)) {
                $dataE['subject'] = $dataSubject;
                $result[] = $dataE;
            }
        }

        // Données pour le filtre
        $data['academicYears'] = AcademicYear::orderBy('start_date', 'desc')->get();
        $data['selectedAcademicYear'] = AcademicYear::find($academicYearId);
        $data['getRecord'] = $result;
        $data['header_title'] = "Mes résultats validés";

        return view('student.my_exam_result', $data);
    }




    public function myExamResultPrint(Request $request)
    {
        $exam_id = $request->exam_id;
        $student_id = $request->student_id;

        // Récupérer l'examen
        $getExam = ExamModel::find($exam_id);

        // Récupérer l'étudiant
        $getStudent = User::find($student_id);

        // Récupérer la classe de l'étudiant pour cet examen
        $getClass = MarksRegisterModel::getClass($exam_id, $student_id);

        // Récupérer les paramètres de l'établissement
        $getSetting = SettingModel::first();

        // Récupérer les matières avec info UE, code, crédits, etc.
        $getExamSubject = MarksRegisterModel::select(
            'marks_register.*',
            'subject.name as subject_name',
            'subject.code as subject_code',
            'subject.ue_id',
            'exam_schedule.ponde'
        )
            ->join('subject', 'subject.id', '=', 'marks_register.subject_id')
            ->join('exam_schedule', function ($join) use ($exam_id) {
                $join->on('exam_schedule.exam_id', '=', 'marks_register.exam_id')
                    ->on('exam_schedule.class_id', '=', 'marks_register.class_id')
                    ->on('exam_schedule.subject_id', '=', 'marks_register.subject_id')
                    ->where('exam_schedule.exam_id', $exam_id);
            })
            ->where('marks_register.exam_id', $exam_id)
            ->where('marks_register.student_id', $student_id)
            ->get();

        // Organisation des données pour la vue
        $dataSubject = [];
        foreach ($getExamSubject as $exam) {
            $total_score = $exam->class_work + $exam->exam;

            $dataS = [
                'subject_id'    => $exam->subject_id,
                'subject_code'  => $exam->subject_code,
                'subject_name'  => $exam->subject_name,
                'class_work'    => $exam->class_work,
                'exam'          => $exam->exam,
                'total_score'   => $total_score,
                'ponde'         => $exam->ponde ?? 1,
                'full_marks'    => $exam->full_marks,
                'passing_mark'  => $exam->passing_mark,
                'ue_id'         => $exam->ue_id
            ];
            $dataSubject[] = $dataS;
        }

        // Passer toutes les données nécessaires à la vue
        return view('exam_result_print', [
            'getExamMark'   => $dataSubject,
            'getStudent'    => $getStudent,
            'getClass'      => $getClass,
            'getExam'       => $getExam,
            'getSetting'    => $getSetting,
        ]);
    }

    // public function generateAnnualResultPrint(Request $request)
    // {
    //     $student_id = $request->student_id;
    //     $academic_year_id = $request->academic_year_id;

    //     // Récupération des données principales
    //     $student = User::findOrFail($student_id);
    //     $academicYear = AcademicYear::findOrFail($academic_year_id);
    //     $setting = SettingModel::first();

    //     // Classe pour l'année
    //     $class = $student->studentClasses()
    //         ->wherePivot('academic_year_id', $academic_year_id)
    //         ->first();

    //     // Récupérer toutes les notes validées (status=1) de l'année, avec les relations nécessaires
    //     $marks = MarksRegisterModel::with([
    //         'subject.ue' => function ($q) {
    //             $q->select('id', 'code', 'name', 'credits');
    //         }
    //     ])
    //         ->where('academic_year_id', $academic_year_id)
    //         ->where('student_id', $student_id)
    //         ->where('status', 1)
    //         ->get();

    //     // Organisation des données par semestre
    //     $semesters = [];
    //     $totalCreditsObtenus = 0;
    //     $totalCreditsPossibles = 0;
    //     $totalNotePonderee = 0;

    //     // Avant la boucle des semestres
    //     $annualResults = [
    //         'totalCreditsObtenus' => 0,
    //         'totalCreditsPossibles' => 0,
    //         'totalNotePonderee' => 0,
    //         'moyenneGenerale' => 0,
    //         'decision' => 'AUCUN RÉSULTAT'
    //     ];


    //     foreach ($marks->groupBy('semester_id') as $semesterId => $semesterMarks) {
    //         $semester = \App\Models\semestre::find($semesterId);
    //         if (!$semester) continue;

    //         $ues = [];
    //         $ecsAutonomes = [];
    //         $creditsSemestre = 0;
    //         $creditsPossiblesSemestre = 0;
    //         $notePondereeSemestre = 0;

    //         // Groupement par EC (subject_id)
    //         foreach ($semesterMarks->groupBy('subject_id') as $subjectId => $subjectMarks) {
    //             // Prendre la meilleure note (session 2 prioritaire)
    //             $bestMark = $subjectMarks->sortByDesc(function ($m) {
    //                 return [$m->session == 2 ? 1 : 0, $m->class_work + $m->exam];
    //             })->first();

    //             $subject = $bestMark->subject;
    //             $score = $bestMark->class_work + $bestMark->exam;
    //             $isSession2 = $bestMark->session == 2;
    //             $ponde = $bestMark->ponde; // ou $bestMark->ec_ponde si tu utilises la jointure exam_schedule

    //             $ecData = [
    //                 'subject' => $subject,
    //                 'score' => $score,
    //                 'ponde' => $ponde,
    //                 'is_session2' => $isSession2
    //             ];

    //             if ($subject && $subject->ue) {
    //                 $ueId = $subject->ue->id;
    //                 $ues[$ueId] ??= [
    //                     'ue' => $subject->ue,
    //                     'ecs' => [],
    //                     'total_notes' => 0,
    //                     'total_coeff' => 0
    //                 ];
    //                 $ues[$ueId]['ecs'][] = $ecData;
    //                 $ues[$ueId]['total_notes'] += $score * $ponde;
    //                 $ues[$ueId]['total_coeff'] += $ponde;
    //             } else {
    //                 $ecsAutonomes[] = $ecData;
    //             }
    //         }

    //         // Calcul des moyennes UE
    //         foreach ($ues as &$ue) {
    //             $ue['moyenne'] = $ue['total_coeff'] > 0
    //                 ? round($ue['total_notes'] / $ue['total_coeff'], 2)
    //                 : 0;

    //             if ($ue['moyenne'] >= 10) {
    //                 $creditsSemestre += $ue['ue']->credits;
    //             } elseif ($ue['moyenne'] >= 8) {
    //                 $ue['compensee'] = true;
    //             }
    //             $creditsPossiblesSemestre += $ue['ue']->credits;
    //             $notePondereeSemestre += $ue['moyenne'] * $ue['ue']->credits;
    //         }

    //         // EC autonomes (sans UE)
    //         foreach ($ecsAutonomes as $ec) {
    //             if ($ec['score'] >= 10) {
    //                 $creditsSemestre += $ec['ponde'];
    //             }
    //             $creditsPossiblesSemestre += $ec['ponde'];
    //             $notePondereeSemestre += $ec['score'] * $ec['ponde'];
    //         }

    //         // Compensation LMD
    //         $moyenneSemestre = $creditsPossiblesSemestre > 0
    //             ? round($notePondereeSemestre / $creditsPossiblesSemestre, 2)
    //             : 0;

    //         if ($moyenneSemestre >= 10) {
    //             foreach ($ues as $ue) {
    //                 if (isset($ue['compensee'])) {
    //                     $creditsSemestre += $ue['ue']->credits;
    //                 }
    //             }
    //         }

    //         // Stockage des données du semestre
    //         $semesters[$semester->name] = [
    //             'ues' => array_values($ues),
    //             'ecsAutonomes' => $ecsAutonomes,
    //             'credits_obtenus' => $creditsSemestre,
    //             'credits_possibles' => $creditsPossiblesSemestre,
    //             'moyenne_semestre' => $moyenneSemestre
    //         ];

    //         $totalCreditsObtenus += $creditsSemestre;
    //         $totalCreditsPossibles += $creditsPossiblesSemestre;
    //         $totalNotePonderee += $notePondereeSemestre;
    //     }

    //     // Calculs finaux
    //     $moyenneGenerale = $totalCreditsPossibles > 0
    //         ? round($totalNotePonderee / $totalCreditsPossibles, 2)
    //         : 0;

    //     $decision = ($totalCreditsObtenus / max($totalCreditsPossibles, 1)) >= 0.75
    //         ? 'ADMIS EN ANNÉE SUPÉRIEURE'
    //         : 'REDOUBLEMENT';

    //     return view('exam_year_result_print', [
    //         'student' => $student,
    //         'setting' => $setting,
    //         'class' => $class,
    //         'academicYear' => $academicYear->name,
    //         'semesters' => $semesters,
    //         'totalCreditsObtenus' => $totalCreditsObtenus,
    //         'totalCreditsPossibles' => $totalCreditsPossibles,
    //         'moyenneGenerale' => $moyenneGenerale,
    //         'annualResults' => $annualResults,
    //         'decision' => $decision
    //     ]);
    // }

    public function generateAnnualResultPrint(Request $request)
    {
        $student_id = $request->student_id;
        $academic_year_id = $request->academic_year_id;

        // Récupération des données principales
        $student = User::findOrFail($student_id);
        $academicYear = AcademicYear::findOrFail($academic_year_id);
        $setting = SettingModel::first();

        // Classe pour l'année
        $class = $student->studentClasses()
            ->wherePivot('academic_year_id', $academic_year_id)
            ->first();

        // Récupérer toutes les notes validées (status=1) de l'année, avec les relations nécessaires
        $marks = MarksRegisterModel::with([
            'subject.ue' => function ($q) {
                $q->select('id', 'code', 'name', 'credits');
            }
        ])
            ->where('academic_year_id', $academic_year_id)
            ->where('student_id', $student_id)
            ->where('status', 1)
            ->get();

        // Organisation des données par semestre
        $semesters = [];
        $annualResults = [
            'totalCreditsObtenus' => 0,
            'totalCreditsPossibles' => 0,
            'totalNotePonderee' => 0,
            'moyenneGenerale' => 0,
            'decision' => 'AUCUN RÉSULTAT'
        ];

        foreach ($marks->groupBy('semester_id') as $semesterId => $semesterMarks) {
            $semester = \App\Models\semestre::find($semesterId);
            if (!$semester) continue;

            $ues = [];
            $ecsAutonomes = [];
            $creditsSemestre = 0;
            $creditsPossiblesSemestre = 0;
            $notePondereeSemestre = 0;

            // Groupement par EC (subject_id)
            foreach ($semesterMarks->groupBy('subject_id') as $subjectId => $subjectMarks) {
                // Prendre la meilleure note (session 2 prioritaire)
                $bestMark = $subjectMarks->sortByDesc(function ($m) {
                    return [$m->session == 2 ? 1 : 0, $m->class_work + $m->exam];
                })->first();

                $subject = $bestMark->subject;
                $score = $bestMark->class_work + $bestMark->exam;
                $isSession2 = $bestMark->session == 2;
                $ponde = $bestMark->ponde;

                $ecData = [
                    'subject' => $subject,
                    'score' => $score,
                    'ponde' => $ponde,
                    'is_session2' => $isSession2
                ];

                if ($subject && $subject->ue) {
                    $ueId = $subject->ue->id;
                    $ues[$ueId] ??= [
                        'ue' => $subject->ue,
                        'ecs' => [],
                        'total_notes' => 0,
                        'total_coeff' => 0,
                        'compensee' => false,
                    ];
                    $ues[$ueId]['ecs'][] = $ecData;
                    $ues[$ueId]['total_notes'] += $score * $ponde;
                    $ues[$ueId]['total_coeff'] += $ponde;
                } else {
                    $ecsAutonomes[] = $ecData;
                }
            }

            // Calcul des moyennes UE et crédits capitalisés
            foreach ($ues as &$ue) {
                $ue['moyenne'] = $ue['total_coeff'] > 0
                    ? round($ue['total_notes'] / $ue['total_coeff'], 2)
                    : 0;

                if ($ue['moyenne'] >= 10) {
                    $creditsSemestre += $ue['ue']->credits;
                } elseif ($ue['moyenne'] >= 8) {
                    $ue['compensee'] = true;
                }
                $creditsPossiblesSemestre += $ue['ue']->credits;
                $notePondereeSemestre += $ue['moyenne'] * $ue['ue']->credits;
            }
            unset($ue);

            // EC autonomes capitalisés
            foreach ($ecsAutonomes as $ec) {
                if ($ec['score'] >= 10) {
                    $creditsSemestre += $ec['ponde'];
                }
                $creditsPossiblesSemestre += $ec['ponde'];
                $notePondereeSemestre += $ec['score'] * $ec['ponde'];
            }

            // Compensation LMD
            $moyenneSemestre = $creditsPossiblesSemestre > 0
                ? round($notePondereeSemestre / $creditsPossiblesSemestre, 2)
                : 0;

            if ($moyenneSemestre >= 10) {
                foreach ($ues as $ue) {
                    if (isset($ue['compensee']) && $ue['compensee']) {
                        $creditsSemestre += $ue['ue']->credits;
                    }
                }
            }

            // Stockage des données du semestre
            $semesters[$semester->name] = [
                'ues' => array_values($ues),
                'ecsAutonomes' => $ecsAutonomes,
                'credits_obtenus' => $creditsSemestre,
                'credits_possibles' => $creditsPossiblesSemestre,
                'moyenne_semestre' => $moyenneSemestre
            ];

            $annualResults['totalCreditsObtenus'] += $creditsSemestre;
            $annualResults['totalCreditsPossibles'] += $creditsPossiblesSemestre;
            $annualResults['totalNotePonderee'] += $notePondereeSemestre;
        }

        // Calcul de la moyenne générale pondérée des semestres
        $moyennesSemestres = [];
        $creditsSemestres = [];

        foreach ($semesters as $semestreData) {
            $moyennesSemestres[] = $semestreData['moyenne_semestre'];
            $creditsSemestres[] = $semestreData['credits_possibles'];
        }

        $totalCoeff = array_sum($creditsSemestres);
        $moyenneGenerale = 0;

        if ($totalCoeff > 0) {
            foreach ($moyennesSemestres as $k => $moySem) {
                $moyenneGenerale += $moySem * $creditsSemestres[$k];
            }
            $moyenneGenerale = round($moyenneGenerale / $totalCoeff, 2);
        }

        // Arrondi selon règle
        $moyenneGeneraleArrondie = ($moyenneGenerale - floor($moyenneGenerale) >= 0.5)
            ? ceil($moyenneGenerale)
            : floor($moyenneGenerale);

        // Décision finale
        $decision = ($annualResults['totalCreditsObtenus'] / max($annualResults['totalCreditsPossibles'], 1)) >= 0.75
            ? 'ADMIS EN ANNÉE SUPÉRIEURE'
            : 'REDOUBLEMENT';

        return view('exam_year_result_print', [
            'student' => $student,
            'setting' => $setting,
            'class' => $class,
            'academicYear' => $academicYear->name,
            'semesters' => $semesters,
            'totalCreditsObtenus' => $annualResults['totalCreditsObtenus'],
            'totalCreditsPossibles' => $annualResults['totalCreditsPossibles'],
            'moyenneGenerale' => $moyenneGeneraleArrondie,
            'decision' => $decision
        ]);
    }







    // RECOURS

    public function MySubjectRecours(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'subject_id' => 'required|exists:subject,id',
            'exam_id' => 'required|exists:exam,id',
            'session' => 'required|in:1,2',
            'objet' => 'required|array'
        ]);

        try {
            $academicYearId = $request->academic_year_id;

            // Récupérer la classe de l'étudiant pour cette année
            $studentClass = DB::table('student_class')
                ->where('student_id', Auth::id())
                ->where('academic_year_id', $academicYearId)
                ->first();

            if (!$studentClass) {
                return response()->json([
                    'error' => 'Vous n\'êtes pas inscrit dans une classe pour cette année académique.'
                ], 400);
            }

            // Numéro de recours incrémenté par année académique
            $lastRecours = Recours::where('academic_year_id', $academicYearId)
                ->orderBy('numero', 'desc')
                ->first();

            $nextNumero = $lastRecours ? $lastRecours->numero + 1 : 1;

            $save = new Recours();
            $save->numero = $nextNumero;
            $save->student_id = Auth::id();
            $save->class_id = $studentClass->class_id;
            $save->academic_year_id = $academicYearId;
            $save->subject_id = $request->subject_id;
            $save->exam_id = $request->exam_id;
            $save->session = $request->session;
            $save->objet = implode(', ', $request->objet);
            $save->session_year = Carbon::now()->format('F Y');
            $save->save();

            return response()->json([
                'success' => true,
                'nextNumero' => $nextNumero,
                'session_year' => $save->session_year
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur création recours : ' . $e->getMessage());
            return response()->json([
                'error' => 'Une erreur est survenue lors de la création du recours'
            ], 500);
        }
    }






    public function printClassResults(Request $request)
    {
        $exam_id = $request->input('exam_id');
        $class_id = $request->input('class_id');

        if (!$exam_id || !$class_id) {
            return redirect()->back()->with('error', 'Paramètres manquants.');
        }

        $class = ClassModel::find($class_id);
        if (!$class) {
            return redirect()->back()->with('error', 'Classe non trouvée.');
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

        // Récupérer les étudiants de la classe
        $students = User::getStudentClass($class_id);

        $getSetting = SettingModel::getSingle();

        // Récupérer tous les résultats
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
