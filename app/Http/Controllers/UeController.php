<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\UeModel;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class UeController extends Controller
{
    public function list()
    {
        $data['getRecord'] = UeModel::with('academicYear')->get();
        $data['header_title'] = "Gestion des UE";
        return view('admin.ue.list', $data);
    }

    public function add()
    {
        $data['academicYears'] = AcademicYear::orderBy('start_date', 'desc')->get();
        $data['header_title'] = "Ajouter une UE";
        return view('admin.ue.add', $data);
    }

    // public function insert(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'code' => 'required|string|max:50|unique:ue',
    //         'credits' => 'required|integer|min:1',
    //         'academic_year_id' => 'required|exists:academic_years,id'
    //     ]);

    //     UeModel::create($request->all());

    //     return redirect('admin/ue/list')->with('success', 'UE créée avec succès');
    // }

    public function insert(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:ue',
            'credits' => 'required|integer|min:1',
            'min_passing_mark' => 'required|numeric|between:0,20',
            'compensation_threshold' => 'nullable|numeric|between:0,19.99',
            'grade_scale' => 'required|in:LMD,OTHER',
            'academic_year_id' => 'required|exists:academic_years,id'
        ]);

        UeModel::create([
            'name' => $request->name,
            'code' => $request->code,
            'credits' => $request->credits,
            'min_passing_mark' => $request->min_passing_mark,
            'compensation_threshold' => $request->compensation_threshold,
            'grade_scale' => $request->grade_scale,
            'academic_year_id' => $request->academic_year_id
        ]);

        return redirect('admin/ue/list')->with('success', 'UE créée avec succès');
    }


    public function edit($id)
    {
        $data['ue'] = UeModel::findOrFail($id);
        $data['academicYears'] = AcademicYear::orderBy('start_date', 'desc')->get();
        $data['header_title'] = "Modifier l'UE";
        return view('admin.ue.edit', $data);
    }

    // public function update($id, Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'code' => 'required|string|max:50|unique:ue,code,' . $id,
    //         'credits' => 'required|integer|min:1',
    //         'academic_year_id' => 'required|exists:academic_years,id'
    //     ]);

    //     $ue = UeModel::findOrFail($id);
    //     $ue->update($request->all());

    //     return redirect('admin/ue/list')->with('success', 'UE mise à jour');
    // }

    public function update($id, Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:ue,code,' . $id,
            'credits' => 'required|integer|min:1',
            'min_passing_mark' => 'required|numeric|between:0,20',
            'compensation_threshold' => 'nullable|numeric|between:0,19.99',
            'grade_scale' => 'required|in:LMD,OTHER',
            'academic_year_id' => 'required|exists:academic_years,id'
        ]);

        $ue = UeModel::findOrFail($id);
        $ue->update($request->all());

        return redirect('admin/ue/list')->with('success', 'UE mise à jour avec paramètres LMD');
    }


    public function delete($id)
    {
        $ue = UeModel::findOrFail($id);
        $ue->delete();

        return redirect()->back()->with('success', 'UE supprimée');
    }
}
