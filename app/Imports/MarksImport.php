<?php

namespace App\Imports;

use App\Models\MarksRegisterModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MarksImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Les clés doivent correspondre aux en-têtes d'export (sans majuscules ni espaces)
        return MarksRegisterModel::updateOrCreate(
            [
                'student_id' => $row['student_id'],
                'subject_id' => $row['subject_id'],
                'exam_id' => request('exam_id'),
                'class_id' => request('class_id'),
            ],
            [
                'class_work' => $row['class_work'] ?? 0,
                'exam' => $row['exam'] ?? 0,
                'status' => 1,
            ]
        );
    }
}
