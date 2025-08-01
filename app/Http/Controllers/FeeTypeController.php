<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FeeTypeController extends Controller
{
    public function index()
    {
        $fee_types = \App\Models\FeeType::with('classes')->paginate(10);
        return view('admin.fee_types.index', compact('fee_types'));
    }

    public function create()
    {
        $classes = \App\Models\ClassModel::all();
        return view('admin.fee_types.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'amount' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'class_ids' => 'required|array'
        ]);

        $feeType = \App\Models\FeeType::create($validated);
        $feeType->classes()->sync($validated['class_ids']);
        return redirect()->route('admin.fee_types.index')->with('success', 'Frais créé');
    }

    public function edit($id)
    {
        $fee_type = \App\Models\FeeType::findOrFail($id);
        $classes = \App\Models\ClassModel::all();
        return view('admin.fee_types.edit', compact('fee_type', 'classes'));
    }

    public function update(Request $request, $id)
    {
        $fee_type = \App\Models\FeeType::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string',
            'amount' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'class_ids' => 'required|array'
        ]);
        $fee_type->update($validated);
        $fee_type->classes()->sync($validated['class_ids']);
        return redirect()->route('admin.fee_types.index')->with('success', 'Frais mis à jour');
    }
}
