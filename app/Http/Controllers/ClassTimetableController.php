<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassModel;
use App\Models\SubjectModel;
use App\Models\ClassSubjectModel;
use App\Models\WeekModel;
use App\Models\ClassSubjectTimetableModel;
use App\Models\User;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;



class ClassTimetableController extends Controller
{
    public function list(Request $request)
    {
        // Charger toutes les années académiques pour le filtre
        $data['academicYears'] = AcademicYear::orderBy('start_date', 'desc')->get();

        // Charger les classes filtrées si année sélectionnée
        $classesQuery = ClassModel::where('is_delete', 0);
        if ($request->filled('academic_year_id')) {
            $classesQuery->where('academic_year_id', $request->academic_year_id);
        }
        $data['getClass'] = $classesQuery->get();

        // Charger les matières si une classe est sélectionnée
        if ($request->filled('class_id')) {
            $data['getSubject'] = ClassSubjectModel::MySubjectAdmin($request->class_id);

            // Vérifier la cohérence entre classe et année sélectionnée
            $selectedClass = ClassModel::find($request->class_id);
            if ($selectedClass && $request->filled('academic_year_id') && $selectedClass->academic_year_id != $request->academic_year_id) {
                return redirect()->back()->with('error', 'La classe ne correspond pas à l\'année sélectionnée');
            }
        }

        // Préparer les plages horaires de la semaine
        $weeks = WeekModel::getRecord();
        $data['week'] = [];

        foreach ($weeks as $week) {
            $entry = [
                'week_id' => $week->id,
                'week_name' => $week->name,
                'start_time' => '',
                'end_time' => '',
                'room_number' => ''
            ];

            if ($request->filled('class_id') && $request->filled('subject_id')) {
                $timetable = ClassSubjectTimetableModel::getRecordClassSubject($request->class_id, $request->subject_id, $week->id);
                if ($timetable) {
                    $entry['start_time'] = $timetable->start_time;
                    $entry['end_time'] = $timetable->end_time;
                    $entry['room_number'] = $timetable->room_number;
                }
            }
            $data['week'][] = $entry;
        }

        $data['header_title'] = "Emploi du temps des classes";

        return view('admin.class_timetable.list', $data);
    }



    // Méthode API pour le chargement dynamique
    public function getClassesByYear($yearId)
    {
        try {
            $classes = ClassModel::with('academicYear')
                ->where('academic_year_id', $yearId)
                ->where('is_delete', 0)
                ->get(['id', 'name', 'opt', 'academic_year_id']);

            return response()->json([
                'success' => true,
                'data' => $classes->map(function ($class) {
                    return [
                        'id' => $class->id,
                        'name' => $class->name . ' (' . ($class->academicYear->name ?? 'N/A') . ')'
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du chargement des classes'
            ], 500);
        }
    }



    public function get_subject(Request $request)
    {
        $getSubject = ClassSubjectModel::MySubjectAdmin($request->class_id);

        $html = "<option value=''>Select</option>";

        foreach ($getSubject as $value) {
            $html .= "<option value='" . $value->subject_id . "'>" . $value->subject_name . "</option>";
        }

        $json['html'] = $html;
        echo json_encode($json);
    }

    public function insert_update(Request $request)
    {
        ClassSubjectTimetableModel::where('class_id', '=', $request->class_id)->where('subject_id', '=', $request->subject_id)->delete();

        foreach ($request->timetable as $timetable) {
            if (!empty($timetable['week_id']) && !empty($timetable['start_time']) && !empty($timetable['end_time']) && !empty($timetable['room_number'])) {
                $save = new ClassSubjectTimetableModel;
                $save->class_id = $request->class_id;
                $save->subject_id = $request->subject_id;
                $save->week_id = $timetable['week_id'];
                $save->start_time = $timetable['start_time'];
                $save->end_time = $timetable['end_time'];
                $save->room_number = $timetable['room_number'];
                $save->save();;
            }
        }

        return redirect()->back()->with('success', "Class Timetable Successfully Saved");
    }


    // student side



    public function MyTimetable()
    {
        $result = array();

        // 1. Récupérer l'année académique
        $academicYearId = session('academic_year_id', AcademicYear::where('is_active', 1)->value('id'));

        // 2. Trouver la classe de l'étudiant pour cette année
        $studentClass = DB::table('student_class')
            ->where('student_id', Auth::id())
            ->where('academic_year_id', $academicYearId)
            ->first();

        if (!$studentClass) {
            return redirect()->back()->with('error', 'Aucune classe assignée pour cette année académique');
        }

        $classId = $studentClass->class_id;

        // 3. Récupérer les matières avec filtre académique
        $getRecord = ClassSubjectModel::MySubject($classId, $academicYearId);

        foreach ($getRecord as $value) {
            $dataS['name'] = $value->subject_name;

            $getWeek = WeekModel::getRecord();
            $week = array();

            foreach ($getWeek as $valueW) {
                $dataW = array();
                $dataW['week_name'] = $valueW->name;

                // 4. Récupération des horaires avec vérification académique
                $ClassSubject = ClassSubjectTimetableModel::getRecordClassSubject(
                    $classId,
                    $value->subject_id,
                    $valueW->id
                );

                $dataW['start_time'] = $ClassSubject->start_time ?? 'N/A';
                $dataW['end_time'] = $ClassSubject->end_time ?? 'N/A';
                $dataW['room_number'] = $ClassSubject->room_number ?? 'N/A';

                $week[] = $dataW;
            }

            $dataS['week'] = $week;
            $result[] = $dataS;
            // dd($result);
        }

        // 5. Données pour le filtre
        $data['academicYears'] = AcademicYear::orderBy('start_date', 'desc')->get();
        $data['selectedAcademicYear'] = AcademicYear::find($academicYearId);
        $data['getRecord'] = $result;
        $data['header_title'] = "Mon Emploi du Temps";

        return view('student.my_timetable', $data);
    }



    // teacher side

    public function MyTimetableTeacher($class_id, $subject_id)
    {
        $data['getClass'] = ClassModel::getSingle($class_id);
        $data['getSubject'] = SubjectModel::getSingle($subject_id);

        $getWeek = WeekModel::getRecord();
        $week = array();
        foreach ($getWeek as $valueW) {
            $dataW = array();
            $dataW['week_name'] = $valueW->name;

            $ClassSubject = ClassSubjectTimetableModel::getRecordClassSubject($class_id, $subject_id, $valueW->id);

            if (!empty($ClassSubject)) {
                $dataW['start_time'] = $ClassSubject->start_time;
                $dataW['end_time'] = $ClassSubject->end_time;
                $dataW['room_number'] = $ClassSubject->room_number;
            } else {
                $dataW['start_time'] = '';
                $dataW['end_time'] = '';
                $dataW['room_number'] = '';
            }

            $result[] = $dataW;
        }


        $data['getRecord'] = $result;

        $data['header_title'] = "My Timetable";
        return view('teacher.my_timetable', $data);
    }


    // parent side


    public function MyTimetableParent($class_id, $subject_id, $student_id)
    {
        $data['getClass'] = ClassModel::getSingle($class_id);
        $data['getSubject'] = SubjectModel::getSingle($subject_id);
        $data['getStudent'] = User::getSingle($student_id);

        $getWeek = WeekModel::getRecord();
        $week = array();
        foreach ($getWeek as $valueW) {
            $dataW = array();
            $dataW['week_name'] = $valueW->name;

            $ClassSubject = ClassSubjectTimetableModel::getRecordClassSubject($class_id, $subject_id, $valueW->id);

            if (!empty($ClassSubject)) {
                $dataW['start_time'] = $ClassSubject->start_time;
                $dataW['end_time'] = $ClassSubject->end_time;
                $dataW['room_number'] = $ClassSubject->room_number;
            } else {
                $dataW['start_time'] = '';
                $dataW['end_time'] = '';
                $dataW['room_number'] = '';
            }

            $result[] = $dataW;
        }


        $data['getRecord'] = $result;

        $data['header_title'] = "My Timetable";
        return view('parent.my_timetable', $data);
    }
}
