<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ThesisSubmissio;
use App\Models\ThesisSubmissionSetting;
use Illuminate\Support\Facades\Storage; // Ajout de l'import pour Storage
use Illuminate\Support\Facades\Http;

class AdminThesisController extends Controller
{
    public function index()
    {
        $submissions = ThesisSubmissio::with('student')
            ->filterByClass(request('class_id'))
            ->paginate(10);

        return view('admin.thesis.index', compact('submissions'));
    }

    public function updateSettings(Request $request)
    {
        ThesisSubmissionSetting::updateOrCreate(
            ['class_id' => $request->class_id],
            $request->only('opening_date', 'closing_date')
        );

        return back()->with('success', 'Paramètres mis à jour');
    }
}
