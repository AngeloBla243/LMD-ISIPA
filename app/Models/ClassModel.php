<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class ClassModel extends Model
{
    use HasFactory;

    protected $table = 'class';

    protected $fillable = [
        'name',
        'opt',
        'amount',
        'status',
        'academic_year_id', // Ajouter cette ligne
        'created_by'
    ];


    static public function getSingle($id)
    {
        return self::find($id);
    }

    static public function getRecord()
    {
        $return = ClassModel::select('class.*', 'users.name as created_by_name')
            ->join('users', 'users.id', 'class.created_by');

        if (!empty(Request::get('name'))) {
            $return = $return->where('class.name', 'like', '%' . Request::get('name') . '%');
        }

        if (!empty(Request::get('date'))) {
            $return = $return->whereDate('class.created_at', '=', Request::get('date'));
        }

        $return = $return->where('class.is_delete', '=', 0)
            ->orderBy('class.id', 'asc')
            ->paginate(20);

        return $return;
    }

    static public function getClass()
    {
        $return = ClassModel::select('class.*')
            ->join('users', 'users.id', 'class.created_by')
            ->where('class.is_delete', '=', 0)
            ->where('class.status', '=', 0)
            ->orderBy('class.name', 'asc')
            ->get();

        return $return;
    }

    static public function getTotalClass()
    {
        $return = ClassModel::select('class.id')
            ->join('users', 'users.id', 'class.created_by')
            ->where('class.is_delete', '=', 0)
            ->where('class.status', '=', 0)
            ->count();

        return $return;
    }

    public function subjects()
    {
        return $this->belongsToMany(SubjectModel::class, 'class_subject', 'class_id', 'subject_id');
    }
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public static function getClassesByYear($yearId)
    {
        return self::where('academic_year_id', $yearId)
            ->where('is_delete', 0)
            ->get(['id', 'name', 'opt']);
    }

    public static function getClass1($academicYearId = null)
    {
        return self::when($academicYearId, function ($query) use ($academicYearId) {
            $query->where('academic_year_id', $academicYearId);
        })
            ->where('is_delete', 0)
            ->orderBy('name', 'asc')
            ->get();
    }
}
