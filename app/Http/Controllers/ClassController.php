<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ClassModel;
use App\Models\AcademicYear;

class ClassController extends Controller
{
    // public function list()
    // {
    //     $data['getRecord'] = ClassModel::getRecord();

    //     $data['header_title'] = "Class List";
    //     return view('admin.class.list', $data);
    // }
    public function list()
    {
        $data['getRecord'] = ClassModel::select('class.*', 'academic_years.name as academic_year_name')
            ->join('academic_years', 'academic_years.id', '=', 'class.academic_year_id') // Jointure
            ->where('class.is_delete', 0)
            ->paginate(20);

        $data['header_title'] = "Class List";
        return view('admin.class.list', $data);
    }


    public function add()
    {
        $data['header_title'] = "Add New Class";
        $data['academicYears'] = AcademicYear::orderBy('name', 'desc')->get();
        return view('admin.class.add', $data);
    }

    public function insert(Request $request)
    {
        $save = new ClassModel;
        $save->name = $request->name;
        $save->opt = $request->opt;
        $save->amount = $request->amount;
        $save->academic_year_id = $request->academic_year_id;
        $save->status = $request->status;
        $save->created_by = Auth::user()->id;
        $save->save();

        return redirect('admin/class/list')->with('success', "Class Successfully Created");
    }

    public function edit($id)
    {
        $data['academicYears'] = AcademicYear::orderBy('name', 'desc')->get();
        $data['getRecord'] = ClassModel::getSingle($id);
        if (!empty($data['getRecord'])) {
            $data['header_title'] = "Edit Class";
            return view('admin.class.edit', $data);
        } else {
            abort(404);
        }
    }

    public function update($id, Request $request)
    {
        $save = ClassModel::getSingle($id);
        $save->name = $request->name;
        $save->opt = $request->opt;
        $save->academic_year_id = $request->academic_year_id;
        $save->amount = $request->amount;
        $save->status = $request->status;
        $save->save();

        return redirect('admin/class/list')->with('success', "Class Successfully Updated");
    }

    public function delete($id)
    {
        $save = ClassModel::getSingle($id);
        $save->is_delete = 1;
        $save->save();

        return redirect()->back()->with('success', "Class Successfully Deleted");
    }
}
