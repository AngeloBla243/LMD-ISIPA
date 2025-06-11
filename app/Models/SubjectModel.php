<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class SubjectModel extends Model
{
    use HasFactory;

    protected $table = 'subject';
    protected $fillable = [
        'name',
        'code',
        'type',
        'academic_year_id',
        'ue_id', // Ajouté// Ajouté
        'status',
        'created_by'
    ];



    static public function getSingle($id)
    {
        return self::find($id);
    }

    // static public function getRecord()
    // {
    //     $return = SubjectModel::select('subject.*', 'users.name as created_by_name')
    //         ->join('users', 'users.id', 'subject.created_by');

    //     if (!empty(Request::get('name'))) {
    //         $return = $return->where('subject.name', 'like', '%' . Request::get('name') . '%');
    //     }

    //     if (!empty(Request::get('code'))) {
    //         $return = $return->where('subject.code', 'like', '%' . Request::get('code') . '%');
    //     }

    //     if (!empty(Request::get('type'))) {
    //         $return = $return->where('subject.type', '=', Request::get('type'));
    //     }


    //     if (!empty(Request::get('date'))) {
    //         $return = $return->whereDate('subject.created_at', '=', Request::get('date'));
    //     }

    //     $return = $return->where('subject.is_delete', '=', 0)
    //         ->orderBy('subject.id', 'asc')
    //         ->paginate(20);

    //     return $return;
    // }


    static public function getRecord()
    {
        $return = SubjectModel::select(
            'subject.*',
            'users.name as created_by_name',
            'academic_years.name as academic_year_name',
            'ue.code as ue_code', // Ajout du code UE
            'ue.name as ue_name'  // (optionnel) Ajout du nom UE
        )
            ->join('users', 'users.id', '=', 'subject.created_by')
            ->leftJoin('academic_years', 'academic_years.id', '=', 'subject.academic_year_id')
            ->leftJoin('ue', 'ue.id', '=', 'subject.ue_id') // Jointure UE
            ->where('subject.is_delete', '=', 0);



        if (!empty(Request::get('name'))) {
            $return = $return->where('subject.name', 'like', '%' . Request::get('name') . '%');
        }

        if (!empty(Request::get('code'))) {
            $return = $return->where('subject.code', 'like', '%' . Request::get('code') . '%');
        }

        if (!empty(Request::get('type'))) {
            $return = $return->where('subject.type', '=', Request::get('type'));
        }

        if (!empty(Request::get('date'))) {
            $return = $return->whereDate('subject.created_at', '=', Request::get('date'));
        }

        $return = $return->where('subject.is_delete', '=', 0)
            ->orderBy('subject.id', 'asc')
            ->paginate(50);

        return $return;
    }



    static public function getSubject()
    {
        $return = SubjectModel::select('subject.*')
            ->join('users', 'users.id', 'subject.created_by')
            ->where('subject.is_delete', '=', 0)
            ->where('subject.status', '=', 0)
            ->orderBy('subject.name', 'asc')
            ->get();

        return $return;
    }

    public static function getSubjectsByYear($yearId)
    {
        return self::where('academic_year_id', $yearId)
            ->where('is_delete', 0)
            ->get(['id', 'name', 'code']);
    }


    static public function getTotalSubject()
    {
        $return = SubjectModel::select('subject.id')
            ->join('users', 'users.id', 'subject.created_by')
            ->where('subject.is_delete', '=', 0)
            ->where('subject.status', '=', 0)
            ->count();

        return $return;
    }

    public function classSubjects()
    {
        return $this->hasMany(ClassSubjectModel::class, 'subject_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    // app/Models/SubjectModel.php

    public function ue()
    {
        return $this->belongsTo(UeModel::class, 'ue_id');
    }
}
