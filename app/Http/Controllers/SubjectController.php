<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubjectModel;
use App\Models\ClassSubjectModel;
use App\Models\recours;
use App\Models\User;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\Auth;

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
        $data['header_title'] = "Ajouter une Matière";
        return view('admin.subject.add', $data);
    }



    public function insert(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'academic_year_id' => 'required|exists:academic_years,id',
            'type' => 'required|string'
        ]);

        $save = new SubjectModel;
        $save->name = trim($request->name);
        $save->code = trim($request->code);
        $save->type = trim($request->type);
        $save->academic_year_id = $request->academic_year_id; // Ajouté
        $save->status = $request->status;
        $save->created_by = Auth::id();
        $save->save();

        return redirect('admin/subject/list')->with('success', 'Matière créée avec succès');
    }


    public function edit($id)
    {
        $data['getRecord'] = SubjectModel::findOrFail($id);
        $data['academicYears'] = AcademicYear::orderBy('start_date', 'desc')->get(); // Ajouté
        $data['header_title'] = "Modifier la Matière";
        return view('admin.subject.edit', $data);
    }

    public function update($id, Request $request)
    {

        $save = SubjectModel::findOrFail($id);
        $save->name = trim($request->name);
        $save->code = trim($request->code);
        $save->type = trim($request->type);
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

        $data['getRecord'] = ClassSubjectModel::MySubject(Auth::user()->class_id);

        $data['header_title'] = "My Subject";
        return view('student.my_subject', $data);
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


    public function MySubjectRecours(Request $request)
    {
        // Récupère le dernier numéro inséré
        $lastRecours = Recours::orderBy('numero', 'desc')->first();

        // Assigne le prochain numéro
        $nextNumero = $lastRecours ? $lastRecours->numero + 1 : 1; // Commence à 1 si aucun numéro n'existe

        $getStudent = Auth::user();

        // Récupérer la date actuelle
        $currentDate = Carbon::now();
        $currentMonth = $currentDate->format('F'); // Mois en format texte (ex : January)
        $currentYear = $currentDate->year; // Année (ex : 2024)


        // Insérer les données dans la table 'recours'
        $save = new Recours();
        $save->numero = $nextNumero;
        $save->student_id = $getStudent->id;
        $save->class_id = $getStudent->class_id;
        $save->subject_id = $request->input('subject_id');  // ID du sujet (subject_id)
        $save->objet = implode(', ', $request->objet);  // Texte de l'objet de recours
        $save->session_year = "{$currentMonth} {$currentYear}"; // Utiliser le mois et l'année


        $save->save();

        return response()->json([
            'nextNumero' => $nextNumero,
            'session_year' => $save->session_year // Vous pouvez également le renvoyer ici


        ]);
    }
}
