<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recours extends Model
{
    use HasFactory;

    protected $table = 'recours';

    // protected $fillable = ['student_id', 'class_id', 'subject_id', 'objet', 'numero', 'session_year', 'academic_year_id'];
    protected $fillable = [
        'numero',
        'student_id',
        'class_id',
        'academic_year_id',
        'subject_id',
        'exam_id',
        'session',
        'objet',
        'session_year'
    ];

    // Relation avec le modèle Student
    public function student()
    {
        return $this->belongsTo(user::class, 'student_id');
    }

    // Relation avec le modèle Class
    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id'); // Assurez-vous que le nom du modèle de la classe est correct
    }
    // Relation avec le modèle Subject
    public function subject()
    {
        return $this->belongsTo(SubjectModel::class, 'subject_id');
    }

    // Méthode pour récupérer tous les recours avec les relations
    public static function getAllRecours()
    {
        return self::with(['student', 'class', 'subject', 'exam'])->get(); // Charge les relations
    }

    public function exam()
    {
        return $this->belongsTo(ExamScheduleModel::class, 'exam_id'); // Assurez-vous que la clé étrangère est correcte
    }
}
