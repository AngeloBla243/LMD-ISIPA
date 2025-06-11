<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


use MacsiDigital\Zoom\Facades\Zoom;
use App\Models\AcademicYear;
use App\Models\{Meeting, ClassModel, AssignClassTeacherModel};

class MeetingController extends Controller
{
    // public function create()
    // {
    //     $teacherId = auth()->id();
    //     $classes = ClassModel::whereHas('assignTeachers', function ($q) use ($teacherId) {
    //         $q->where('teacher_id', $teacherId);
    //     })->get();

    //     return view('teacher.meetings.create', [
    //         'classes' => $classes,
    //         'header_title' => 'Créer une réunion Zoom'
    //     ]);
    // }

    public function create()
    {
        $teacherId = auth()->id();
        $academicYearId = session('academic_year_id', AcademicYear::active()->value('id'));

        // 1. Récupérer les classes assignées via la table pivot
        $assignedClasses = AssignClassTeacherModel::where([
            'teacher_id' => $teacherId,
            'academic_year_id' => $academicYearId,
            'is_delete' => 0
        ])
            ->with(['class.students' => function ($query) use ($academicYearId) {
                $query->wherePivot('academic_year_id', $academicYearId);
            }])
            ->get();

        // 2. Extraire les objets ClassModel
        $classes = $assignedClasses->map(function ($item) {
            return $item->class;
        })->filter();

        return view('teacher.meetings.create', [
            'classes' => $classes,
            'academicYear' => AcademicYear::find($academicYearId),
            'header_title' => 'Créer une réunion Zoom'
        ]);
    }



    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:class,id',
            'topic' => 'required|string|max:255',
            'start_time' => 'required|date',
            'duration' => 'required|integer|min:15'
        ]);

        // Création de la réunion Zoom
        $zoomMeeting = Zoom::meeting()->create([
            'topic' => $request->topic,
            'type' => 2,
            'start_time' => $request->start_time,
            'duration' => $request->duration,
            'settings' => [
                'join_before_host' => false,
                'waiting_room' => true
            ]
        ]);

        // Enregistrement en base
        Meeting::create([
            'class_id' => $request->class_id,
            'teacher_id' => auth()->id(),
            'zoom_meeting_id' => $zoomMeeting->id,
            'topic' => $request->topic,
            'start_time' => $request->start_time,
            'duration' => $request->duration
        ]);

        return redirect()->route('teacher.meetings.list')
            ->with('success', 'Réunion créée avec succès');
    }

    public function list()
    {
        $meetings = Meeting::with('class')
            ->where('teacher_id', auth()->id())
            ->paginate(10);

        return view('teacher.meetings.list', [
            'meetings' => $meetings,
            'header_title' => 'Mes réunions planifiées'
        ]);
    }
}
