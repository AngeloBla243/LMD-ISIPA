<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubjectModel;
use App\Models\ClassSubjectModel;
use App\Models\recours;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;




class SubjectController extends Controller
{
    public function list()
    {
        $data['getRecord'] = SubjectModel::getRecord();
        $data['header_title'] = "Subject List";
        return view('admin.subject.list', $data);
    }

    public function add()
    {
        $data['header_title'] = "Add Subject";
        return view('admin.subject.add', $data);
    }

    public function insert(Request $request)
    {
        $save = new SubjectModel;
        $save->name = trim($request->name);
        $save->type = trim($request->type);
        $save->status = trim($request->status);
        $save->created_by = Auth::user()->id;
        $save->save();

        return redirect('admin/subject/list')->with('success', "Subject Sucessfully Created");
    }

    public function edit($id)
    {
        $data['getRecord'] = SubjectModel::getSingle($id);
        if (!empty($data['getRecord'])) {
            $data['header_title'] = "Edit Subject";
            return view('admin.subject.edit', $data);
        } else {
            abort(404);
        }
    }

    public function update($id, Request $request)
    {
        $save = SubjectModel::getSingle($id);
        $save->name = trim($request->name);
        $save->type = trim($request->type);
        $save->status = trim($request->status);
        $save->save();

        return redirect('admin/subject/list')->with('success', "Subject Sucessfully Updated");
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
