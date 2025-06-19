<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


use MacsiDigital\Zoom\Facades\Zoom;
use App\Models\AcademicYear;
use App\Models\{Meeting, ClassModel, AssignClassTeacherModel};
use Carbon\Carbon;

class MeetingController extends Controller
{

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
            'duration' => 'required|integer|min:15',
            'agenda' => 'nullable|string|max:1000',
        ]);

        // Récupérer l'année académique actuelle depuis la session
        $academicYearId = session('academic_year_id', AcademicYear::active()->value('id'));
        // Si pour une raison quelconque academicYearId est null, il faut gérer l'erreur ou forcer une valeur par défaut.
        // Pour l'instant, on assume qu'il est toujours présent.

        try {
            $zoomMeeting = Zoom::meeting()->create([
                'topic' => $request->topic,
                'type' => 2, // Scheduled meeting
                'start_time' => Carbon::parse($request->start_time)->toIso8601String(),
                'duration' => $request->duration,
                // 'agenda' n'est généralement pas un champ direct de l'API Zoom pour la création,
                // il est géré dans votre propre base de données.
                'settings' => [
                    'join_before_host' => false,
                    'waiting_room' => true
                ]
            ]);

            // Assurez-vous de récupérer l'URL de jointure depuis la réponse de Zoom
            $joinUrl = $zoomMeeting->join_url ?? null;
        } catch (\Exception $e) {
            // Log l'erreur pour un débogage plus facile
            \Log::error("Zoom Meeting Creation Error: " . $e->getMessage());
            return back()->withErrors(['zoom_error' => 'Erreur lors de la création de la réunion Zoom : ' . $e->getMessage()]);
        }

        Meeting::create([
            'class_id' => $request->class_id,
            'teacher_id' => auth()->id(),
            'academic_year_id' => $academicYearId, // <-- AJOUTÉ
            'zoom_meeting_id' => $zoomMeeting->id,
            'topic' => $request->topic,
            'start_time' => $request->start_time,
            'duration' => $request->duration,
            'agenda' => $request->agenda, // <-- MAINTENU POUR VOTRE DB
            'join_url' => $joinUrl,      // <-- AJOUTÉ
        ]);

        return redirect()->route('teacher.meetings.list')
            ->with('success', 'Réunion créée avec succès');
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'class_id' => 'required|exists:class,id',
    //         'topic' => 'required|string|max:255',
    //         'start_time' => 'required|date',
    //         'duration' => 'required|integer|min:15',
    //         'agenda' => 'nullable|string|max:1000',
    //     ]);

    //     try {
    //         $zoomMeeting = Zoom::meeting()->create([
    //             'topic' => $request->topic,
    //             'type' => 2, // Scheduled meeting
    //             'start_time' => \Carbon\Carbon::parse($request->start_time)->toIso8601String(),
    //             'duration' => $request->duration,
    //             'agenda' => $request->agenda,
    //             'settings' => [
    //                 'join_before_host' => false,
    //                 'waiting_room' => true
    //             ]
    //         ]);
    //     } catch (\Exception $e) {
    //         return back()->withErrors(['zoom_error' => 'Erreur lors de la création de la réunion Zoom : ' . $e->getMessage()]);
    //     }

    //     Meeting::create([
    //         'class_id' => $request->class_id,
    //         'teacher_id' => auth()->id(),
    //         'zoom_meeting_id' => $zoomMeeting->id,
    //         'topic' => $request->topic,
    //         'start_time' => $request->start_time,
    //         'duration' => $request->duration,
    //         'agenda' => $request->agenda,
    //     ]);

    //     return redirect()->route('teacher.meetings.list')
    //         ->with('success', 'Réunion créée avec succès');
    // }



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
