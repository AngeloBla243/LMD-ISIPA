<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarksRegisterModel extends Model
{
    use HasFactory;

    protected $table = 'marks_register';

    protected $fillable = [
        'academic_year_id', // Ajouté
        'student_id',
        'exam_id',
        'class_id',
        'subject_id',
        'class_work',
        'exam',
        'status',
        'full_marks',
        'semester_id',
        'passing_mark',
        'ponde',
        'created_by'
    ];


    static public function CheckAlreadyMark($student_id, $exam_id, $class_id, $subject_id)
    {
        return MarksRegisterModel::where('student_id', '=', $student_id)->where('exam_id', '=', $exam_id)->where('class_id', '=', $class_id)->where('subject_id', '=', $subject_id)->first();
    }


    static public function getExam($student_id)
    {
        return MarksRegisterModel::select('marks_register.*', 'exam.name as exam_name')
            ->join('exam', 'exam.id', '=', 'marks_register.exam_id')
            ->where('marks_register.student_id', '=', $student_id)
            ->groupBy('marks_register.exam_id')
            ->get();
    }


    static public function getExamSubject($exam_id, $student_id)
    {
        return MarksRegisterModel::select('marks_register.*', 'exam.name as exam_name', 'subject.name as subject_name', 'subject.code as subject_code')
            ->join('exam', 'exam.id', '=', 'marks_register.exam_id')
            ->join('subject', 'subject.id', '=', 'marks_register.subject_id')
            ->where('marks_register.exam_id', '=', $exam_id)
            ->where('marks_register.student_id', '=', $student_id)
            ->get();
    }

    static public function getClass($exam_id, $student_id)
    {
        return MarksRegisterModel::select(
            'class.name as class_name',
            'class.opt as class_opt',
            'academic_years.name as academic_year_name'
        )
            ->join('exam', 'exam.id', '=', 'marks_register.exam_id')
            ->join('class', 'class.id', '=', 'marks_register.class_id')
            ->join('academic_years', 'academic_years.id', '=', 'class.academic_year_id') // Jointure
            ->join('subject', 'subject.id', '=', 'marks_register.subject_id')
            ->where('marks_register.exam_id', '=', $exam_id)
            ->where('marks_register.student_id', '=', $student_id)
            ->first();
    }

    static public function getClassResults($exam_id, $class_id)
    {
        return MarksRegisterModel::select(
            'student.id as student_id',
            'student.name as student_name',
            'subject.name as subject_name',
            'marks_register.class_work',
            'marks_register.exam',
            'marks_register.ponde',
            'exam.name as exam_name',
            'class.name as class_name'
        )
            ->join('exam', 'exam.id', '=', 'marks_register.exam_id')
            ->join('class', 'class.id', '=', 'marks_register.class_id')
            ->join('subject', 'subject.id', '=', 'marks_register.subject_id')
            ->join('student', 'student.id', '=', 'marks_register.student_id')
            ->where('marks_register.exam_id', '=', $exam_id)
            ->where('marks_register.class_id', '=', $class_id)
            ->orderBy('student.name')
            ->get();
    }
    // MarksRegisterModel.php
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function subject()
    {
        return $this->belongsTo(SubjectModel::class, 'subject_id');
    }

    public function exam()
    {
        return $this->belongsTo(ExamModel::class, 'exam_id');
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    // Méthode getExam avec filtre académique
    static public function getExams($student_id, $academic_year_id = null)
    {
        return self::select('exam.name as exam_name', 'exam.id as exam_id')
            ->join('exam', 'exam.id', '=', 'marks_register.exam_id')
            ->where('marks_register.student_id', $student_id)
            ->when($academic_year_id, function ($query) use ($academic_year_id) {
                $query->where('marks_register.academic_year_id', $academic_year_id);
            })
            ->groupBy('exam.id')
            ->get();
    }

    // Méthode getExamSubject avec filtre académique
    static public function getExamSubjects($exam_id, $student_id, $academic_year_id = null)
    {
        return self::select(
            'marks_register.*',
            'subject.name as subject_name',
            'subject.code as subject_code'
        )
            ->join('subject', 'subject.id', '=', 'marks_register.subject_id')
            ->where('marks_register.exam_id', $exam_id)
            ->where('marks_register.student_id', $student_id)
            ->when($academic_year_id, function ($query) use ($academic_year_id) {
                $query->where('marks_register.academic_year_id', $academic_year_id);
            })
            ->get();
    }
}
