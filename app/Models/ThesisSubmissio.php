<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThesisSubmissio extends Model
{
    use HasFactory;

    protected $table = 'thesis1_submissions';
    protected $casts = [
        'plagiarism_results' => 'array',
    ];

    protected $fillable = [
        'type',
        'directeur_id',
        'encadreur_id',
        'class_id',
        'project_name',
        'student_id',
        'content',
        'content_hash',
        'subject',
        'pdf_path',
        'plagiarism_rate',
        'plagiarism_results',
        'plagiarism_rate',
        'annotations',
        'academic_year_id',
    ];

    public function student()
    {
        return $this->belongsTo(User::class);
    }

    public function studen()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    protected static function booted()
    {
        static::updated(function ($submission) {
            // Si statut passe à "accepted" et n'était pas déjà accepté
            if ($submission->status === 'accepted' && $submission->wasChanged('status')) {
                // Vérifier que ce contenu hashé n'existe pas déjà dans documents
                $hash = hash('sha256', $submission->content);
                $exists = Document::where('hash', $hash)->exists();

                if (!$exists) {
                    // On concatène le sujet au début du contenu, car pas de colonne "title"
                    $to_save = "Sujet: " . $submission->subject . "\n\n" . $submission->content;
                    Document::create([
                        'content' => $to_save,
                        'hash' => $hash,
                    ]);
                }
            }
        });
    }



    // ThesisSubmissio.php
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    // Document.php
    public function submission()
    {
        return $this->belongsTo(ThesisSubmissio::class);
    }
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
    public function directeur()
    {
        return $this->belongsTo(User::class, 'directeur_id');
    }

    public function encadreur()
    {
        return $this->belongsTo(User::class, 'encadreur_id');
    }
}
