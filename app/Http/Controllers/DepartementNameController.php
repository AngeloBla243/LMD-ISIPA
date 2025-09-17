<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;

class DepartementNameController extends Controller
{
    public function list()
    {
        $data['departments'] = Department::paginate(20);
        $data['header_title'] = "Liste des départements";
        return view('admin.departementName.list', $data);
    }

    public function add()
    {
        $data['header_title'] = "Ajouter un département";
        return view('admin.departementName.add', $data);
    }

    public function insert(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:departments'
        ]);
        Department::create(['name' => $request->name]);
        return redirect()->route('admin.departementName.list')->with('success', 'Département ajouté');
    }

    public function edit($id)
    {
        $data['department'] = Department::findOrFail($id);
        $data['header_title'] = "Modifier un département";
        return view('admin.departementName.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|unique:departments,name,' . $id,
        ]);
        $department = Department::findOrFail($id);
        $department->update(['name' => $request->name]);
        return redirect()->route('admin.departementName.list')->with('success', 'Département modifié');
    }

    public function delete($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();
        return redirect()->route('admin.departementName.list')->with('success', 'Département supprimé');
    }
}
