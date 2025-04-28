<?php

namespace App\Http\Controllers;

use App\Services\PdfToText;
use App\Services\PlagiarismChecker;
use App\Models\ThesisSubmissio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\ClassModel;
use App\Models\ClassSubjectModel;
use Smalot\PdfParser\Parser;
use PhpOffice\PhpWord\IOFactory;
use App\Models\SettingModel;
use Barryvdh\DomPDF\Facade\Pdf; // Ajout de use PDF; (si tu utilises dompdf)

class ThesisController extends Controller
{
    public function create()
    {
        return view('student.thesis');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string',
            'thesis_file' => 'required|file|mimes:pdf,docx|max:2048'
        ]);

        $file = $request->file('thesis_file');
        $fileExtension = $file->getClientOriginalExtension();

        try {
            $text = '';
            if ($fileExtension === 'pdf') {
                // Extraction du texte depuis un PDF
                $pdfToText = new PdfToText();
                $text = $pdfToText->setPdf($file)->text();
            } elseif ($fileExtension === 'docx') {
                // Extraction du texte depuis un DOCX
                $phpWord = IOFactory::load($file->path());
                foreach ($phpWord->getSections() as $section) {
                    foreach ($section->getElements() as $element) {
                        if (method_exists($element, 'getText')) {
                            $text .= $element->getText() . "\n";
                        }
                    }
                }
            } else {
                return back()->withErrors(['thesis_file' => 'Type de fichier non supporté.']);
            }

            // Vérification du plagiat
            $checker = new PlagiarismChecker();
            $results = $checker->check($text);

            // Calcul du taux maximal
            $maxRate = collect($results)->max('similarity') ?? 0;

            // Enregistrement
            $submission = ThesisSubmissio::create([
                'student_id' => Auth::id(),
                'subject' => $validated['subject'],
                'content' => $text,
                'content_hash' => Hash::make($text),
                'plagiarism_results' => json_encode($results),
                'plagiarism_rate' => $maxRate,
                'file_extension' => $fileExtension,
            ]);

            // Stockage du fichier
            $path = $file->storeAs('theses', $submission->id . '.' . $fileExtension);

            return redirect()->route('thesis.result', $submission->id);
        } catch (\Exception $e) {
            return back()->withErrors(['thesis_file' => 'Impossible de lire ce fichier. Erreur: ' . $e->getMessage()]);
        }
    }

    public function result($id)
    {
        $submission = ThesisSubmissio::findOrFail($id);
        return view('student.result', compact('submission'));
    }

    public function downloadReport(Request $request, $id)
    {
        $submission = ThesisSubmissio::findOrFail($id);

        if ($submission->plagiarism_rate < 20) {
            // Récupérer l'étudiant connecté
            $student = Auth::user();

            // Charger la classe liée via class_id
            $class = ClassModel::find($student->class_id);

            // L'option est dans la table class dans la colonne 'opt' (tu peux adapter selon ta table)
            $opt = $class ? $class->opt : '';

            // Récupérer les settings (logo etc.)
            $getSetting = SettingModel::getSingle();


            $data = [
                'getSetting' => $getSetting,
                'class'           => $class,
                'opt'             => $opt,
                'nom'             => $student->name . ' ' . ($student->last_name ?? ''),
                'plagiarism_rate' => $submission->plagiarism_rate,
                'date'            => now()->format('d/m/Y'),
            ];

            $pdf = Pdf::loadView('autorisation_depot', $data);

            return $pdf->download('autorisation_depot.pdf');
        } else {
            return redirect()->route('thesis.result', $submission->id)
                ->withErrors(['message' => 'Le taux de plagiat est trop élevé.']);
        }
    }
}
