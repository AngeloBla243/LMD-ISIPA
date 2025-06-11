<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ClassModel;
use App\Exports\ExportStudent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\StudentAttendanceModel;
use App\Models\AcademicYear;

use App\Imports\StudentsImport;

use App\Models\AssignClassTeacherModel;

use Maatwebsite\Excel\Facades\Excel;


class StudentController extends Controller
{
    public function export_excel(Request $request)
    {
        return Excel::download(new ExportStudent, 'Student_' . date('d-m-Y') . '.xls');
    }


    public function import()
    {
        $data['header_title'] = "Importer des étudiants";
        return view('admin.student.import', $data);
    }

    public function importSubmit(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new StudentsImport, $request->file('file'));

        return redirect('admin/student/list')->with('success', 'Étudiants importés avec succès');
    }


    public function list()
    {
        $data['getRecord'] = User::getStudent();
        $data['header_title'] = "Student List";
        return view('admin.student.list', $data);
    }







    public function add()
    {
        $data['getClass'] = ClassModel::getClass();
        $data['header_title'] = "Add New Student";
        return view('admin.student.add', $data);
    }

    public function insert(Request $request)
    {

        request()->validate([
            'email' => 'required|email|unique:users',
            'weight' => 'max:10',
            'blood_group' => 'max:10',
            'mobile_number' => 'max:15|min:8',
            'admission_number' => 'max:50',
            'roll_number' => 'max:50',
            'caste' => 'max:50',
            'religion' => 'max:50',
            'height' => 'max:10'
        ]);


        $student = new User;
        $student->name = trim($request->name);
        $student->departement = trim($request->departement);
        $student->admission_number = trim($request->admission_number);
        $student->roll_number = trim($request->roll_number);
        $student->class_id = trim($request->class_id);
        $student->gender = trim($request->gender);

        if (!empty($request->date_of_birth)) {
            $student->date_of_birth = trim($request->date_of_birth);
        }

        if (!empty($request->file('profile_pic'))) {
            $ext = $request->file('profile_pic')->getClientOriginalExtension();
            $file = $request->file('profile_pic');
            $randomStr = date('Ymdhis') . Str::random(20);
            $filename = strtolower($randomStr) . '.' . $ext;
            $file->move('upload/profile/', $filename);

            $student->profile_pic = $filename;
        }

        $student->caste = trim($request->caste);
        $student->religion = trim($request->religion);
        $student->mobile_number = trim($request->mobile_number);

        if (!empty($request->admission_date)) {
            $student->admission_date = trim($request->admission_date);
        }

        $student->blood_group = trim($request->blood_group);
        $student->height = trim($request->height);
        $student->weight = trim($request->weight);
        $student->status = trim($request->status);
        $student->email = trim($request->email);
        $student->password = Hash::make($request->password);
        $student->user_type = 3;
        $student->save();

        return redirect('admin/student/list')->with('success', "Student Successfully Created");
    }

    public function edit($id)
    {
        // Récupère l'étudiant avec ses classes et années académiques
        $student = User::with(['studentClasses' => function ($query) {
            $query->withPivot('academic_year_id');
        }])->findOrFail($id);

        $data = [
            'getRecord' => $student,
            'getClass' => ClassModel::where('is_delete', 0)
                ->where('status', 0)
                ->get(),
            'academicYears' => AcademicYear::orderBy('start_date', 'desc')->get(),
            'header_title' => "Modifier l'étudiant"
        ];

        return view('admin.student.edit', $data);
    }


    public function update($id, Request $request)
    {
        request()->validate([
            'email' => 'required|email|unique:users,email,' . $id,
            'weight' => 'max:10',
            'blood_group' => 'max:10',
            'mobile_number' => 'max:15|min:8',
            'admission_number' => 'max:50',
            'roll_number' => 'max:50',
            'class_ids.*' => 'exists:class,id',
            'caste' => 'max:50',
            'religion' => 'max:50',
            'height' => 'max:10'
        ]);


        $student = User::getSingle($id);;
        $student->name = trim($request->name);
        $student->last_name = trim($request->last_name);
        $student->departement = trim($request->departement);
        $student->admission_number = trim($request->admission_number);
        $student->roll_number = trim($request->roll_number);
        $student->class_id = trim($request->class_id);
        $student->gender = trim($request->gender);

        if (!empty($request->date_of_birth)) {
            $student->date_of_birth = trim($request->date_of_birth);
        }

        if (!empty($request->file('profile_pic'))) {
            if (!empty($student->getProfile())) {
                unlink('upload/profile/' . $student->profile_pic);
            }

            $ext = $request->file('profile_pic')->getClientOriginalExtension();
            $file = $request->file('profile_pic');
            $randomStr = date('Ymdhis') . Str::random(20);
            $filename = strtolower($randomStr) . '.' . $ext;
            $file->move('upload/profile/', $filename);
            $student->profile_pic = $filename;
        }

        $student->caste = trim($request->caste);
        $student->religion = trim($request->religion);
        $student->mobile_number = trim($request->mobile_number);

        if (!empty($request->admission_date)) {
            $student->admission_date = trim($request->admission_date);
        }

        $student->blood_group = trim($request->blood_group);
        $student->height = trim($request->height);
        $student->weight = trim($request->weight);
        $student->status = trim($request->status);
        $student->email = trim($request->email);

        if (!empty($request->password)) {
            $student->password = Hash::make($request->password);
        }

        $student->studentClasses()->detach(); // Supprimer les anciennes entrées
        foreach ($request->class_ids as $index => $classId) {
            $student->studentClasses()->attach($classId, [
                'academic_year_id' => $request->academic_year_ids[$index]
            ]);
        }

        $student->save();

        return redirect('admin/student/list')->with('success', "Student Successfully Updated");
    }

    public function delete($id)
    {
        $getRecord = User::getSingle($id);
        if (!empty($getRecord)) {
            $getRecord->is_delete = 1;
            $getRecord->save();

            return redirect()->back()->with('success', "Student Successfully Deleted");
        } else {
            abort(404);
        }
    }


    // teacher side work

    // public function MyStudent()
    // {
    //     $students = User::getTeacherStudent(Auth::user()->id);

    //     foreach ($students as $student) {
    //         $attendanceRecords = studentAttendanceModel::getRecordStudent($student->id);
    //         $totalDays = $attendanceRecords->count();

    //         if ($totalDays > 0) {
    //             $presentDays = $attendanceRecords->where('attendance_type', 1)->count();
    //             $absentDays = $attendanceRecords->where('attendance_type', 3)->count();
    //             $presentPercentage = ($presentDays / $totalDays) * 100;
    //         } else {
    //             $presentPercentage = 0;
    //         }

    //         // Ajouter les taux de présence pour chaque étudiant
    //         $student->attendanceRate = $presentPercentage;
    //     }

    //     $data['getRecord'] = $students;
    //     $data['header_title'] = "My Student List";
    //     return view('teacher.my_student', $data);
    // }

    public function MyStudent()
    {
        // 1. Récupérer l'année académique sélectionnée
        $academicYearId = session('academic_year_id', AcademicYear::where('is_active', 1)->value('id'));

        // 2. Récupérer les classes assignées au professeur pour cette année
        $teacherClassIds = AssignClassTeacherModel::where('teacher_id', Auth::id())
            ->whereHas('class', function ($q) use ($academicYearId) {
                $q->where('academic_year_id', $academicYearId);
            })
            ->pluck('class_id')
            ->toArray();

        if (empty($teacherClassIds)) {
            return view('teacher.my_student', [
                'getRecord' => collect(),
                'academicYears' => AcademicYear::all(),
                'selectedAcademicYear' => AcademicYear::find($academicYearId),
                'header_title' => "Mes étudiants"
            ])->with('error', 'Aucune classe assignée pour cette année académique');
        }

        // 3. Récupérer les étudiants de ces classes pour cette année
        $students = User::select('users.*', 'class.name as class_name', 'class.opt as class_opt')
            ->join('student_class', 'student_class.student_id', '=', 'users.id')
            ->join('class', 'class.id', '=', 'student_class.class_id')
            ->whereIn('student_class.class_id', $teacherClassIds)
            ->where('student_class.academic_year_id', $academicYearId)
            ->where('users.user_type', 3)
            ->where('users.is_delete', 0)
            ->groupBy('users.id', 'student_class.class_id')
            ->paginate(20);


        // 4. Calculer les taux de présence par classe/année
        foreach ($students as $student) {
            $attendanceRecords = StudentAttendanceModel::getRecordStudent(
                $student->id,
                $academicYearId,
                ['class_id' => $student->class_id] // Filtre par classe
            );

            $totalDays = $attendanceRecords->count();
            $student->attendanceRate = $totalDays > 0
                ? ($attendanceRecords->where('attendance_type', 1)->count() / $totalDays) * 100
                : 0;
        }

        // 5. Préparer les données pour la vue
        $data = [
            'getRecord' => $students,
            'academicYears' => AcademicYear::orderBy('start_date', 'desc')->get(),
            'selectedAcademicYear' => AcademicYear::find($academicYearId),
            'header_title' => "Mes étudiants (" . AcademicYear::find($academicYearId)->name . ")"
        ];

        return view('teacher.my_student', $data);
    }
}
