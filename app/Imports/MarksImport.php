<?php

namespace App\Imports;

use App\Models\MarksRegisterModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MarksImport implements ToModel, WithHeadingRow
{
    protected $examId;
    protected $classId;

    public function __construct($examId, $classId)
    {
        $this->examId = $examId;
        $this->classId = $classId;
    }

    public function model(array $row)
    {
        return MarksRegisterModel::updateOrCreate(
            [
                'student_id' => $row['student_id'],
                'subject_id' => $row['subject_id'],
                'exam_id'    => $this->examId,
                'class_id'   => $this->classId,
            ],
            [
                // ⚡ On utilise exactement les titres exportés
                'class_work' => $row['travail_de_classe'] ?? 0,
                'exam'       => $row['examen'] ?? 0,
                'status'     => 1,
            ]
        );
    }
}
