<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassModel;
use App\Models\User;
use App\Models\AssignClassTeacherModel;
use App\Models\ClassSubjectModel;
use App\Models\SubjectModel;
use Illuminate\Support\Facades\Auth;


class AssignClassTeacherController extends Controller
{
    public function list(Request $request)
    {

        $data['getRecord'] = AssignClassTeacherModel::getRecord();
        $data['header_title'] = "Assign Class Teacher";
        return view('admin.assign_class_teacher.list', $data);
    }

    public function add(Request $request)
    {
        $data['getClass'] = ClassModel::getClass();
        $data['getTeacher'] = User::getTeacherClass();

        $data['header_title'] = "Add Assign Class Teacher";
        return view('admin.assign_class_teacher.add', $data);
    }

    // public function insert(Request $request)
    // {

    //     if (!empty($request->teacher_id)) {
    //         foreach ($request->teacher_id as $teacher_id) {
    //             $getAlreadyFirst = AssignClassTeacherModel::getAlreadyFirst($request->class_id, $teacher_id);
    //             if (!empty($getAlreadyFirst)) {
    //                 $getAlreadyFirst->status = $request->status;
    //                 $getAlreadyFirst->save();
    //             } else {
    //                 $save = new AssignClassTeacherModel;
    //                 $save->class_id = $request->class_id;
    //                 $save->teacher_id = $teacher_id;
    //                 $save->status = $request->status;
    //                 $save->created_by = Auth::user()->id;
    //                 $save->save();
    //             }
    //         }

    //         return redirect('admin/assign_class_teacher/list')->with('success', "Assign Class to Teacher Successfully");
    //     } else {
    //         return redirect()->back()->with('error', 'Due to some error pls try again');
    //     }
    // }

    public function insert(Request $request)
    {
        try {
            if (!empty($request->teacher_id)) {
                foreach ($request->teacher_id as $teacher_id) {
                    $getAlreadyFirst = AssignClassTeacherModel::getAlreadyFirst($request->class_id, $teacher_id);
                    if (!empty($getAlreadyFirst)) {
                        $getAlreadyFirst->status = $request->status;
                        $getAlreadyFirst->save();
                    } else {
                        // Enregistrement dans la table AssignClassTeacherModel
                        $save = new AssignClassTeacherModel;
                        $save->class_id = $request->class_id;
                        $save->teacher_id = $teacher_id;
                        $save->status = $request->status;
                        $save->created_by = Auth::user()->id;
                        $save->save();
                    }
                }

                // Répondre avec les IDs pour la redirection
                return response()->json([
                    'success' => true,
                    'teacher_id' => $teacher_id,
                    'class_id' => $request->class_id,
                ]);
            } else {
                return response()->json(['error' => 'Veuillez sélectionner au moins un enseignant.'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Une erreur est survenue lors de l\'assignation.'], 500);
        }
    }

    public function edit($id)
    {
        $getRecord = AssignClassTeacherModel::getSingle($id);
        if (!empty($getRecord)) {
            $data['getRecord'] = $getRecord;
            $data['getAssignTeacherID'] = AssignClassTeacherModel::getAssignTeacherID($getRecord->class_id);
            $data['getClass'] = ClassModel::getClass();
            $data['getTeacher'] = User::getTeacherClass();
            $data['header_title'] = "Edit Assign Class Teacher";
            return view('admin.assign_class_teacher.edit', $data);
        } else {
            abort(404);
        }
    }

    public function update($id, Request $request)
    {
        AssignClassTeacherModel::deleteTeacher($request->class_id);


        if (!empty($request->teacher_id)) {
            foreach ($request->teacher_id as $teacher_id) {
                $getAlreadyFirst = AssignClassTeacherModel::getAlreadyFirst($request->class_id, $teacher_id);
                if (!empty($getAlreadyFirst)) {
                    $getAlreadyFirst->status = $request->status;
                    $getAlreadyFirst->save();
                } else {
                    $save = new AssignClassTeacherModel;
                    $save->class_id = $request->class_id;
                    $save->teacher_id = $teacher_id;
                    $save->status = $request->status;
                    $save->created_by = Auth::user()->id;
                    $save->save();
                }
            }
        }

        return redirect('admin/assign_class_teacher/list')->with('success', "Assign Class to Teacher Successfully");
    }

    public function edit_single($id)
    {
        $getRecord = AssignClassTeacherModel::getSingle($id);
        if (!empty($getRecord)) {
            $data['getRecord'] = $getRecord;
            $data['getClass'] = ClassModel::getClass();
            $data['getTeacher'] = User::getTeacherClass();
            $data['header_title'] = "Edit Assign Class Teacher";
            return view('admin.assign_class_teacher.edit_single', $data);
        } else {
            abort(404);
        }
    }



    public function update_single($id, Request $request)
    {

        $getAlreadyFirst = AssignClassTeacherModel::getAlreadyFirst($request->class_id, $request->teacher_id);
        if (!empty($getAlreadyFirst)) {
            $getAlreadyFirst->status = $request->status;
            $getAlreadyFirst->save();

            return redirect('admin/assign_class_teacher/list')->with('success', "Status Successfully Updated");
        } else {
            $save = AssignClassTeacherModel::getSingle($id);
            $save->class_id = $request->class_id;
            $save->teacher_id = $request->teacher_id;
            $save->status = $request->status;
            $save->save();

            return redirect('admin/assign_class_teacher/list')->with('success', "Assign Class to Teacher Successfully Updated");
        }
    }


    public function delete($id)
    {
        $save = AssignClassTeacherModel::getSingle($id);
        $save->delete();

        return redirect()->back()->with('success', "Assign Class to Teacher Successfully Deleted");
    }

    // Assignation des cours aux enseignants
    public function assign_subject(Request $request)
    {

        // Récupérer tous les enseignants
        $teachers = User::getTeacherClass();

        // Récupérer toutes les matières
        $subjects = SubjectModel::all();

        // Vérifier que les données sont bien récupérées
        if ($teachers->isEmpty() || $subjects->isEmpty()) {
            return redirect()->back()->with('error', 'Aucun enseignant ou matière disponible.');
        }

        // Passer les données à la vue
        return view('admin.assign_class_teacher.assign_subject_subject', compact('teachers', 'subjects'));
    }



    // public function insert_assign_subject(Request $request)
    // {
    //     // Validation des données envoyées depuis le formulaire
    //     $request->validate([
    //         'teacher_id' => 'required|integer',
    //         'subject_ids' => 'required|array',
    //         'subject_ids.*' => 'integer',
    //     ]);

    //     $teacher_id = $request->input('teacher_id');
    //     $subject_ids = $request->input('subject_ids');

    //     // Récupérer la classe assignée à l'enseignant
    //     $teacherAssignment = AssignClassTeacherModel::where('teacher_id', $teacher_id)->first();

    //     if (!$teacherAssignment) {
    //         return redirect()->back()->with('error', 'Aucune classe assignée à cet enseignant.');
    //     }

    //     // Récupérer la classe et le statut automatiquement
    //     $class_id = $teacherAssignment->class_id; // ID de la classe assignée
    //     $status = $teacherAssignment->status;     // Statut assigné à l'enseignant

    //     // On boucle sur les matières à assigner
    //     foreach ($subject_ids as $subject_id) {
    //         // Vérifier que le subject_id appartient bien à la class_id
    //         $isSubjectInClass = ClassSubjectModel::where('class_id', $class_id)
    //             ->where('subject_id', $subject_id)
    //             ->exists();

    //         if (!$isSubjectInClass) {
    //             // Si la matière n'appartient pas à la classe, on passe à la suivante
    //             continue;
    //         }

    //         // Vérifier si l'assignation existe déjà pour cette matière
    //         $existingAssignment = AssignClassTeacherModel::where('teacher_id', $teacher_id)
    //             ->where('subject_id', $subject_id)
    //             ->first();

    //         if (!$existingAssignment) {
    //             // Créer l'assignation si elle n'existe pas
    //             AssignClassTeacherModel::create([
    //                 'teacher_id' => $teacher_id,
    //                 'class_id' => $class_id,  // Classe récupérée automatiquement
    //                 'subject_id' => $subject_id,
    //                 'status' => $status,      // Statut récupéré automatiquement
    //                 'is_delete' => 0,
    //                 'created_by' => Auth::user()->id,
    //             ]);
    //         }
    //     }

    //     return redirect()->back();
    // }

    public function insert_assign_subject(Request $request)
    {
        // Validation des données envoyées depuis le formulaire

        $teacher_id = $request->input('teacher_id');
        $subject_ids = $request->input('subject_ids');

        // Récupérer la classe assignée à l'enseignant
        $teacherAssignment = AssignClassTeacherModel::where('teacher_id', $teacher_id)->first();

        if (!$teacherAssignment) {
            return response()->json(['error' => 'Aucune classe assignée à cet enseignant.'], 400);
        }

        $class_id = $teacherAssignment->class_id; // ID de la classe assignée
        $status = $teacherAssignment->status;     // Statut assigné à l'enseignant

        foreach ($subject_ids as $subject_id) {
            // Vérifier si la matière appartient à la classe
            $isSubjectInClass = ClassSubjectModel::where('class_id', $class_id)
                ->where('subject_id', $subject_id)
                ->exists();

            if (!$isSubjectInClass) {
                // Retourner un message d'erreur si la matière n'appartient pas à la classe
                return response()->json(['error' => 'Le cours sélectionné n\'appartient pas à la classe.'], 400);
            }

            // Vérifier si l'assignation existe déjà pour cette matière
            $existingAssignment = AssignClassTeacherModel::where('teacher_id', $teacher_id)
                ->where('subject_id', $subject_id)
                ->first();

            if (!$existingAssignment) {
                // Créer l'assignation si elle n'existe pas
                AssignClassTeacherModel::create([
                    'teacher_id' => $teacher_id,
                    'class_id' => $class_id,  // Classe récupérée automatiquement
                    'subject_id' => $subject_id,
                    'status' => $status,      // Statut récupéré automatiquement
                    'is_delete' => 0,
                    'created_by' => Auth::user()->id,
                ]);
            } else {
                return response()->json(['error' => 'existe deja'], 400);
            }
        }

        return response()->json(['success' => 'Matières assignées avec succès à l\'enseignant.']);
    }

    public function assign_subject1($teacher_id, $class_id)
    {
        // Récupérer les matières associées à la classe
        $subjects = ClassSubjectModel::where('class_id', $class_id)->get();

        // Vérifiez si les sujets existent
        if ($subjects->isEmpty()) {
            return redirect()->back()->with('error', 'Aucun sujet trouvé pour cette classe.');
        }

        // Récupérer l'enseignant
        $teacher = User::find($teacher_id);
        if (!$teacher) {
            return redirect()->back()->with('error', 'Enseignant non trouvé.');
        }

        return view('admin.assign_class_teacher.assign_subject_subject1', [
            'subjects' => $subjects,
            'teacher_id' => $teacher_id,
            'class_id' => $class_id,
        ]);
    }

    public function insert_assign_subject1(Request $request)
    {
        // Récupérer les IDs depuis le formulaire
        $subject_ids = $request->input('subject_ids');
        $teacher_id = $request->input('teacher_id'); // ID de l'enseignant
        $class_id = $request->input('class_id'); // ID de la classe
        $status = $request->input('status');

        // Vérifier si des matières ont été sélectionnées
        if (empty($subject_ids)) {
            return response()->json(['error' => 'Veuillez sélectionner au moins une matière.'], 400);
        }

        foreach ($subject_ids as $subject_id) {
            // Vérifier si la matière appartient à la classe
            $isSubjectInClass = ClassSubjectModel::where('class_id', $class_id)
                ->where('subject_id', $subject_id)
                ->exists();

            if (!$isSubjectInClass) {
                // Retourner un message d'erreur si la matière n'appartient pas à la classe
                return response()->json(['error' => 'Le cours sélectionné n\'appartient pas à la classe.'], 400);
            }

            $status = AssignClassTeacherModel::where('teacher_id', $teacher_id)
            ->where('class_id', $class_id)
            ->value('status');


            // Vérifier si l'assignation existe déjà pour cette matière
            $existingAssignment = AssignClassTeacherModel::where('teacher_id', $teacher_id)
                ->where('subject_id', $subject_id)
                ->first();

            if (!$existingAssignment) {
                // Créer l'assignation si elle n'existe pas
                AssignClassTeacherModel::create([
                    'teacher_id' => $teacher_id,
                    'class_id' => $class_id,  // Classe récupérée depuis les paramètres
                    'subject_id' => $subject_id,
                    'status' => $status, // Ajuste le statut selon tes besoins
                    'is_delete' => 0,
                    'created_by' => Auth::user()->id,
                ]);
            } else {
                return response()->json(['error' => 'Cette matière est déjà assignée à l\'enseignant.'], 400);
            }
        }

        return response()->json(['success' => 'Matières assignées avec succès à l\'enseignant.']);
    }

    // teacher side work

    public function MyClassSubject()
    {
        $data['getRecord'] = AssignClassTeacherModel::getMyClassSubject(Auth::user()->id);
        $data['header_title'] = "My Class & Subject";
        return view('teacher.my_class_subject', $data);
    }
}
