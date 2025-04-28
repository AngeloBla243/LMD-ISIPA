<?php

// app/Services/PdfToText.php
namespace App\Services;

use Smalot\PdfParser\Parser;



class PdfToText
{
    private $pdf;

    public function setPdf($file)
    {
        try {
            $this->pdf = (new Parser())->parseFile($file->path());
            return $this;
        } catch (\Exception $e) {
            // Gérer l'erreur (ex: log, message à l'utilisateur)
            throw new \Exception("Impossible de lire ce PDF protégé.");
        }
    }

    public function text(): string
    {
        return $this->pdf->getText();
    }
}
