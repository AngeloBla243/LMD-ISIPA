<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ApparitoratController extends Controller
{
    public function list()
    {
        $data['getRecord'] = User::where('user_type', 7)->where('is_delete', 0)->paginate(20);
        $data['header_title'] = "Liste des apparitorat";
        return view('admin.apparitorat.list', $data);
    }

    public function add()
    {
        $data['header_title'] = "Ajouter un apparitorat";
        return view('admin.apparitorat.add', $data);
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
        $user->user_type = 7; // departement
        if ($request->hasFile('profile_pic')) {
            $ext = $request->file('profile_pic')->getClientOriginalExtension();
            $file = $request->file('profile_pic');
            $filename = strtolower(date('YmdHis') . Str::random(10)) . '.' . $ext;
            $file->move('upload/profile/', $filename);
            $user->profile_pic = $filename;
        }
        $user->save();

        return redirect()->route('admin.apparitorat.list')->with('success', "apparitorat ajouté avec succès");
    }

    public function edit($id)
    {
        $data['getRecord'] = User::where('id', $id)->where('user_type', 7)->firstOrFail();
        $data['header_title'] = "Modifier apparitorat";
        return view('admin.apparitorat.edit', $data);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email,' . $id,
            'name' => 'required|string|max:255',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user = User::where('id', $id)->where('user_type', 7)->firstOrFail();
        $user->name = trim($request->name);
        $user->email = trim($request->email);
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

        return redirect()->route('admin.apparitorat.list')->with('success', "apparitorat mis à jour avec succès");
    }

    public function delete($id)
    {
        $user = User::where('id', $id)->where('user_type', 7)->firstOrFail();
        $user->is_delete = 1;
        $user->save();

        return redirect()->route('admin.apparitorat.list')->with('success', "apparitorat supprimé avec succès");
    }
}
