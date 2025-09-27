<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassModel;
use App\Models\ClassSubjectModel;
use App\Models\ClassSubjectTimetableModel;
use App\Models\AcademicYear;
use App\Models\WeekModel;
use Illuminate\Support\Facades\Auth;
use App\Models\ExamScheduleModel;
use App\Models\ExamModel;

class DepartementClassTimetableController extends Controller
{
    public function list(Request $request)
    {
        $departmentId = Auth::user()->department_id;

        // Charger années académiques
        $data['academicYears'] = AcademicYear::orderBy('start_date', 'desc')->get();

        // Charger classes filtrées par département et année si précisé
        $classesQuery = ClassModel::where('department_id', $departmentId)->where('is_delete', 0);
        if ($request->filled('academic_year_id')) {
            $classesQuery->where('academic_year_id', $request->academic_year_id);
        }
        $data['getClass'] = $classesQuery->get();

        // Charger matières si classe sélectionnée
        if ($request->filled('class_id')) {
            $data['getSubject'] = ClassSubjectModel::where('class_id', $request->class_id)->get();

            // Vérifier cohérence année <-> classe
            $selectedClass = ClassModel::find($request->class_id);
            if ($selectedClass && $request->filled('academic_year_id') && $selectedClass->academic_year_id != $request->academic_year_id) {
                return redirect()->back()->with('error', "La classe ne correspond pas à l'année sélectionnée");
            }
        }

        // Préparer plages horaires de la semaine avec éventuelles données existantes
        $weeks = WeekModel::all();
        $data['week'] = [];
        foreach ($weeks as $week) {
            $entry = [
                'week_id' => $week->id,
                'week_name' => $week->name,
                'start_time' => '',
                'end_time' => '',
                'room_number' => '',
            ];
            if ($request->filled('class_id') && $request->filled('subject_id')) {
                $timetable = ClassSubjectTimetableModel::where('class_id', $request->class_id)
                    ->where('subject_id', $request->subject_id)
                    ->where('week_id', $week->id)
                    ->first();
                if ($timetable) {
                    $entry['start_time'] = $timetable->start_time;
                    $entry['end_time'] = $timetable->end_time;
                    $entry['room_number'] = $timetable->room_number;
                }
            }
            $data['week'][] = $entry;
        }

        $data['header_title'] = "Emploi du temps des classes (Département)";
        return view('departement.class_timetable.list', $data);
    }

    public function getClassesByYear($yearId)
    {
        $departmentId = Auth::user()->department_id;

        try {
            $classes = ClassModel::where('academic_year_id', $yearId)
                ->where('department_id', $departmentId)
                ->where('is_delete', 0)
                ->get(['id', 'name', 'opt']);

            return response()->json([
                'success' => true,
                'data' => $classes->map(function ($class) {
                    return [
                        'id' => $class->id,
                        'name' => $class->name . ' (' . ($class->opt ?? '') . ')'
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

    public function getSubject(Request $request)
    {
        $subjects = ClassSubjectModel::select('subject.id as subject_id', 'subject.name as subject_name')
            ->join('subject', 'class_subject.subject_id', '=', 'subject.id')
            ->where('class_subject.class_id', $request->class_id)
            ->get();

        $html = "<option value=''>Sélectionner une matière</option>";
        foreach ($subjects as $subject) {
            $html .= "<option value='" . $subject->subject_id . "'>" . $subject->subject_name . "</option>";
        }
        return response()->json(['html' => $html]);
    }


    public function insert_update(Request $request)
    {
        ClassSubjectTimetableModel::where('class_id', $request->class_id)
            ->where('subject_id', $request->subject_id)
            ->delete();

        foreach ($request->timetable as $timetable) {
            if (!empty($timetable['week_id']) && !empty($timetable['start_time']) && !empty($timetable['end_time']) && !empty($timetable['room_number'])) {
                ClassSubjectTimetableModel::create([
                    'class_id' => $request->class_id,
                    'subject_id' => $request->subject_id,
                    'week_id' => $timetable['week_id'],
                    'start_time' => $timetable['start_time'],
                    'end_time' => $timetable['end_time'],
                    'room_number' => $timetable['room_number'],
                ]);
            }
        }

        return redirect()->back()->with('success', 'Emploi du temps enregistré avec succès');
    }

    // PLANNING EXAMEN

    public function examSchedule(Request $request)
    {
        $departmentId = Auth::user()->department_id;

        $data['academicYears'] = AcademicYear::orderBy('start_date', 'desc')->get();

        // Classes du département filtrées par année académique si donnée
        $filteredClasses = collect();
        $filteredExams = collect();
        $selectedAcademicYearId = $request->get('academic_year_id');

        if ($selectedAcademicYearId) {
            $filteredClasses = ClassModel::where('department_id', $departmentId)
                ->where('academic_year_id', $selectedAcademicYearId)->get();

            $filteredExams = ExamModel::where('academic_year_id', $selectedAcademicYearId)->get();
        }

        $result = [];

        if (!empty($request->get('exam_id')) && !empty($request->get('class_id'))) {
            $getSubject = ClassSubjectModel::where('class_id', $request->get('class_id'))->get();

            foreach ($getSubject as $value) {
                $dataS = [];
                $dataS['subject_id'] = $value->subject_id;
                $dataS['class_id'] = $value->class_id;
                $dataS['subject_name'] = $value->subject_name;
                $dataS['subject_type'] = $value->subject_type;

                $ExamSchedule = ExamScheduleModel::where('exam_id', $request->get('exam_id'))
                    ->where('class_id', $request->get('class_id'))
                    ->where('subject_id', $value->subject_id)
                    ->first();

                if ($ExamSchedule) {
                    $dataS['exam_date'] = $ExamSchedule->exam_date;
                    $dataS['start_time'] = $ExamSchedule->start_time;
                    $dataS['end_time'] = $ExamSchedule->end_time;
                    $dataS['room_number'] = $ExamSchedule->room_number;
                    $dataS['full_marks'] = $ExamSchedule->full_marks;
                    $dataS['passing_mark'] = $ExamSchedule->passing_mark;
                    $dataS['ponde'] = $ExamSchedule->ponde;
                } else {
                    $dataS['exam_date'] = '';
                    $dataS['start_time'] = '';
                    $dataS['end_time'] = '';
                    $dataS['room_number'] = '';
                    $dataS['full_marks'] = '';
                    $dataS['passing_mark'] = '';
                    $dataS['ponde'] = '';
                }
                $result[] = $dataS;
            }
        }

        $data['getRecord'] = $result;
        $data['filteredClasses'] = $filteredClasses;
        $data['filteredExams'] = $filteredExams;
        $data['selectedAcademicYearId'] = $selectedAcademicYearId;
        $data['header_title'] = "Planning des Examens (Département)";

        return view('departement.exam_schedule', $data);
    }

    public function examScheduleInsert(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:exam,id',
            'class_id' => 'required|exists:class,id',
            'academic_year_id' => 'required|exists:academic_years,id'
        ]);

        $academicYearId = $request->academic_year_id;

        ExamScheduleModel::where('exam_id', $request->exam_id)
            ->where('class_id', $request->class_id)
            ->delete();

        if (!empty($request->schedule)) {
            foreach ($request->schedule as $schedule) {
                if (!empty($schedule['subject_id']) && !empty($schedule['exam_date']) && !empty($schedule['start_time']) && !empty($schedule['end_time']) && !empty($schedule['room_number']) && !empty($schedule['full_marks']) && !empty($schedule['passing_mark'])) {
                    ExamScheduleModel::create([
                        'exam_id' => $request->exam_id,
                        'class_id' => $request->class_id,
                        'subject_id' => $schedule['subject_id'],
                        'exam_date' => $schedule['exam_date'],
                        'start_time' => $schedule['start_time'],
                        'end_time' => $schedule['end_time'],
                        'room_number' => $schedule['room_number'],
                        'full_marks' => $schedule['full_marks'],
                        'passing_mark' => $schedule['passing_mark'],
                        'ponde' => $schedule['ponde'],
                        'academic_year_id' => $academicYearId,
                        'created_by' => Auth::id(),
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Planning des examens enregistré avec succès');
    }
}
