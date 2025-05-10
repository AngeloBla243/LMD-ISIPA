<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class ExamModel extends Model
{
    use HasFactory;

    protected $table = 'exam';

    static public function getSingle($id)
    {
        return self::find($id);
    }


    static public function getRecord()
    {
        $return = self::select('exam.*', 'academic_years.name as academic_year_name', 'users.name as created_name')
            ->join('users', 'users.id', '=', 'exam.created_by')
            ->leftJoin('academic_years', 'academic_years.id', '=', 'exam.academic_year_id');

        if (!empty(Request::get('name'))) {
            $return = $return->where('exam.name', 'like', '%' . Request::get('name') . '%');
        }
        if (!empty(Request::get('date'))) {
            $return = $return->whereDate('exam.created_at', '=', Request::get('date'));
        }

        $return = $return->where('exam.is_delete', '=', 0)
            ->orderBy('exam.id', 'asc')
            ->paginate(50);
        return $return;
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

}
