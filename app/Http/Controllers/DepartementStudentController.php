<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ClassModel;
use App\Models\AcademicYear;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DepartementStudentController extends Controller
{
    public function list(Request $request)
    {
        $departementId = Auth::user()->department_id;

        $query = User::query()
            ->where('user_type', 3)
            ->where('is_delete', 0)
            ->where('department_id', $departementId)
            ->leftJoin('departments', 'users.department_id', '=', 'departments.id')
            ->select('users.*', 'departments.name as departement');

        if ($request->filled('name')) {
            $query->where('users.name', 'like', '%' . $request->name . '%');
        }
        // Ajoutez d'autres filtres si nécessaire

        $data['getRecord'] = $query->paginate(20);
        $data['header_title'] = "Liste des étudiants du département";

        return view('departement.student.list', $data);
    }

    // public function edit($id)
    // {
    //     $student = User::with(['studentClasses' => function ($query) {
    //         $query->withPivot('academic_year_id');
    //     }])->findOrFail($id);

    //     $departementId = Auth::user()->department_id;

    //     $data = [
    //         'getRecord' => $student,
    //         'getClass' => ClassModel::where('is_delete', 0)
    //             ->where('status', 0)
    //             ->where('department_id', $departementId) // classes du département connecté seulement
    //             ->get(),
    //         'academicYears' => AcademicYear::orderBy('start_date', 'desc')->get(),
    //         'departments' => Department::all(),
    //         'header_title' => "Modifier l'étudiant",
    //         'departementId' => $departementId, // transmettre pour JS
    //     ];
    //     return view('departement.student.edit', $data);
    // }

    public function edit($id)
    {
        $student = User::with(['studentClasses' => function ($query) {
            $query->withPivot('academic_year_id');
        }])->findOrFail($id);

        $departementId = Auth::user()->department_id;

        $data = [
            'getRecord' => $student,
            'academicYears' => AcademicYear::orderBy('start_date', 'desc')->get(),
            'departments' => Department::all(),
            'header_title' => "Modifier l'étudiant",
            'departementId' => $departementId,
        ];

        return view('departement.student.edit', $data);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            //'email' => 'required|email|unique:users,email,' . $id, // optionnel selon besoin
            'class_ids.*' => 'exists:class,id',
            'academic_year_ids.*' => 'exists:academic_years,id',
            // autres validations si nécessaire
        ]);

        $student = User::findOrFail($id);

        // Mise à jour uniquement de l’affectation de classes et années académiques
        $student->studentClasses()->detach();

        foreach ($request->class_ids as $index => $classId) {
            $academicYearId = $request->academic_year_ids[$index] ?? null;
            if ($classId && $academicYearId) {
                $student->studentClasses()->attach($classId, ['academic_year_id' => $academicYearId]);
            }
        }

        $student->save();

        return redirect()->route('departement.student.list')->with('success', "Affectations mises à jour avec succès");
    }
}
