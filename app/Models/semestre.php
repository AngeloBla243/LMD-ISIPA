<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class semestre extends Model
{
    use HasFactory;
    protected $table = 'semesters';
    protected $fillable = [
        'name',          // Ajouté
        'academic_year_id' // Ajouté
    ];
    public function exams()
    {
        return $this->hasMany(ExamModel::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
