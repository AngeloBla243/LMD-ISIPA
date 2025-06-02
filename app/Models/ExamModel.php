<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class ExamModel extends Model
{
    use HasFactory;

    protected $table = 'exam';

    protected $fillable = [
        'name',
        'semester_id', // Ajouté
        'academic_year_id', // Ajouté
        'session',
        'is_active',
        'created_by'
    ];



    static public function getSingle($id)
    {
        return self::find($id);
    }


    // static public function getRecord()
    // {
    //     $return = self::select('exam.*', 'academic_years.name as academic_year_name', 'users.name as created_name')
    //         ->join('users', 'users.id', '=', 'exam.created_by')
    //         ->leftJoin('academic_years', 'academic_years.id', '=', 'exam.academic_year_id');

    //     if (!empty(Request::get('name'))) {
    //         $return = $return->where('exam.name', 'like', '%' . Request::get('name') . '%');
    //     }
    //     if (!empty(Request::get('date'))) {
    //         $return = $return->whereDate('exam.created_at', '=', Request::get('date'));
    //     }

    //     $return = $return->where('exam.is_delete', '=', 0)
    //         ->orderBy('exam.id', 'asc')
    //         ->paginate(50);
    //     return $return;
    // }

    static public function getRecord()
    {
        return self::select(
            'exam.*',
            'academic_years.name as academic_year_name',
            'users.name as created_name'
        )
            ->leftJoin('users', 'users.id', '=', 'exam.created_by') // Remplacer par LEFT JOIN
            ->leftJoin('academic_years', 'academic_years.id', '=', 'exam.academic_year_id')
            ->where('exam.is_delete', 0)
            ->when(!empty(request('name')), function ($query) {
                $query->where('exam.name', 'LIKE', '%' . request('name') . '%');
            })
            ->when(!empty(request('date')), function ($query) {
                $query->whereDate('exam.created_at', request('date'));
            })
            ->orderBy('exam.id', 'desc')
            ->paginate(request('per_page', 50));
    }





    static public function getExam()
    {
        $return = self::select('exam.*')
            ->join('users', 'users.id', '=', 'exam.created_by')
            ->where('exam.is_delete', '=', 0)
            ->orderBy('exam.name', 'asc')
            ->get();
        return $return;
    }


    static public function getTotalExam()
    {
        $return = self::select('exam.id')
            ->join('users', 'users.id', '=', 'exam.created_by')
            ->where('exam.is_delete', '=', 0)
            ->count();
        return $return;
    }


    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function semester()
    {
        return $this->belongsTo(semestre::class, 'semester_id');
    }


    public function marks()
    {
        return $this->hasMany(MarksRegisterModel::class, 'exam_id');
    }
    public function exam()
    {
        return $this->belongsTo(ExamModel::class, 'exam_id');
    }
}
