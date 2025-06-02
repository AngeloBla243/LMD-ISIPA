<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Exports\ExportTeacher;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\AcademicYear;
use Barryvdh\DomPDF\Facade\PDF;
use App\Models\ThesisSubmissio;
use Maatwebsite\Excel\Facades\Excel;

class TeacherController extends Controller
{

    public function export_excel(Request $request)
    {
        return Excel::download(new ExportTeacher, 'Teacher_' . date('d-m-Y') . '.xls');
    }

    public function list()
    {
        $data['getRecord'] = User::getTeacher();
        $data['header_title'] = "Teacher List";
        return view('admin.teacher.list', $data);
    }



    public function add()
    {
        $data['header_title'] = "Add New Teacher";
        return view('admin.teacher.add', $data);
    }

    public function insert(Request $request)
    {
        request()->validate([
            'email' => 'required|email|unique:users',
            'mobile_number' => 'max:15|min:8',
            'marital_status' => 'max:50',
        ]);


        $teacher = new User;
        $teacher->name = trim($request->name);
        $teacher->last_name = trim($request->last_name);
        $teacher->gender = trim($request->gender);

        if (!empty($request->date_of_birth)) {
            $teacher->date_of_birth = trim($request->date_of_birth);
        }

        if (!empty($request->admission_date)) {
            $teacher->admission_date = trim($request->admission_date);
        }

        if (!empty($request->file('profile_pic'))) {
            $ext = $request->file('profile_pic')->getClientOriginalExtension();
            $file = $request->file('profile_pic');
            $randomStr = date('Ymdhis') . Str::random(20);
            $filename = strtolower($randomStr) . '.' . $ext;
            $file->move('upload/profile/', $filename);

            $teacher->profile_pic = $filename;
        }

        $teacher->marital_status = trim($request->marital_status);
        $teacher->address = trim($request->address);
        $teacher->mobile_number = trim($request->mobile_number);
        $teacher->permanent_address = trim($request->permanent_address);
        $teacher->qualification = trim($request->qualification);
        $teacher->work_experience = trim($request->work_experience);
        $teacher->note = trim($request->note);
        $teacher->status = trim($request->status);
        $teacher->email = trim($request->email);
        $teacher->password = Hash::make($request->password);
        $teacher->user_type = 2;
        $teacher->save();

        return redirect('admin/teacher/list')->with('success', "Teacher Successfully Created");
    }

    public function edit($id)
    {
        $data['getRecord'] = User::getSingle($id);
        if (!empty($data['getRecord'])) {
            $data['header_title'] = "Edit Teacher";
            return view('admin.teacher.edit', $data);
        } else {
            abort(404);
        }
    }

    public function update($id, Request $request)
    {
        request()->validate([
            'email' => 'required|email|unique:users,email,' . $id,
            'mobile_number' => 'max:15|min:8',
            'marital_status' => 'max:50',
        ]);


        $teacher = User::getSingle($id);
        $teacher->name = trim($request->name);
        $teacher->last_name = trim($request->last_name);
        $teacher->gender = trim($request->gender);

        if (!empty($request->date_of_birth)) {
            $teacher->date_of_birth = trim($request->date_of_birth);
        }

        if (!empty($request->admission_date)) {
            $teacher->admission_date = trim($request->admission_date);
        }

        if (!empty($request->file('profile_pic'))) {
            $ext = $request->file('profile_pic')->getClientOriginalExtension();
            $file = $request->file('profile_pic');
            $randomStr = date('Ymdhis') . Str::random(20);
            $filename = strtolower($randomStr) . '.' . $ext;
            $file->move('upload/profile/', $filename);

            $teacher->profile_pic = $filename;
        }

        $teacher->marital_status = trim($request->marital_status);
        $teacher->address = trim($request->address);
        $teacher->mobile_number = trim($request->mobile_number);
        $teacher->permanent_address = trim($request->permanent_address);
        $teacher->qualification = trim($request->qualification);
        $teacher->work_experience = trim($request->work_experience);
        $teacher->note = trim($request->note);
        $teacher->status = trim($request->status);
        $teacher->email = trim($request->email);
        if (!empty($request->password)) {
            $teacher->password = Hash::make($request->password);
        }

        $teacher->save();

        return redirect('admin/teacher/list')->with('success', "Teacher Successfully Updated");
    }

    public function delete($id)
    {
        $getRecord = User::getSingle($id);
        if (!empty($getRecord)) {
            $getRecord->is_delete = 1;
            $getRecord->save();

            return redirect()->back()->with('success', "Teacher Successfully Deleted");
        } else {
            abort(404);
        }
    }


    // public function myStudents()
    // {
    //     $teacher = Auth::user();

    //     $submissions = ThesisSubmissio::with(['student.classes', 'academicYear'])
    //         ->where(function ($query) use ($teacher) {
    //             $query->where('encadreur_id', $teacher->id)
    //                 ->orWhere('directeur_id', $teacher->id);
    //         })
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     return view('teacher.encadres', compact('submissions'));
    // }

    public function myStudents()
    {
        $teacher = Auth::user();
        $allAcademicYears = AcademicYear::orderBy('start_date', 'desc')->get();

        // Récupération de l'année académique active ou celle sélectionnée
        $academicYearId = session('academic_year_id', $allAcademicYears->where('is_active', 1)->first()?->id);
        $academicYear = AcademicYear::find($academicYearId);

        $submissions = ThesisSubmissio::with(['student.classes', 'academicYear'])
            ->where(function ($query) use ($teacher) {
                $query->where('encadreur_id', $teacher->id)
                    ->orWhere('directeur_id', $teacher->id);
            })
            ->where('academic_year_id', $academicYear->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('teacher.encadres', compact('submissions', 'academicYear', 'allAcademicYears'));
    }

    public function exportEncadresPDF()
    {
        $teacher = Auth::user();

        $submissions = ThesisSubmissio::with(['student.classes', 'academicYear'])
            ->where(function ($query) use ($teacher) {
                $query->where('encadreur_id', $teacher->id)
                    ->orWhere('directeur_id', $teacher->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = PDF::loadView('teacher.encadres-pdf', compact('submissions'));
        return $pdf->download('mes_encadres_' . now()->format('Ymd') . '.pdf');
    }
}
