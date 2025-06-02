<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UeModel extends Model
{
    use HasFactory;
    // use SoftDeletes;

    protected $table = 'ue';
    protected $fillable = [
        'name',
        'code',
        'credits',
        'min_passing_mark', // Ajouté
        'compensation_threshold', // Ajouté
        'grade_scale', // Ajouté
        'academic_year_id'
    ];


    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function subjects()
    {
        return $this->hasMany(SubjectModel::class);
    }
}
