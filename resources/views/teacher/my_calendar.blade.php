@extends('layouts.app')
@section('style')

<style type="text/css">
    .fc-daygrid-event {
        white-space: normal;
        font-size: 10px; /* Taille de police pour les événements */
        word-wrap: break-word; /* Permet de casser les mots longs */
    }

    /* Ajustements pour le calendrier */
    #calendar {
        font-size: 1rem; /* Taille de police par défaut pour le calendrier */
    }

    /* Ajustements pour les petits écrans */
    @media (max-width: 768px) {
        #calendar {
            font-size: 0.9rem; /* Réduction de la taille de police */
        }
    }

    @media (max-width: 480px) {
        #calendar {
            font-size: 0.8rem; /* Réduction supplémentaire pour les très petits écrans */
        }
    }

    /* Style de base pour le titre de la page */
    .content-header h1 {
        font-size: 1.5rem; /* Taille de police pour le titre */
    }
</style>

@endsection

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>My Calendar</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div id="calendar"></div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
</div>

@endsection

@section('script')
<script src='{{ url('public/dist/fullcalendar/index.global.js') }}'></script>

<script type="text/javascript">
    var events = [];

    @foreach($getClassTimetable as $value)
        events.push({
            title: 'Class: {{ $value->class_name }} - {{ $value->subject_name }}',
            daysOfWeek: [{{ $value->fullcalendar_day }}],
            startTime: '{{ $value->start_time }}',
            endTime: '{{ $value->end_time }}',
        });
    @endforeach

    @foreach($getExamTimetable as $exam)
        events.push({
            title: 'Exam: {{ $exam->class_name }} - {{ $exam->exam_name }} - {{ $exam->subject_name }} ({{ date('h:i A',strtotime($exam->start_time)) }} to {{ date('h:i A',strtotime($exam->end_time)) }})',
            start: '{{ $exam->exam_date }}',
            end: '{{ $exam->exam_date }}',
            color: 'red',
            url: '{{ url('teacher/my_exam_timetable') }}'
        });
    @endforeach

    document.addEventListener('DOMContentLoaded', function() {
        var calendarID = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarID, {
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
            },
            initialDate: '<?= date('Y-m-d') ?>',
            navLinks: true,
            editable: false,
            events: events,
            height: 'auto', // Adapte la hauteur automatiquement
            eventDisplay: 'block' // Affiche les événements en blocs pour une meilleure lisibilité
        });

        calendar.render();
    });
</script>
@endsection
