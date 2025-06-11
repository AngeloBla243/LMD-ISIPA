<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;

class StudentsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new User([
            'name'          => $row['nom'],
            'last_name'     => $row['prenom'],
            'email'         => $row['email'],
            'password'      => Hash::make($row['password'] ?? 'password'),
            'admission_number' => $row['matricule'],
            'roll_number'   => $row['numero_inscription'],
            'gender'        => $row['genre'],
            'date_of_birth' => $row['date_naissance'],
            'user_type'     => 3,
            'is_delete'     => 0
        ]);
    }
}
