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

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            padding-top: 100px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
        }


        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 10px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
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
                        <h1 class="h3 fw-bold text-primary">Student Attendance</h1>
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
                                    <i class="fa-solid fa-magnifying-glass me-2"></i>Search Student Attendance
                                </h3>
                            </div>
                            <form method="get" action="">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Class</label>
                                            <select class="form-select form-control" name="class_id" id="getClass"
                                                required>
                                                <option value="">Select</option>
                                                @foreach ($getClass as $class)
                                                    <option {{ Request::get('class_id') == $class->id ? 'selected' : '' }}
                                                        value="{{ $class->id }}">{{ $class->name }} {{ $class->opt }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Attendance Date</label>
                                            <input type="date" class="form-control" id="getAttendanceDate"
                                                value="{{ Request::get('attendance_date') }}" required
                                                name="attendance_date">
                                        </div>
                                        <div class="col-md-3 d-flex align-items-end gap-2">
                                            <button class="btn btn-primary w-100" type="submit">
                                                <i class="fa-solid fa-magnifying-glass me-1"></i> Search
                                            </button>
                                            <a href="{{ url('admin/attendance/student') }}" class="btn btn-success w-100">
                                                Reset
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Custom Modal (optionnel) -->
                        <div id="customModal" class="modal fade" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content rounded-4 shadow">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Information</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p id="modalMessage"></p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Fermer</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if (!empty(Request::get('class_id')) && !empty(Request::get('attendance_date')))
                            <div class="card shadow-sm rounded-4 border-0">
                                <div class="card-header bg-primary text-white rounded-top-4">
                                    <h3 class="card-title mb-0">
                                        <i class="fa-solid fa-users me-2"></i>Student List
                                    </h3>
                                </div>
                                <div class="card-body p-0 table-responsive">
                                    <table class="table table-hover table-bordered align-middle mb-0">
                                        <thead class="table-primary text-center text-uppercase small">
                                            <tr>
                                                <th>Student ID</th>
                                                <th>Student Name</th>
                                                <th>Attendance</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (!empty($getStudent) && !empty($getStudent->count()))
                                                @foreach ($getStudent as $value)
                                                    @php
                                                        $attendance_type = '';
                                                        $getAttendance = $value->getAttendance(
                                                            $value->id,
                                                            Request::get('class_id'),
                                                            Request::get('attendance_date'),
                                                        );
                                                        if (!empty($getAttendance->attendance_type)) {
                                                            $attendance_type = $getAttendance->attendance_type;
                                                        }
                                                    @endphp
                                                    <tr>
                                                        <td class="text-center">{{ $value->id }}</td>
                                                        <td>{{ $value->name }} {{ $value->last_name }}</td>
                                                        <td>
                                                            <div class="d-flex flex-wrap gap-3">
                                                                <div class="form-check form-check-inline">
                                                                    <input value="1" type="radio"
                                                                        {{ $attendance_type == '1' ? 'checked' : '' }}
                                                                        id="present_{{ $value->id }}"
                                                                        class="form-check-input SaveAttendance"
                                                                        name="attendance{{ $value->id }}">
                                                                    <label class="form-check-label"
                                                                        for="present_{{ $value->id }}">Present</label>
                                                                </div>
                                                                {{-- <div class="form-check form-check-inline">
                                                                    <input value="2" type="radio"
                                                                        {{ $attendance_type == '2' ? 'checked' : '' }}
                                                                        id="late_{{ $value->id }}"
                                                                        class="form-check-input SaveAttendance"
                                                                        name="attendance{{ $value->id }}">
                                                                    <label class="form-check-label"
                                                                        for="late_{{ $value->id }}">Late</label>
                                                                </div> --}}
                                                                <div class="form-check form-check-inline">
                                                                    <input value="3" type="radio"
                                                                        {{ $attendance_type == '3' ? 'checked' : '' }}
                                                                        id="absent_{{ $value->id }}"
                                                                        class="form-check-input SaveAttendance"
                                                                        name="attendance{{ $value->id }}">
                                                                    <label class="form-check-label"
                                                                        for="absent_{{ $value->id }}">Absent</label>
                                                                </div>
                                                                {{-- <div class="form-check form-check-inline">
                                                                    <input value="4" type="radio"
                                                                        {{ $attendance_type == '4' ? 'checked' : '' }}
                                                                        id="halfday_{{ $value->id }}"
                                                                        class="form-check-input SaveAttendance"
                                                                        name="attendance{{ $value->id }}">
                                                                    <label class="form-check-label"
                                                                        for="halfday_{{ $value->id }}">Half Day</label>
                                                                </div> --}}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="3" class="text-center text-muted py-4">
                                                        <i class="fa-solid fa-folder-open fa-2x mb-2"></i><br>
                                                        Aucun étudiant trouvé.
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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

        .btn-info,
        .btn-danger,
        .btn-success,
        .btn-primary {
            font-weight: 500;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .form-label {
            font-weight: 600;
        }

        .form-check-label {
            font-weight: 500;
        }

        .form-check-input:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, .25);
        }
    </style>


@endsection

@section('script')
    .

    {{-- <script type="text/javascript">
        $('.SaveAttendance').change(function(e) {

            var student_id = $(this).attr('id');
            var attendance_type = $(this).val();
            var class_id = $('#getClass').val();
            var attendance_date = $('#getAttendanceDate').val();


            $.ajax({
                type: "POST",
                url: "{{ url('admin/attendance/student/save') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    student_id: student_id,
                    attendance_type: attendance_type,
                    class_id: class_id,
                    attendance_date: attendance_date,
                },
                dataType: "json",
                success: function(data) {
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                }
            });

        });
    </script> --}}

    <script type="text/javascript">
        $('.SaveAttendance').change(function(e) {
            // L'id est de la forme 'present_42', 'absent_42', etc.
            // On récupère uniquement la partie numérique (l'ID étudiant)
            var fullId = $(this).attr('id'); // ex: "present_42"
            var student_id = fullId.split('_').pop(); // "42"

            var attendance_type = $(this).val();
            var class_id = $('#getClass').val();
            var attendance_date = $('#getAttendanceDate').val();

            $.ajax({
                type: "POST",
                url: "{{ url('admin/attendance/student/save') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    student_id: student_id,
                    attendance_type: attendance_type,
                    class_id: class_id,
                    attendance_date: attendance_date,
                },
                dataType: "json",
                success: function(data) {
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        title: 'Erreur',
                        text: 'Une erreur est survenue lors de l\'enregistrement.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    </script>
@endsection
