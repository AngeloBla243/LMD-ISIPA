<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThesisSubmissio extends Model
{
    use HasFactory;

    protected $table = 'thesis1_submissions';

    protected $fillable = [
        'student_id',
        'content',
        'content_hash',
        'subject',
        'pdf_path',
        'plagiarism_rate',
        'plagiarism_results',
        'plagiarism_rate',
        'annotations'
    ];

    public function student()
    {
        return $this->belongsTo(User::class);
    }
}
