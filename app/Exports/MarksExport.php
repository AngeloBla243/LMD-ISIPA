<?php

namespace App\Exports;

use App\Models\MarksRegisterModel;
use App\Models\AssignClassTeacherModel;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MarksExport implements FromCollection, WithHeadings
{
    protected $examId;
    protected $classId;
    protected $teacherId;

    public function __construct($examId, $classId)
    {
        $this->examId = $examId;
        $this->classId = $classId;
        $this->teacherId = Auth::id();
    }

    public function collection()
    {
        // Récupérer la liste des matières assignées à cet enseignant
        $teacherSubjectIds = AssignClassTeacherModel::where('teacher_id', $this->teacherId)
            ->pluck('subject_id')
            ->filter()
            ->toArray();

        // Récupérer les notes correspondantes
        return MarksRegisterModel::where('exam_id', $this->examId)
            ->where('class_id', $this->classId)
            ->whereIn('subject_id', $teacherSubjectIds)
            ->get(['student_id', 'subject_id', 'class_work', 'exam']);
    }

    public function headings(): array
    {
        return ['student_id', 'subject_id', 'class_work', 'exam'];
    }
}
