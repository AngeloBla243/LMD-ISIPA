<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// class recours extends Model
// {
//     use HasFactory;

//     protected $table = 'recours';

//     protected $fillable = ['student_id', 'objet', 'description'];

//     public function user()
//     {
//         return $this->belongsTo(User::class, 'user_id'); // Assurez-vous que 'user_id' est la clé étrangère dans votre table 'recours'
//     }

//     // Méthode pour récupérer tous les recours
//     public static function getAllRecours()
//     {
//         return self::with('user')->get(); // Cela charge les utilisateurs associés
//     }



//     static public function getSingle()
//     {
//         return self::find(1);
//     }
// }


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recours extends Model
{
    use HasFactory;

    protected $table = 'recours';

    protected $fillable = ['student_id', 'class_id', 'subject_id', 'objet', 'numero', 'session_year'];

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
