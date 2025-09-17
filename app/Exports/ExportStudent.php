<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\User;

class ExportStudent implements FromCollection, WithMapping, WithHeadings
{
    /**
     * Définir la ligne des en-têtes dans le fichier Excel.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            "ID",
            "Student Name",
            "Parent Name",
            "Email",
            "Admission Number",
            "Roll Number",
            "Class",
            "Gender",
            "Date of Birth",
            "Caste",
            "Religion",
            "Mobile Number",
            "Admission Date",
            "Blood Group",
            "Height",
            "Weight",
            "Status",
            "Created Date"
        ];
    }

    /**
     * Mapper chaque modèle User vers une ligne Excel.
     *
     * @param mixed $value
     * @return array
     */
    public function map($value): array
    {
        $student_name = trim($value->name . ' ' . $value->last_name);

        // Gestion du nom complet du parent si disponible
        $parent_name = '';
        if (!empty($value->parent_name) || !empty($value->parent_last_name)) {
            $parent_name = trim($value->parent_name . ' ' . $value->parent_last_name);
        }

        $date_of_birth = !empty($value->date_of_birth) ? date('d-m-Y', strtotime($value->date_of_birth)) : '';
        $admission_date = !empty($value->admission_date) ? date('d-m-Y', strtotime($value->admission_date)) : '';

        $status = ($value->status == 0) ? 'Active' : 'Inactive';

        return [
            $value->id,
            $student_name,
            $parent_name,
            $value->email,
            $value->admission_number,
            $value->roll_number,
            // assurez-vous que class_name est chargé ou calculé dans getStudent()
            $value->class_name ?? '',
            $value->gender,
            $date_of_birth,
            $value->caste,
            $value->religion,
            $value->mobile_number,
            $admission_date,
            $value->blood_group,
            $value->height,
            $value->weight,
            $status,
            date('d-m-Y H:i A', strtotime($value->created_at)),
        ];
    }

    /**
     * Fournir la collection des étudiants à exporter.
     * Appelle une méthode statique du modèle User qui doit renvoyer une collection.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Ici, 1 indique la suppression de la pagination dans getStudent()
        $remove_pagination = 1;

        // Cette méthode getStudent doit être définie dans User.php
        // Elle doit inclure les relations nécessaires pour avoir class_name et parent name, etc.
        return User::getStudent($remove_pagination);
    }
}
