<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassModel;
use App\Models\ClassSubjectModel;
use App\Models\HomeworkModel;
use App\Models\AssignClassTeacherModel;
use App\Models\HomeworkSubmitModel;
use App\Models\SubjectModel;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\DB;
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


    // public function add()
    // {
    //     $data['getClass'] = ClassModel::getClass();
    //     $data['header_title'] = 'Add New Homework';
    //     return view('admin.homework.add', $data);
    // }

    public function add(Request $request)
    {
        // 1. Récupérer toutes les années académiques
        $data['academicYears'] = AcademicYear::orderBy('start_date', 'desc')->get();

        // 2. Si une année est choisie, charger les classes de cette année
        $data['selectedAcademicYear'] = null;
        $data['getClass'] = [];
        $data['getSubject'] = [];

        if ($request->filled('academic_year_id')) {
            $data['selectedAcademicYear'] = AcademicYear::find($request->academic_year_id);

            // Charger les classes de cette année
            $data['getClass'] = ClassModel::where('academic_year_id', $request->academic_year_id)
                ->where('is_delete', 0)
                ->where('status', 0)
                ->get();

            // Si une classe est sélectionnée, charger les matières de cette classe pour cette année
            if ($request->filled('class_id')) {
                $data['getSubject'] = ClassSubjectModel::where('class_id', $request->class_id)
                    ->where('academic_year_id', $request->academic_year_id)
                    ->with('subject')
                    ->get();
            }
        }

        $data['header_title'] = 'Ajouter un devoir';
        return view('admin.homework.add', $data);
    }




    public function insert(Request $request)
    {
        // $request->validate([
        //     'academic_year_id' => 'required|exists:academic_years,id',
        //     'class_id' => 'required|exists:class,id',
        //     'subject_id' => 'required|exists:subject,id',
        //     // ... autres règles
        // ]);

        $homework = new HomeworkModel;
        $homework->academic_year_id = $request->academic_year_id;
        $homework->class_id = $request->class_id;
        $homework->subject_id = $request->subject_id;
        $homework->homework_date = $request->homework_date;
        $homework->submission_date = $request->submission_date;
        $homework->description = $request->description;
        $homework->created_by = Auth::user()->id;

        if ($request->hasFile('document_file')) {
            $ext = $request->file('document_file')->getClientOriginalExtension();
            $file = $request->file('document_file');
            $randomStr = date('Ymdhis') . Str::random(20);
            $filename = strtolower($randomStr) . '.' . $ext;
            $file->move('upload/homework/', $filename);
            $homework->document_file = $filename;
        }
        $homework->save();

        return redirect('admin/homework/homework')->with('success', "Homework successfully created");
    }




    public function edit($id)
    {
        $getRecord = HomeworkModel::getSingle($id);

        // Années disponibles
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();

        // Année académique du devoir (pré-remplissage)
        $selectedAcademicYearId = request('academic_year_id', $getRecord->academic_year_id);

        // Classes de l'année sélectionnée
        $getClass = ClassModel::where('academic_year_id', $selectedAcademicYearId)
            ->where('is_delete', 0)
            ->where('status', 0)
            ->get();

        // Classe sélectionnée (pré-remplissage)
        $selectedClassId = request('class_id', $getRecord->class_id);

        // Matières de la classe et de l'année sélectionnées
        $getSubject = [];
        if ($selectedClassId) {
            $getSubject = ClassSubjectModel::where('class_id', $selectedClassId)
                ->where('academic_year_id', $selectedAcademicYearId)
                ->with('subject')
                ->get();
        }

        $data = [
            'getRecord' => $getRecord,
            'academicYears' => $academicYears,
            'getClass' => $getClass,
            'getSubject' => $getSubject,
            'header_title' => 'Edit Homework'
        ];
        return view('admin.homework.edit', $data);
    }



    // public function update(Request $request, $id)
    // {
    //     $homwork = HomeworkModel::getSingle($id);;
    //     $homwork->class_id = trim($request->class_id);
    //     $homwork->subject_id = trim($request->subject_id);
    //     $homwork->homework_date = trim($request->homework_date);
    //     $homwork->submission_date = trim($request->submission_date);
    //     $homwork->description = trim($request->description);

    //     if (!empty($request->file('document_file'))) {
    //         $ext = $request->file('document_file')->getClientOriginalExtension();
    //         $file = $request->file('document_file');
    //         $randomStr = date('Ymdhis') . Str::random(20);
    //         $filename = strtolower($randomStr) . '.' . $ext;
    //         $file->move('upload/homework/', $filename);

    //         $homwork->document_file = $filename;
    //     }

    //     $homwork->save();

    //     return redirect('admin/homework/homework')->with('success', "Homework successfully updated");
    // }

    public function update(Request $request, $id)
    {
        $homework = HomeworkModel::getSingle($id);

        $homework->academic_year_id = $request->academic_year_id;
        $homework->class_id = $request->class_id;
        $homework->subject_id = $request->subject_id;
        $homework->homework_date = $request->homework_date;
        $homework->submission_date = $request->submission_date;
        $homework->description = $request->description;

        if ($request->hasFile('document_file')) {
            $file = $request->file('document_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move('upload/homework/', $filename);
            $homework->document_file = $filename;
        }

        $homework->save();

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
        // Récupérer l'année académique
        $academicYearId = session('academic_year_id', AcademicYear::where('is_active', 1)->value('id'));

        // Récupérer la classe de l'étudiant pour cette année
        $studentClass = DB::table('student_class')
            ->where('student_id', Auth::id())
            ->where('academic_year_id', $academicYearId)
            ->first();

        if (!$studentClass) {
            return redirect()->back()->with('error', 'Aucune classe assignée pour cette année académique');
        }

        // Récupérer les devoirs avec filtre académique
        $getRecord = HomeworkModel::where('class_id', $studentClass->class_id)
            ->where('academic_year_id', $academicYearId)
            ->orderBy('homework_date', 'desc')
            ->paginate(10);

        // IDs des devoirs soumis
        $submittedHomeworkIds = HomeworkSubmitModel::where('student_id', Auth::id())
            ->pluck('homework_id')
            ->toArray();

        // Données pour la vue
        $data = [
            'getRecord' => $getRecord,
            'academicYears' => AcademicYear::orderBy('start_date', 'desc')->get(),
            'selectedAcademicYear' => AcademicYear::find($academicYearId),
            'submittedHomeworkIds' => $submittedHomeworkIds,
            'header_title' => 'Mes Devoirs'
        ];

        // dd($data);

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

    // public function HomeworkSubmittedStudent(Request $request)
    // {
    //     $data['getRecord'] = HomeworkSubmitModel::getRecordStudent(Auth::user()->id);
    //     $data['header_title'] = 'My Submitted Homework';
    //     return view('student.homework.submitted_list', $data);
    // }

    public function HomeworkSubmittedStudent(Request $request)
    {
        // 1. Définir l'année académique active (via session ou GET)
        $academicYearId = $request->get('academic_year_id', session(
            'academic_year_id',
            \App\Models\AcademicYear::where('is_active', 1)->value('id')
        ));

        // 2. Récupérer la/les classes pour CETTE année académique via la table student_class (pivot)
        $studentClasses = DB::table('student_class')
            ->where('student_id', Auth::id())
            ->where('academic_year_id', $academicYearId)
            ->pluck('class_id')->toArray();

        // Si pas de classe du tout, rien à afficher
        if (empty($studentClasses)) {
            $data['getRecord'] = collect();
            $data['header_title'] = 'My Submitted Homework';
            return view('student.homework.submitted_list', $data);
        }

        // 3. Récupérer les devoirs soumis pour ces classes et cette année académique
        $data['getRecord'] = \App\Models\HomeworkSubmitModel::with([
            'getHomework' => function ($q) use ($academicYearId, $studentClasses) {
                $q->where('academic_year_id', $academicYearId)
                    ->whereIn('class_id', $studentClasses);
            }
        ])
            ->where('student_id', Auth::id())
            // on peut aussi mettre un whereHas pour n’avoir que les devoirs de la bonne année
            ->whereHas('getHomework', function ($q) use ($academicYearId, $studentClasses) {
                $q->where('academic_year_id', $academicYearId)
                    ->whereIn('class_id', $studentClasses);
            })
            ->orderBy('id', 'desc')
            ->paginate(20);

        // 4. Pour le filtre en haut de page
        $data['getRecord'] = HomeworkSubmitModel::getRecordStudent(Auth::user()->id)
            ->paginate(20);
        $data['academicYears'] = \App\Models\AcademicYear::orderBy('start_date', 'desc')->get();
        $data['selectedAcademicYear'] = \App\Models\AcademicYear::find($academicYearId);

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
