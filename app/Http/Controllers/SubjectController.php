<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubjectModel;
use App\Models\ClassSubjectModel;
use App\Models\recours;
use App\Models\User;
use App\Models\AcademicYear;
use App\Models\UeModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Carbon\Carbon;




class SubjectController extends Controller
{


    public function list()
    {
        $data['getRecord'] = SubjectModel::getRecord();
        $data['academicYears'] = AcademicYear::orderBy('start_date', 'desc')->get();
        $data['header_title'] = "Liste des Matières";
        return view('admin.subject.list', $data);
    }



    public function add()
    {
        $data['academicYears'] = AcademicYear::orderBy('start_date', 'desc')->get();
        $data['ueList'] = UeModel::orderBy('code')->get();
        $data['header_title'] = "Ajouter une Matière";
        return view('admin.subject.add', $data);
    }



    public function insert(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'ue_id' => 'nullable|exists:ue,id', // Validation UE
            'academic_year_id' => 'required|exists:academic_years,id',
            'type' => 'required|string'
        ]);

        $save = new SubjectModel;
        $save->name = trim($request->name);
        $save->code = trim($request->code);
        $save->type = trim($request->type);
        $save->ue_id = $request->ue_id ?: null; // Ajout du UE_ID
        $save->academic_year_id = $request->academic_year_id;
        $save->status = $request->status;
        $save->created_by = Auth::id();
        $save->save();

        return redirect('admin/subject/list')->with('success', 'Matière créée avec succès');
    }



    public function edit($id)
    {
        $data['getRecord'] = SubjectModel::findOrFail($id);
        $data['academicYears'] = AcademicYear::orderBy('start_date', 'desc')->get(); // Ajouté
        $data['ueList'] = UeModel::orderBy('code')->get();
        $data['header_title'] = "Modifier la Matière";
        return view('admin.subject.edit', $data);
    }

    public function update($id, Request $request)
    {

        $save = SubjectModel::findOrFail($id);
        $save->name = trim($request->name);
        $save->code = trim($request->code);
        $save->type = trim($request->type);
        $save->ue_id = $request->ue_id ?: null; // Ajout du UE_ID
        $save->academic_year_id = $request->academic_year_id; // Ajouté
        $save->status = $request->status;
        $save->save();

        return redirect('admin/subject/list')->with('success', 'Matière mise à jour');
    }

    public function delete($id)
    {
        $save = SubjectModel::getSingle($id);
        $save->is_delete = 1;
        $save->save();

        return redirect()->back()->with('success', "Subject Sucessfully Deleted");
    }
    // student side

    public function MySubject()
    {
        // Récupérer l'année académique active
        $academicYearId = session('academic_year_id', AcademicYear::where('is_active', 1)->value('id'));

        // Récupérer la classe de l'étudiant pour cette année via student_class
        $studentClass = DB::table('student_class')
            ->where('student_id', Auth::id())
            ->where('academic_year_id', $academicYearId)
            ->first();

        if (!$studentClass) {
            return redirect()->back()->with('error', 'Aucune classe assignée pour cette année académique.');
        }

        // Passer l'année académique à la vue
        $data['academic_year_id'] = $academicYearId;
        $data['getRecord'] = ClassSubjectModel::MySubject($studentClass->class_id, $academicYearId);
        $data['header_title'] = "Mes Matières";

        return view('student.my_subject', $data);
    }

    public function MySubjectRecours(Request $request)
    {
        // Validation incluant l'année académique
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'subject_id' => 'required|exists:subject,id',
            'objet' => 'required|array'
        ]);

        try {
            $academicYearId = $request->academic_year_id;

            // Récupérer la classe de l'étudiant pour CETTE année académique
            $studentClass = DB::table('student_class')
                ->where('student_id', Auth::id())
                ->where('academic_year_id', $academicYearId)
                ->first();

            if (!$studentClass) {
                return response()->json([
                    'error' => 'Vous n\'êtes pas inscrit dans une classe pour cette année académique.'
                ], 400);
            }

            // Numéro de recours incrémenté par année académique
            $lastRecours = Recours::where('academic_year_id', $academicYearId)
                ->orderBy('numero', 'desc')
                ->first();

            $nextNumero = $lastRecours ? $lastRecours->numero + 1 : 1;

            $save = new Recours();
            $save->numero = $nextNumero;
            $save->student_id = Auth::id();
            $save->class_id = $studentClass->class_id; // <-- Classe pour l'année sélectionnée
            $save->academic_year_id = $academicYearId;
            $save->subject_id = $request->subject_id;
            $save->objet = implode(', ', $request->objet);
            $save->session_year = Carbon::now()->format('F Y');
            $save->save();

            return response()->json([
                'success' => true,
                'nextNumero' => $nextNumero,
                'session_year' => $save->session_year
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur création recours : ' . $e->getMessage());
            return response()->json([
                'error' => 'Une erreur est survenue lors de la création du recours'
            ], 500);
        }
    }



    // parent side

    public function ParentStudentSubject($student_id)
    {
        $user = User::getSingle($student_id);
        $data['getUser'] = $user;
        $data['getRecord'] = ClassSubjectModel::MySubject($user->class_id);
        $data['header_title'] = "Student Subject";
        return view('parent.my_student_subject', $data);
    }
}
