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
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextRun;

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


    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'subject' => 'required|string',
    //         'thesis_file' => 'required|file|mimes:pdf,docx|max:2048',
    //     ]);

    //     $file = $request->file('thesis_file');
    //     $fileExtension = strtolower($file->getClientOriginalExtension());

    //     try {
    //         $text = '';

    //         if ($fileExtension === 'pdf') {
    //             // Extraction du texte depuis un PDF
    //             $pdfToText = new PdfToText();
    //             $text = $pdfToText->setPdf($file)->text();
    //         } elseif ($fileExtension === 'docx') {
    //             // Extraction du texte depuis un DOCX
    //             $phpWord = IOFactory::load($file->path());
    //             foreach ($phpWord->getSections() as $section) {
    //                 foreach ($section->getElements() as $element) {
    //                     if (method_exists($element, 'getText')) {
    //                         $text .= $element->getText() . "\n";
    //                     }
    //                 }
    //             }
    //         } else {
    //             return back()->withErrors(['thesis_file' => 'Type de fichier non supporté.']);
    //         }

    //         // Instancie ton PlagiarismChecker
    //         $checker = new PlagiarismChecker();

    //         // Recherche des phrases similaires détaillées
    //         $similarSentences = $checker->findSimilarSentences($text);

    //         // Calcul du taux de plagiat : 0,5% par phrase similaire
    //         $percentagePerMatch = 0.5;
    //         $totalRate = count($similarSentences) * $percentagePerMatch;
    //         $plagiarismRate = min($totalRate, 100); // Limite à 100%

    //         // Création de la soumission en base
    //         $submission = ThesisSubmissio::create([
    //             'student_id' => Auth::id(),
    //             'subject' => $validated['subject'],
    //             'content' => $text,
    //             'content_hash' => hash('sha256', $text),
    //             'plagiarism_results' => json_encode($similarSentences),
    //             'plagiarism_rate' => $plagiarismRate,
    //             'file_extension' => $fileExtension,
    //         ]);

    //         // Stockage du fichier uploadé sous un nom lié à l'ID de la soumission
    //         $path = $file->storeAs('theses', $submission->id . '.' . $fileExtension);

    //         // Redirection vers la page résultat avec l'ID de la soumission
    //         return redirect()->route('thesis.result', $submission->id);
    //     } catch (\Exception $e) {
    //         return back()->withErrors(['thesis_file' => 'Impossible de lire ce fichier. Erreur: ' . $e->getMessage()]);
    //     }
    // }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string',
            'thesis_file' => 'required|file|mimes:pdf,docx|max:2048',
        ]);

        $file = $request->file('thesis_file');
        $fileExtension = strtolower($file->getClientOriginalExtension());

        try {
            $text = '';

            if ($fileExtension === 'pdf') {
                // Extraction du texte depuis un PDF (adapte le service selon ton projet)
                $pdfToText = new PdfToText();
                $text = $pdfToText->setPdf($file)->text();
            } elseif ($fileExtension === 'docx') {
                // Extraction du texte depuis un DOCX - VERSION CORRIGÉE
                $phpWord = IOFactory::load($file->path());
                foreach ($phpWord->getSections() as $section) {
                    foreach ($section->getElements() as $element) {
                        if ($element instanceof Text) {
                            $text .= $element->getText() . "\n";
                        } elseif ($element instanceof TextRun) {
                            foreach ($element->getElements() as $childElement) {
                                if ($childElement instanceof Text) {
                                    $text .= $childElement->getText();
                                }
                            }
                            $text .= "\n";
                        }
                    }
                }
            } else {
                return back()->withErrors(['thesis_file' => 'Type de fichier non supporté.']);
            }

            // Vérification du plagiat
            $checker = new PlagiarismChecker();
            $similarSentences = $checker->findSimilarSentences($text);

            // Calcul du taux de plagiat : 0,5% par phrase similaire (max 100%)
            $percentagePerMatch = 0.5;
            $totalRate = count($similarSentences) * $percentagePerMatch;
            $plagiarismRate = min($totalRate, 100);

            // Création de la soumission dans la base
            $submission = ThesisSubmissio::create([
                'student_id'          => Auth::id(),
                'subject'             => $validated['subject'],
                'content'             => $text,
                'content_hash'        => hash('sha256', $text),
                'plagiarism_results'  => json_encode($similarSentences),
                'plagiarism_rate'     => $plagiarismRate,
                'file_extension'      => $fileExtension,
            ]);

            // Stockage du fichier uploadé
            $path = $file->storeAs('theses', $submission->id . '.' . $fileExtension);

            return redirect()->route('thesis.result', $submission->id);
        } catch (\Exception $e) {
            return back()->withErrors(['thesis_file' => 'Impossible de lire ce fichier. Erreur: ' . $e->getMessage()]);
        }
    }





    public function result($id)
    {
        $submission = ThesisSubmissio::findOrFail($id);

        // Décoder les résultats JSON en tableau PHP
        $plagiarismResults = json_decode($submission->plagiarism_results, true) ?? [];

        return view('student.result', [
            'submission' => $submission,
            'plagiarismResults' => $plagiarismResults,
        ]);
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
