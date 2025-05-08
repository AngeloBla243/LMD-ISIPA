<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Models\ThesisSubmissio;

class AcademicYearController extends Controller
{
    public function index()
    {
        $years = AcademicYear::orderBy('start_date', 'desc')->get();
        return view('admin.academic-years.index', compact('years'));
    }


    public function create()
    {
        return view('admin.academic-years.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:academic_years',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        AcademicYear::create($request->all());

        return redirect()->route('admin.academic-years.index')
            ->with('success', 'Année académique créée.');
    }

    public function edit(AcademicYear $academicYear)
    {
        return view('admin.academic-years.edit', compact('academicYear'));
    }


    public function update(Request $request, AcademicYear $academicYear)
    {
        $request->validate([
            'name' => 'required|string|unique:academic_years,name,' . $academicYear->id,
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'nullable' // présent seulement si switch ou select
        ]);

        // Si activé, désactive les autres
        if ($request->is_active) {
            AcademicYear::where('id', '!=', $academicYear->id)->update(['is_active' => false]);
            $academicYear->is_active = true;
        } else {
            $academicYear->is_active = false;
        }

        $academicYear->name = $request->name;
        $academicYear->start_date = $request->start_date;
        $academicYear->end_date = $request->end_date;
        $academicYear->save();

        return redirect()->route('admin.academic-years.index')
            ->with('success', 'Année académique mise à jour.');
    }


    public function setActive(AcademicYear $academicYear)
    {
        AcademicYear::query()->update(['is_active' => false]); // Désactiver toutes les autres années
        $academicYear->update(['is_active' => true]);

        return back()->with('success', 'Année académique active définie.');
    }

    public function destroy(AcademicYear $academicYear)
    {
        $academicYear->delete();
        return back()->with('success', 'Année académique supprimée.');
    }
}
