<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $table = 'meetings';

    // Champs autorisés à la création en masse
    protected $fillable = [
        'class_id',
        'teacher_id',
        'academic_year_id',  // <-- AJOUTE CE CHAMP
        'zoom_meeting_id',
        'topic',
        'start_time',
        'duration',
        'agenda',
        'join_url',          // <-- AJOUTE CE CHAMP
    ];

    // Définir les relations si besoin, par exemple avec la classe
    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    // Relation avec l'enseignant (User)
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
