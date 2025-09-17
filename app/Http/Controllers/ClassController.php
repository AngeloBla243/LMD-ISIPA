<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ClassModel;

use App\Models\AcademicYear;
use App\Models\Department;

class ClassController extends Controller
{

    public function list()
    {
        $data['getRecord'] = ClassModel::select(
            'class.*',
            'academic_years.name as academic_year_name',
            'departments.name as department_name', // ajouter ce champ
            'users.name as created_by_name'
        )
            ->join('academic_years', 'academic_years.id', '=', 'class.academic_year_id')
            ->leftJoin('departments', 'departments.id', '=', 'class.department_id') // jointure facultative
            ->join('users', 'users.id', '=', 'class.created_by')
            ->where('class.is_delete', 0)
            ->paginate(20);

        $data['header_title'] = "Class List";
        return view('admin.class.list', $data);
    }



    public function add()
    {
        $data['header_title'] = "Add New Class";
        $data['academicYears'] = AcademicYear::orderBy('name', 'desc')->get();
        $data['departments'] = Department::all(); // ajout
        return view('admin.class.add', $data);
    }

    public function insert(Request $request)
    {
        $save = new ClassModel;
        $save->name = $request->name;
        $save->opt = $request->opt;
        $save->amount = $request->amount;
        $save->academic_year_id = $request->academic_year_id;
        $save->department_id = $request->department_id;
        $save->status = $request->status;
        $save->created_by = Auth::user()->id;
        $save->save();

        return redirect('admin/class/list')->with('success', "Class Successfully Created");
    }

    public function edit($id)
    {
        $data['academicYears'] = AcademicYear::orderBy('name', 'desc')->get();
        $data['departments'] = Department::all(); // ajout
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
        $save->department_id = $request->department_id; // ajout
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

    public function getClassesByDepartment($departmentId)
    {
        $classes = ClassModel::where('department_id', $departmentId)
            ->where('status', 0)       // selon vos critères de classes actives
            ->where('is_delete', 0)    // exclus classes supprimées
            ->get(['id', 'name', 'opt']);

        return response()->json($classes);
    }

    public function getClassesByDepartmentAndYear($departmentId, $yearId)
    {
        if (auth()->user()->role === 'departement') {
            $departmentId = auth()->user()->department_id; // sécurité
        }

        $classes = ClassModel::where('department_id', $departmentId)
            ->where('academic_year_id', $yearId)
            ->where('status', 0)
            ->where('is_delete', 0)
            ->get(['id', 'name', 'opt']);

        return response()->json($classes);  // AJOUTER CE RETOUR JSON
    }
}
