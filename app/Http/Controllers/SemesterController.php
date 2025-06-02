<?php


namespace App\Http\Controllers;

use App\Models\ExamScheduleModel;

use App\Models\semestre;
use App\Models\ExamModel; // Modèle pour les classes assignées
use Illuminate\Support\Facades\Auth; //

use App\Models\AcademicYear;

use Illuminate\Http\Request;


class SemesterController extends Controller
{


    public function list()
    {
        $data['getRecord'] = Semestre::with('academicYear')->orderBy('id', 'desc')->get();
        $data['header_title'] = "Liste des Semestres";
        return view('admin.examinations.semestre.list', $data);
    }

    // public function createSemester(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|unique:semesters,name',
    //         'academic_year_id' => 'required|exists:academic_years,id'
    //     ]);

    //     // Créer le semestre
    //     $semester = semestre::create([
    //         'name' => $request->name,
    //         'academic_year_id' => $request->academic_year_id
    //     ]);

    //     // Créer automatiquement les deux sessions
    //     foreach ([1, 2] as $session) {
    //         ExamModel::create([
    //             'semester_id' => $semester->id,
    //             'academic_year_id' => $request->academic_year_id,
    //             'session' => $session,
    //             'name' => $semester->name . ' - Session ' . $session,
    //             'is_active' => ($session == 1) // Session 1 active par défaut
    //         ]);
    //     }

    //     return redirect()->view('admin.examinations.semestre.list')->with('success', 'Semestre créé avec sessions');
    // }

    public function createForm()
    {
        $data['academicYears'] = AcademicYear::orderBy('start_date', 'desc')->get();
        $data['header_title'] = "Créer un Semestre";
        return view('admin.examinations.semestre.create', $data);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:semesters,name', // Table 'semestres'
            'academic_year_id' => 'required|exists:academic_years,id'
        ]);

        // Création du semestre
        $semestre = Semestre::create([
            'name' => $request->name,
            'academic_year_id' => $request->academic_year_id
        ]);

        // Création des sessions
        foreach ([1, 2] as $session) {
            ExamModel::create([
                'semester_id' => $semestre->id,
                'academic_year_id' => $semestre->academic_year_id,
                'session' => $session,
                'name' => $semestre->name . ' - Session ' . $session,
                'is_active' => ($session == 1)
            ]);
        }

        return redirect()->route('admin.semester.list')->with('success', 'Semestre créé avec sessions');
    }
}
