<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamScheduleModel extends Model
{
    use HasFactory;

    protected $table = 'exam_schedule';

    static public function getSingle($id)
    {
        return self::find($id);
    }


    static public function getRecordSingle($exam_id, $class_id, $subject_id)
    {
        return ExamScheduleModel::where('exam_id', '=', $exam_id)->where('class_id', '=', $class_id)->where('subject_id', '=', $subject_id)->first();
    }

    static public function deleteRecord($exam_id, $class_id)
    {
        ExamScheduleModel::where('exam_id', '=', $exam_id)->where('class_id', '=', $class_id)->delete();
    }


    static public function getExam($class_id)
    {
        return ExamScheduleModel::select('exam_schedule.*', 'exam.name as exam_name')
            ->join('exam', 'exam.id', '=', 'exam_schedule.exam_id')
            ->where('exam_schedule.class_id', '=', $class_id)
            ->groupBy('exam_schedule.exam_id')
            ->orderBy('exam_schedule.id', 'desc')
            ->get();
    }


    static public function getExamTeacher($teacher_id)
    {
        return ExamScheduleModel::select('exam_schedule.*', 'exam.name as exam_name')
            ->join('exam', 'exam.id', '=', 'exam_schedule.exam_id')
            ->join('assign_class_teacher', 'assign_class_teacher.class_id', '=', 'exam_schedule.class_id')
            ->where('assign_class_teacher.teacher_id', '=', $teacher_id)
            ->groupBy('exam_schedule.exam_id')
            ->orderBy('exam_schedule.id', 'desc')
            ->get();
    }

    static public function getExamTimetable($exam_id, $class_id)
    {
        return ExamScheduleModel::select('exam_schedule.*', 'subject.name as subject_name', 'subject.type as subject_type')
            ->join('subject', 'subject.id', '=', 'exam_schedule.subject_id')
            ->where('exam_schedule.exam_id', '=', $exam_id)
            ->where('exam_schedule.class_id', '=', $class_id)
            ->get();
    }

    static public function getExamTimetable1($exam_id, $class_id, $teacher_id)
    {
        return ExamScheduleModel::select('exam_schedule.*', 'subject.name as subject_name', 'subject.type as subject_type')
            ->join('subject', 'subject.id', '=', 'exam_schedule.subject_id')
            ->join('assign_class_teacher', function ($join) use ($teacher_id) {
                $join->on('assign_class_teacher.subject_id', '=', 'exam_schedule.subject_id')
                    ->where('assign_class_teacher.teacher_id', '=', $teacher_id);
            })
            ->where('exam_schedule.exam_id', '=', $exam_id)
            ->where('exam_schedule.class_id', '=', $class_id)
            ->get();
    }
    static public function getSubject($exam_id, $class_id)
    {
        return ExamScheduleModel::select('exam_schedule.*', 'subject.name as subject_name', 'subject.type as subject_type')
            ->join('subject', 'subject.id', '=', 'exam_schedule.subject_id')
            ->where('exam_schedule.exam_id', '=', $exam_id)
            ->where('exam_schedule.class_id', '=', $class_id)
            ->get();
    }

    static public function getSubject_teacher($exam_id, $class_id, $teacher_id)
    {
        return ExamScheduleModel::select('exam_schedule.*', 'subject.name as subject_name', 'subject.type as subject_type')
            ->join('subject', 'subject.id', '=', 'exam_schedule.subject_id')
            // Ajout de la jointure pour l'assignation de classe
            ->join('assign_class_teacher', function ($join) use ($teacher_id) {
                $join->on('assign_class_teacher.subject_id', '=', 'subject.id')
                    ->where('assign_class_teacher.teacher_id', '=', $teacher_id)
                    ->where('assign_class_teacher.is_delete', '=', 0); // Vérifiez que l'assignation n'est pas supprimée
            })
            ->where('exam_schedule.exam_id', '=', $exam_id)
            ->where('exam_schedule.class_id', '=', $class_id)
            ->get();
    }



    static public function getExamTimetableTeacher($teacher_id)
    {
        return ExamScheduleModel::select('exam_schedule.*', 'class.name as class_name', 'subject.name as subject_name', 'exam.name as exam_name')
            ->join('assign_class_teacher', 'assign_class_teacher.class_id', '=', 'exam_schedule.class_id')
            ->join('class', 'class.id', '=', 'exam_schedule.class_id')
            ->join('subject', 'subject.id', '=', 'exam_schedule.subject_id')
            ->join('exam', 'exam.id', '=', 'exam_schedule.exam_id')
            ->where('assign_class_teacher.teacher_id', '=', $teacher_id)
            ->get();
    }


    static public function getMark($student_id, $exam_id, $class_id, $subject_id)
    {
        return MarksRegisterModel::CheckAlreadyMark($student_id, $exam_id, $class_id, $subject_id);
    }

    static public function getExamIdBySubject($subject_id, $class_id)
    {
        return ExamScheduleModel::where('subject_id', $subject_id)
            ->where('class_id', $class_id)
            ->pluck('exam_id') // Récupère uniquement l'ID de l'examen
            ->first();
    }
}
