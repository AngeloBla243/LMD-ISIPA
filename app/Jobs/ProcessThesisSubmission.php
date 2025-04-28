<?php

namespace App\Jobs;

use App\Services\PdfToText;
use App\Services\PlagiarismChecker;
use App\Models\ThesisSubmissio;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpWord\IOFactory;

class ProcessThesisSubmission implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $submissionId;
    protected $file;
    protected $validated;

    public function __construct($submissionId, $file, $validated)
    {
        $this->submissionId = $submissionId;
        $this->file = $file;
        $this->validated = $validated;
    }

    public function handle()
    {
        $fileExtension = $this->file->getClientOriginalExtension();

        try {
            $text = '';
            if ($fileExtension === 'pdf') {
                // Extraction du texte depuis un PDF
                $pdfToText = new PdfToText();
                $text = $pdfToText->setPdf($this->file)->text();
            } elseif ($fileExtension === 'docx') {
                // Extraction du texte depuis un DOCX
                $phpWord = IOFactory::load($this->file->path());
                foreach ($phpWord->getSections() as $section) {
                    foreach ($section->getElements() as $element) {
                        if (method_exists($element, 'getText')) {
                            $text .= $element->getText() . "\n";
                        }
                    }
                }
            }

            // Vérification du plagiat
            $checker = new PlagiarismChecker();
            $results = $checker->check($text);

            // Calcul du taux maximal
            $maxRate = collect($results)->max('similarity') ?? 0;

            // Mise à jour de la soumission
            $submission = ThesisSubmissio::findOrFail($this->submissionId);
            $submission->content = $text;
            $submission->content_hash = Hash::make($text);
            $submission->plagiarism_results = json_encode($results);
            $submission->plagiarism_rate = $maxRate;
            $submission->save();
        } catch (\Exception $e) {
            // Gérer l'erreur (journalisation, notification, etc.)
            \Log::error('Erreur lors du traitement de la soumission : ' . $e->getMessage());
        }
    }
}
