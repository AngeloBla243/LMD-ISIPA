<?php

namespace App\Http\Controllers;

use App\Models\FeatureToggle;
use Illuminate\Http\Request;

class FeaturesController extends Controller
{
    public function index()
    {
        $features = FeatureToggle::paginate(10); // pagination
        return view('admin.features.index', compact('features'));
    }

    public function create()
    {
        return view('admin.features.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'feature_name' => 'required|string|max:255',
            'enabled' => 'required|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        FeatureToggle::create($data);

        return redirect()->route('admin.features.index')
            ->with('success', 'Nouvelle fonctionnalité ajoutée avec succès ✅');
    }

    public function edit($id)
    {
        $feature = FeatureToggle::findOrFail($id);
        return view('admin.features.edit', compact('feature'));
    }

    public function update(Request $request, $id)
    {
        $feature = FeatureToggle::findOrFail($id);

        $data = $request->validate([
            'enabled' => 'required|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $feature->update($data);

        return redirect()->route('admin.features.index')
            ->with('success', 'Mise à jour effectuée avec succès ✅');
    }
}
