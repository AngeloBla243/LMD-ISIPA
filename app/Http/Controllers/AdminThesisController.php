<?php

namespace App\Http\Controllers;

use App\Models\ThesisSubmissio;
use App\Models\ThesisSubmissionSetting;
use Illuminate\Support\Facades\Storage; // Ajout de l'import pour Storage
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class AdminThesisController extends Controller
{
    // Liste des mémoires soumis
    // public function index()
    // {
    //     $submissions = ThesisSubmissio::with('student')->orderBy('created_at', 'desc')->paginate(20);
    //     return view('admin.theses.index', compact('submissions'));
    // }

    public function index(Request $request)
    {
        $query = ThesisSubmissio::with(['student.class']);

        // Filtres de recherche
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('student', function ($qs) use ($search) {
                    $qs->where('name', 'like', "%$search%")
                        ->orWhere('last_name', 'like', "%$search%");
                })
                    ->orWhereHas('student.class', function ($qc) use ($search) {
                        $qc->where('name', 'like', "%$search%");
                    })
                    ->orWhere('subject', 'like', "%$search%");
            });
        }

        // Numérotation par ordre de dépôt (plus récent = 1)
        $submissions = $query->orderBy('created_at', 'asc')->paginate(20);

        return view('admin.theses.index', compact('submissions'));
    }


    // Détail d'une soumission
    public function show($id)
    {
        $submission = ThesisSubmissio::with('student')->findOrFail($id);
        return view('admin.theses.show', compact('submission'));
    }

    // AdminThesisController.php
    // public function show($id)
    // {
    //     $submission = ThesisSubmissio::with(['student', 'documents'])->findOrFail($id);
    //     return view('admin.theses.show', compact('submission'));
    // }


    // Exemple : mise à jour du statut ou commentaire admin
    // public function update(Request $request, $id)
    // {
    //     $submission = ThesisSubmissio::findOrFail($id);
    //     $request->validate([
    //         'status' => 'required|in:accepted,rejected,pending',
    //         'admin_comment' => 'nullable|string|max:1000',
    //     ]);
    //     $submission->status = $request->status;
    //     $submission->admin_comment = $request->admin_comment;
    //     $submission->save();

    //     return redirect()->route('admin.theses.show', $id)->with('success', 'Mise à jour enregistrée.');
    // }

    // public function update(Request $request, $id)
    // {
    //     $submission = ThesisSubmissio::findOrFail($id);
    //     $request->validate([
    //         'status' => 'required|in:accepted,rejected,pending',
    //         // plus besoin de 'admin_comment' si tu ne veux plus de commentaire
    //         'subject' => 'required|string|max:255',
    //     ]);
    //     $submission->status = $request->status;
    //     $submission->subject = $request->subject;
    //     $submission->save();

    //     // PAS besoin d'ajouter ici la création Document : c'est géré dans le booted() du modèle

    //     return redirect()->route('admin.theses.show', $id)->with('success', 'Mise à jour enregistrée.');
    // }
    public function update(Request $request, $id)
    {
        $submission = ThesisSubmissio::findOrFail($id);

        $request->validate([
            'subject' => 'required|string|max:255',
            'status' => 'required|in:pending,accepted,rejected',
        ]);

        $submission->subject = $request->subject;
        $submission->status = $request->status;
        $submission->save();

        return redirect()->route('admin.theses.show', $id)
            ->with('success', 'Modifications enregistrées avec succès !');
    }




    // public function downloadReport($id)
    // {
    //     $submission = ThesisSubmissio::findOrFail($id);

    //     // On récupère le contenu texte
    //     $content = $submission->content ?: 'Mémoire non disponible';
    //     $filename = str_replace(' ', '_', $submission->subject) . '.txt';

    //     return response($content)
    //         ->header('Content-Type', 'text/plain; charset=UTF-8')
    //         ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    // }





    public function destroy($id)
    {
        $submission = ThesisSubmissio::findOrFail($id);
        $submission->delete();
        return back()->with('success', 'Soumission supprimée.');
    }
}
