<?php

namespace App\Exports;

use App\Models\MarksRegisterModel;
use App\Models\AssignClassTeacherModel;
use App\Models\User;              // Modèle étudiant (utilisateur)
use App\Models\SubjectModel;      // Modèle matière
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class MarksExport implements FromCollection, WithHeadings, WithEvents
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
        $teacherSubjectIds = AssignClassTeacherModel::where('teacher_id', $this->teacherId)
            ->pluck('subject_id')
            ->filter()
            ->toArray();

        return MarksRegisterModel::where('exam_id', $this->examId)
            ->where('class_id', $this->classId)
            ->whereIn('subject_id', $teacherSubjectIds)
            ->get()
            ->map(function ($mark) {
                $student = User::find($mark->student_id);
                $subject = SubjectModel::find($mark->subject_id);

                $studentName = $student ? ($student->name . ' ' . ($student->last_name ?? '')) : 'N/A';
                $subjectName = $subject ? $subject->name : 'N/A';

                return [
                    'student_id'    => $mark->student_id, // masqué dans Excel
                    'subject_id'    => $mark->subject_id, // masqué dans Excel
                    'student_name'  => $studentName,
                    'subject_name'  => $subjectName,
                    'class_work'    => $mark->class_work,
                    'exam'          => $mark->exam,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'student_id',   // masqué Excel
            'subject_id',   // masqué Excel
            'Nom étudiant',
            'Matière',
            'Travail de classe',
            'Examen',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Masquer colonnes A et B (student_id, subject_id)
                $sheet->getColumnDimension('A')->setVisible(false);
                $sheet->getColumnDimension('B')->setVisible(false);

                // Style de l'en-tête C1:F1
                $sheet->getStyle('C1:F1')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '009879']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                ]);

                // Bordures sur tout le tableau utile
                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle("C1:F$lastRow")->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                ]);

                // Auto width sur colonnes C à F
                foreach (range('C', 'F') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Validation des entrées pour colonnes E et F (Travail, Examen)
                for ($row = 2; $row <= $lastRow; $row++) {
                    foreach (['E', 'F'] as $col) {
                        $validation = $sheet->getCell($col . $row)->getDataValidation();
                        $validation->setType(DataValidation::TYPE_WHOLE);
                        $validation->setErrorStyle(DataValidation::STYLE_STOP);
                        $validation->setAllowBlank(true);
                        $validation->setShowInputMessage(true);
                        $validation->setShowErrorMessage(true);
                        $validation->setErrorTitle('Valeur invalide');
                        $validation->setError('La valeur doit être comprise entre 0 et 10.');
                        $validation->setPrompt('Saisissez un nombre entre 0 et 10.');
                        $validation->setFormula1(0);
                        $validation->setFormula2(10);
                    }
                }
            }
        ];
    }
}
