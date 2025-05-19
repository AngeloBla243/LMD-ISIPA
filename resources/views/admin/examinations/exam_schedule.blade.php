@extends('layouts.app')
@section('style')
    <style type="text/css">
        .styled-table {
            border-collapse: collapse;
            margin: 25px 0;
            min-width: 400px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
            border-radius: 8px;
            overflow: hidden;
        }

        .styled-table thead tr {
            background-color: #009879;
            color: #ffffff;
            text-align: left;
        }

        .styled-table th,
        .styled-table td {
            padding: 12px 15px;
        }

        .styled-table tbody tr {
            border-bottom: 1px solid #dddddd;
        }

        .styled-table tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }

        .styled-table tbody tr:last-of-type {
            border-bottom: 2px solid #009879;
        }

        /* Effet survol (hover) */
        .styled-table tbody tr:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }
    </style>
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="h3 fw-bold text-primary">Exam Schedule</h1>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content pb-5">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">

                        <!-- Search Card -->
                        <div class="card shadow-sm rounded-4 border-0 mb-4">
                            <div class="card-header bg-primary text-white rounded-top-4">
                                <h3 class="card-title mb-0">
                                    <i class="fa-solid fa-magnifying-glass me-2"></i>Search Exam Schedule
                                </h3>
                            </div>
                            <form method="get" action="">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Année Académique</label>
                                            <select class="form-select form-control" name="academic_year_id" required
                                                onchange="this.form.submit()">
                                                <option value="">Sélectionner</option>
                                                @foreach ($academicYears as $year)
                                                    <option value="{{ $year->id }}"
                                                        {{ Request::get('academic_year_id') == $year->id ? 'selected' : '' }}>
                                                        {{ $year->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Classe</label>
                                            <select class="form-select form-control" name="class_id" required
                                                {{ empty($filteredClasses) ? 'disabled' : '' }}>
                                                <option value="">Sélectionner</option>
                                                @foreach ($filteredClasses as $class)
                                                    <option value="{{ $class->id }}"
                                                        {{ Request::get('class_id') == $class->id ? 'selected' : '' }}>
                                                        {{ $class->name }} {{ $class->opt }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Examen</label>
                                            <select class="form-select form-control" name="exam_id" required
                                                {{ empty($filteredExams) ? 'disabled' : '' }}>
                                                <option value="">Sélectionner</option>
                                                @foreach ($filteredExams as $exam)
                                                    <option value="{{ $exam->id }}"
                                                        {{ Request::get('exam_id') == $exam->id ? 'selected' : '' }}>
                                                        {{ $exam->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 d-flex align-items-end gap-2">
                                            <button class="btn btn-primary w-100" type="submit">
                                                <i class="fa-solid fa-magnifying-glass me-1"></i> Rechercher
                                            </button>
                                            <a href="{{ url('admin/examinations/exam_schedule') }}"
                                                class="btn btn-success w-100">
                                                Réinitialiser
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        @include('_message')

                        @if (!empty($getRecord))
                            <form action="{{ url('admin/examinations/exam_schedule_insert') }}" method="post">
                                @csrf
                                <input type="hidden" name="exam_id" value="{{ Request::get('exam_id') }}">
                                <input type="hidden" name="class_id" value="{{ Request::get('class_id') }}">
                                <input type="hidden" name="academic_year_id" value="{{ $selectedAcademicYearId }}">

                                <div class="card shadow-sm rounded-4 border-0">
                                    <div class="card-header bg-primary text-white rounded-top-4">
                                        <h3 class="card-title mb-0">
                                            <i class="fa-solid fa-calendar-days me-2"></i>Exam Schedule
                                        </h3>
                                    </div>
                                    <div class="card-body p-0 table-responsive">
                                        <table class="table table-hover table-bordered align-middle mb-0">
                                            <thead class="table-primary text-center text-uppercase small">
                                                <tr>
                                                    <th>Subject Name</th>
                                                    <th>Exam Date</th>
                                                    <th>Start Time</th>
                                                    <th>End Time</th>
                                                    <th>Room Number</th>
                                                    <th>Full Marks</th>
                                                    <th>Passing Marks</th>
                                                    <th>Ponderation</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $i = 1; @endphp
                                                @foreach ($getRecord as $value)
                                                    <tr>
                                                        <td style="min-width: 200px;">
                                                            {{ $value['subject_name'] }}
                                                            <input type="hidden" class="form-control"
                                                                value="{{ $value['subject_id'] }}"
                                                                name="schedule[{{ $i }}][subject_id]">
                                                        </td>
                                                        <td>
                                                            <input type="date" class="form-control"
                                                                value="{{ $value['exam_date'] }}"
                                                                name="schedule[{{ $i }}][exam_date]">
                                                        </td>
                                                        <td>
                                                            <input type="time" class="form-control"
                                                                value="{{ $value['start_time'] }}"
                                                                name="schedule[{{ $i }}][start_time]">
                                                        </td>
                                                        <td>
                                                            <input type="time" class="form-control"
                                                                value="{{ $value['end_time'] }}"
                                                                name="schedule[{{ $i }}][end_time]">
                                                        </td>
                                                        <td>
                                                            <input type="text" style="width: 140px;"
                                                                value="{{ $value['room_number'] }}" class="form-control"
                                                                name="schedule[{{ $i }}][room_number]">
                                                        </td>
                                                        <td>
                                                            <input type="number" style="width: 120px;"
                                                                value="{{ $value['full_marks'] }}" class="form-control"
                                                                name="schedule[{{ $i }}][full_marks]">
                                                        </td>
                                                        <td>
                                                            <input type="number" style="width: 120px;"
                                                                value="{{ $value['passing_mark'] }}" class="form-control"
                                                                name="schedule[{{ $i }}][passing_mark]">
                                                        </td>
                                                        <td>
                                                            <input type="text" style="width: 120px;"
                                                                value="{{ $value['ponde'] }}" class="form-control"
                                                                name="schedule[{{ $i }}][ponde]">
                                                        </td>
                                                    </tr>
                                                    @php $i++; @endphp
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="text-center py-4">
                                            <button class="btn btn-primary px-5 py-2 fw-semibold shadow-sm"
                                                type="submit">
                                                <i class="fas fa-save me-2"></i> Enregistrer le planning
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @endif

                    </div>
                </div>
            </div>
        </section>
    </div>

    <style>
        .card {
            border-radius: 1.25rem;
        }

        .card-header {
            border-radius: 1.25rem 1.25rem 0 0;
        }

        .table-primary th {
            background-color: #cfe2ff !important;
            color: #084298 !important;
            font-weight: 600;
        }

        .btn-primary,
        .btn-success {
            font-weight: 500;
        }

        .btn-sm {
            padding: 0.25rem 0.75rem;
            font-size: 0.9rem;
        }

        .form-label {
            font-weight: 600;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, .25);
        }
    </style>

@endsection
