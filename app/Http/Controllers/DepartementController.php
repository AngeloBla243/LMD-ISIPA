<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Department;

class DepartementController extends Controller
{
    public function list()
    {
        $data['getRecord'] = User::with('department')
            ->where('user_type', 5)
            ->where('is_delete', 0)
            ->paginate(20);

        $data['getRecord'] = User::where('user_type', 5)->where('is_delete', 0)->paginate(20);
        $data['header_title'] = "Liste des Départements";
        return view('admin.departement.list', $data);
    }

    public function add()
    {
        $data['departments'] = Department::all(); // Ajout
        $data['header_title'] = "Ajouter un Département";
        return view('admin.departement.add', $data);
    }

    public function insert(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'name' => 'required|string|max:255',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = new User;
        $user->name = trim($request->name);
        $user->email = trim($request->email);
        $user->password = Hash::make($request->password);
        $user->user_type = 5; // departement
        $user->department_id = $request->department_id;
        if ($request->hasFile('profile_pic')) {
            $ext = $request->file('profile_pic')->getClientOriginalExtension();
            $file = $request->file('profile_pic');
            $filename = strtolower(date('YmdHis') . Str::random(10)) . '.' . $ext;
            $file->move('upload/profile/', $filename);
            $user->profile_pic = $filename;
        }
        $user->save();

        return redirect()->route('admin.departement.list')->with('success', "Département ajouté avec succès");
    }

    public function edit($id)
    {
        $data['getRecord'] = User::where('id', $id)->where('user_type', 5)->firstOrFail();
        $data['departments'] = Department::all(); // Ajout
        $data['header_title'] = "Modifier Département";
        return view('admin.departement.edit', $data);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email,' . $id,
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user = User::where('id', $id)->where('user_type', 5)->firstOrFail();
        $user->name = trim($request->name);
        $user->email = trim($request->email);
        $user->departement = trim($request->departement);
        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }
        if ($request->hasFile('profile_pic')) {
            if (!empty($user->profile_pic) && file_exists(public_path('upload/profile/' . $user->profile_pic))) {
                unlink(public_path('upload/profile/' . $user->profile_pic));
            }
            $ext = $request->file('profile_pic')->getClientOriginalExtension();
            $file = $request->file('profile_pic');
            $filename = strtolower(date('YmdHis') . Str::random(10)) . '.' . $ext;
            $file->move('upload/profile/', $filename);
            $user->profile_pic = $filename;
        }
        $user->save();

        return redirect()->route('admin.departement.list')->with('success', "Département mis à jour avec succès");
    }

    public function delete($id)
    {
        $user = User::where('id', $id)->where('user_type', 5)->firstOrFail();
        $user->is_delete = 1;
        $user->save();

        return redirect()->route('admin.departement.list')->with('success', "Département supprimé avec succès");
    }
}
