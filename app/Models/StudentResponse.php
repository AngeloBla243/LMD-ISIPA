<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'exam_id',
        'question_id',
        'choice_id',
        'text_response',
        'score',
        'started_at',
        'submitted_at'
    ];
}
