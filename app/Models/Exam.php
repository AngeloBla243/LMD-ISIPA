<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



// Exam.php
class Exam extends Model
{
    use HasFactory;


    protected $fillable = [
        'title',
        'description',
        'question_count',
        'choices_per_question',
        'duration_minutes',
        'start_time',
        'end_time',
        'class_id',
        'subject_id',
        'teacher_id',
        'academic_year_id'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];


    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class);
    }

    public function subject()
    {
        return $this->belongsTo(SubjectModel::class);
    }
}
