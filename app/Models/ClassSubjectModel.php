<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class ClassSubjectModel extends Model
{
    use HasFactory;

    protected $table = 'class_subject';

    protected $fillable = [
        'class_id',
        'subject_id',
        'academic_year_id', // Ajouté
        'created_by',
        'status',
        'is_delete'
    ];

    static public function getSingle($id)
    {
        return self::find($id);
    }

    static public function getRecord()
    {
        $return = self::select(
            'class_subject.*',
            'class.name as class_name',
            'class.opt as class_opt',
            'subject.name as subject_name',
            'subject.code as subject_code',
            'academic_years.name as academic_year_name'
        ) // Nouveau
            ->join('class', 'class.id', '=', 'class_subject.class_id')
            ->join('subject', 'subject.id', '=', 'class_subject.subject_id')
            ->join('academic_years', 'academic_years.id', '=', 'class.academic_year_id') // Jointure
            ->join('users', 'users.id', '=', 'class_subject.created_by')
            ->where('class_subject.is_delete', 0);


        if (!empty(Request::get('class_name'))) {
            $return = $return->where('class.name', 'like', '%' . Request::get('class_name') . '%');
        }

        if (!empty(Request::get('subject_name'))) {
            $return = $return->where('subject.name', 'like', '%' . Request::get('subject_name') . '%');
        }

        if (!empty(Request::get('date'))) {
            $return = $return->whereDate('class_subject.created_at', '=', Request::get('date'));
        }

        $return = $return->orderBy('class_subject.id', 'asc')
            ->paginate(50);

        return $return;
    }

    static public function MySubjectAdmin($class_id)
    {
        return  self::select('class_subject.*', 'subject.name as subject_name', 'subject.type as subject_type')
            ->join('subject', 'subject.id', '=', 'class_subject.subject_id')
            ->join('class', 'class.id', '=', 'class_subject.class_id')
            ->join('users', 'users.id', '=', 'class_subject.created_by')
            ->where('class_subject.class_id', '=', $class_id)
            ->where('class_subject.is_delete', '=', 0)
            ->where('class_subject.status', '=', 0)
            ->orderBy('class_subject.id', 'asc')
            ->get();
    }

    // // Dans chaque modèle concerné (ClassSubjectModel, etc.)



    // static public function MySubjectTotal($class_id)
    // {
    //     return  self::select('class_subject.id')
    //         ->join('subject', 'subject.id', '=', 'class_subject.subject_id')
    //         ->join('class', 'class.id', '=', 'class_subject.class_id')
    //         ->join('users', 'users.id', '=', 'class_subject.created_by')
    //         ->where('class_subject.class_id', '=', $class_id)
    //         ->where('class_subject.is_delete', '=', 0)
    //         ->where('class_subject.status', '=', 0)
    //         ->orderBy('class_subject.id', 'asc')
    //         ->count();
    // }
    // Récupère les matières d'une classe pour une année académique spécifique
    static public function MySubject($class_id, $academic_year_id)
    {
        return self::select(
            'class_subject.*',
            'subject.name as subject_name',
            'subject.type as subject_type',
            'academic_years.name as academic_year_name' // Ajout du nom de l'année
        )
            ->join('subject', 'subject.id', '=', 'class_subject.subject_id')
            ->join('class', 'class.id', '=', 'class_subject.class_id')
            ->join('academic_years', 'academic_years.id', '=', 'class_subject.academic_year_id') // Jointure explicite
            ->join('users', 'users.id', '=', 'class_subject.created_by')
            ->where('class_subject.class_id', '=', $class_id)
            ->where('class_subject.academic_year_id', '=', $academic_year_id) // Filtre académique
            ->where('class_subject.is_delete', '=', 0)
            ->where('class_subject.status', '=', 0)
            ->orderBy('class_subject.id', 'asc')
            ->get();
    }

    static public function MySubjectTotal($class_id, $academic_year_id)
    {
        return self::select('class_subject.id')
            ->join('subject', 'subject.id', '=', 'class_subject.subject_id')
            ->join('class', 'class.id', '=', 'class_subject.class_id')
            ->join('academic_years', 'academic_years.id', '=', 'class_subject.academic_year_id')
            ->join('users', 'users.id', '=', 'class_subject.created_by')
            ->where('class_subject.class_id', '=', $class_id)
            ->where('class_subject.academic_year_id', '=', $academic_year_id)
            ->where('class_subject.is_delete', '=', 0)
            ->where('class_subject.status', '=', 0)
            ->count();
    }


    // Dans chaque modèle concerné (ClassSubjectModel, etc.)
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }




    static public function getAlreadyFirst($class_id, $subject_id)
    {
        return self::where('class_id', '=', $class_id)->where('subject_id', '=', $subject_id)->first();
    }

    static public function getAssignSubjectID($class_id)
    {
        return self::where('class_id', '=', $class_id)->where('is_delete', '=', 0)->get();
    }

    static public function deleteSubject($class_id)
    {
        return self::where('class_id', '=', $class_id)->delete();
    }

    // Dans le modèle ClassSubjectModel
    public function subject()
    {
        return $this->belongsTo(SubjectModel::class, 'subject_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    static public function getRecordByDepartment($departmentId)
    {
        $return = self::select(
            'class_subject.*',
            'class.name as class_name',
            'class.opt as class_opt',
            'subject.name as subject_name',
            'subject.code as subject_code',
            'academic_years.name as academic_year_name',
            'users.name as created_by_name'
        )
            ->join('class', 'class.id', '=', 'class_subject.class_id')
            ->join('subject', 'subject.id', '=', 'class_subject.subject_id')
            ->join('academic_years', 'academic_years.id', '=', 'class_subject.academic_year_id')
            ->join('users', 'users.id', '=', 'class_subject.created_by')
            ->where('class_subject.is_delete', 0)
            ->where('class.department_id', $departmentId);

        // Vous pouvez ajouter les filtres request ici

        return $return->orderBy('class_subject.id', 'asc')->paginate(50);
    }
}
