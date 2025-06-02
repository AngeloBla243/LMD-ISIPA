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
use App\Models\AcademicYear;
use Smalot\PdfParser\Parser;
use PhpOffice\PhpWord\IOFactory;
use App\Models\SettingModel;
use Barryvdh\DomPDF\Facade\Pdf; // Ajout de use PDF; (si tu utilises dompdf)

class ThesisController extends Controller
{
    public function create()
    {
        // Récupération des professeurs qualifiés
        $professeurs = User::where('qualification', 'Prof')->get();
        $encadreurs = User::whereIn('qualification', ['Prof', 'Ct'])->get();

        return view('student.thesis', compact('professeurs', 'encadreurs'));
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
    //             // Extraction du texte depuis un PDF (adapte le service selon ton projet)
    //             $pdfToText = new PdfToText();
    //             $text = $pdfToText->setPdf($file)->text();
    //         } elseif ($fileExtension === 'docx') {
    //             // Extraction du texte depuis un DOCX - VERSION CORRIGÉE
    //             $phpWord = IOFactory::load($file->path());
    //             foreach ($phpWord->getSections() as $section) {
    //                 foreach ($section->getElements() as $element) {
    //                     if ($element instanceof Text) {
    //                         $text .= $element->getText() . "\n";
    //                     } elseif ($element instanceof TextRun) {
    //                         foreach ($element->getElements() as $childElement) {
    //                             if ($childElement instanceof Text) {
    //                                 $text .= $childElement->getText();
    //                             }
    //                         }
    //                         $text .= "\n";
    //                     }
    //                 }
    //             }
    //         } else {
    //             return back()->withErrors(['thesis_file' => 'Type de fichier non supporté.']);
    //         }

    //         // Vérification du plagiat
    //         $checker = new PlagiarismChecker();
    //         $similarSentences = $checker->findSimilarSentences($text);

    //         // Calcul du taux de plagiat : 0,5% par phrase similaire (max 100%)
    //         $percentagePerMatch = 0.5;
    //         $totalRate = count($similarSentences) * $percentagePerMatch;
    //         $plagiarismRate = min($totalRate, 100);

    //         // Création de la soumission dans la base
    //         $submission = ThesisSubmissio::create([
    //             'student_id'          => Auth::id(),
    //             'subject'             => $validated['subject'],
    //             'content'             => $text,
    //             'content_hash'        => hash('sha256', $text),
    //             'plagiarism_results'  => json_encode($similarSentences),
    //             'plagiarism_rate'     => $plagiarismRate,
    //             'file_extension'      => $fileExtension,
    //         ]);

    //         // Stockage du fichier uploadé
    //         $path = $file->storeAs('theses', $submission->id . '.' . $fileExtension);

    //         return redirect()->route('thesis.result', $submission->id);
    //     } catch (\Exception $e) {
    //         return back()->withErrors(['thesis_file' => 'Impossible de lire ce fichier. Erreur: ' . $e->getMessage()]);
    //     }
    // }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:1,2',
            'subject' => 'required_if:type,1|string|max:255|nullable',
            'project_name' => 'required_if:type,2|string|max:255|nullable',
            'directeur_id' => 'required_if:type,1|exists:users,id|nullable',
            'encadreur_id' => 'required_if:type,2|exists:users,id|nullable',
            'thesis_file' => 'required|file|mimes:pdf,docx|max:5120'
        ], [
            'project_name.required_if' => 'Le nom du projet est obligatoire pour les projets.',
            'project_name.string' => 'Le nom du projet doit être une chaîne de caractères.',
            'encadreur_id.exists' => 'L\'encadreur sélectionné est invalide.'
        ]);


        try {
            // Récupération de la classe et année académique
            $student = Auth::user();
            $academicYear = AcademicYear::where('is_active', true)->first();
            $class = $student->studentClasses()
                ->where('student_class.academic_year_id', $academicYear->id) // Spécifier la table
                ->first();

            // Extraction du texte
            $file = $request->file('thesis_file');
            $fileExtension = strtolower($file->getClientOriginalExtension());
            $text = '';

            if ($fileExtension === 'pdf') {
                $pdf = new PdfToText($file->path());
                $text = $pdf->getText();
            } elseif ($fileExtension === 'docx') {
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
            }

            // Vérification du plagiat
            $checker = new PlagiarismChecker();
            $similarSentences = $checker->findSimilarSentences($text);

            // Calcul du taux
            $totalSentences = count(preg_split('/[.!?]/', $text));
            $plagiarismRate = $totalSentences > 0
                ? (count($similarSentences) / $totalSentences) * 100
                : 0;

            // Création de la soumission
            $submission = ThesisSubmissio::create([
                'type' => $validated['type'],
                'subject' => $validated['subject'] ?? null,
                'project_name' => $validated['project_name'] ?? null,
                'directeur_id' => $validated['directeur_id'] ?? null,
                'encadreur_id' => $validated['encadreur_id'] ?? null,
                'student_id' => $student->id,
                'class_id' => $class->id ?? null,
                'academic_year_id' => $academicYear->id,
                'content' => $text,
                'content_hash' => hash('sha256', $text),
                'plagiarism_results' => json_encode($similarSentences),
                'plagiarism_rate' => min($plagiarismRate, 100),
                'file_extension' => $fileExtension,
            ]);

            // Stockage du fichier
            $filePath = $file->storeAs(
                "theses/{$academicYear->id}/{$class->id}",
                $submission->id . '.' . $fileExtension
            );

            return redirect()->route('thesis.result', $submission->id);
        } catch (\Exception $e) {
            return back()
                ->withErrors(['thesis_file' => 'Erreur de traitement: ' . $e->getMessage()])
                ->withInput();
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


    // public function downloadReport(Request $request, $id)
    // {
    //     $submission = ThesisSubmissio::findOrFail($id);

    //     if ($submission->plagiarism_rate < 20) {
    //         // Récupérer l'étudiant connecté
    //         $student = Auth::user();

    //         // Charger la classe liée via class_id
    //         $class = ClassModel::find($student->class_id);

    //         // L'option est dans la table class dans la colonne 'opt' (tu peux adapter selon ta table)
    //         $opt = $class ? $class->opt : '';

    //         // Récupérer les settings (logo etc.)
    //         $getSetting = SettingModel::getSingle();


    //         $data = [
    //             'getSetting' => $getSetting,
    //             'class'           => $class,
    //             'opt'             => $opt,
    //             'nom'             => $student->name . ' ' . ($student->last_name ?? ''),
    //             'plagiarism_rate' => $submission->plagiarism_rate,
    //             'date'            => now()->format('d/m/Y'),
    //         ];

    //         $pdf = Pdf::loadView('autorisation_depot', $data);

    //         return $pdf->download('autorisation_depot.pdf');
    //     } else {
    //         return redirect()->route('thesis.result', $submission->id)
    //             ->withErrors(['message' => 'Le taux de plagiat est trop élevé.']);
    //     }
    // }

    public function downloadReport(Request $request, $id)
    {
        $submission = ThesisSubmissio::findOrFail($id);

        if ($submission->plagiarism_rate < 20) {
            $student = Auth::user();

            // Récupération de la classe via la table pivot avec l'année académique de la soumission
            $class = $student->studentClas()
                ->wherePivot('academic_year_id', $submission->academic_year_id)
                ->first();

            $opt = $class ? $class->opt : '';
            $getSetting = SettingModel::getSingle();

            $data = [
                'getSetting' => $getSetting,
                'class' => $class,
                'opt' => $opt,
                'nom' => $student->name . ' ' . ($student->last_name ?? ''),
                'plagiarism_rate' => $submission->plagiarism_rate,
                'date' => now()->format('d/m/Y'),
            ];

            $pdf = Pdf::loadView('autorisation_depot', $data);
            return $pdf->download('autorisation_depot.pdf');
        } else {
            return redirect()->route('thesis.result', $submission->id)
                ->withErrors(['message' => 'Le taux de plagiat est trop élevé.']);
        }
    }



    public function mySubmissions()
    {
        $student = Auth::user();
        $allAcademicYears = AcademicYear::orderBy('start_date', 'desc')->get();

        // Récupération de l'année académique active ou celle sélectionnée
        $academicYearId = session('academic_year_id', $allAcademicYears->where('is_active', 1)->first()?->id);
        $academicYear = AcademicYear::find($academicYearId);

        $submissions = ThesisSubmissio::with(['directeur', 'encadreur', 'academicYear'])
            ->where('student_id', $student->id)
            ->where('academic_year_id', $academicYear->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('student.submissions', compact('submissions', 'academicYear', 'allAcademicYears'));
    }
}
