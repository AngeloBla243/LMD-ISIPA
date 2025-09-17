<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\WithValidation;

class StudentsImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
     * Validation des lignes d'import
     */
    public function rules(): array
    {
        return [
            '*.email' => ['required', 'email', 'unique:users,email'],
            '*.name' => 'required|string',
            '*.last_name' => 'nullable|string',
            '*.admission_number' => 'nullable|string|unique:users,admission_number',
            // Ajoutez d'autres règles selon vos colonnes
        ];
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            User::create([
                'name' => $row['name'],
                'last_name' => $row['last_name'] ?? null,
                'email' => $row['email'],
                'admission_number' => $row['admission_number'],
                'roll_number' => $row['roll_number'] ?? null,
                'class_id' => $row['class_id'] ?? null, // Assurez-vous que ces colonnes existent dans l’excel
                'gender' => $row['gender'] ?? null,
                'date_of_birth' => $row['date_of_birth'] ?? null,
                'caste' => $row['caste'] ?? null,
                'religion' => $row['religion'] ?? null,
                'mobile_number' => $row['mobile_number'] ?? null,
                'admission_date' => $row['admission_date'] ?? null,
                'blood_group' => $row['blood_group'] ?? null,
                'height' => $row['height'] ?? null,
                'weight' => $row['weight'] ?? null,
                'status' => 0, // Par défaut actif
                'password' => Hash::make('123456'), // Mot de passe généré automatiquement // Mot de passe par défaut, à changer
                'user_type' => 3, // Type étudiant
            ]);
        }
    }
}
