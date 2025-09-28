@extends('layouts.app')

@section('style')
    <style>
        .styled-table thead tr {
            background-color: #009879;
            color: #ffffff;
            text-align: center;
        }

        .styled-table tbody tr:hover {
            background-color: #e9f0ff;
            transition: background-color 0.3s ease;
        }

        .btn-sm {
            padding: 0.25rem 0.75rem;
            font-size: 0.9rem;
        }

        .fw-bold,
        .fw-semibold {
            font-weight: 600 !important;
        }

        .table-primary th {
            background-color: #cfe2ff !important;
            color: #084298 !important;
            font-weight: 600;
            vertical-align: middle;
        }

        .table td,
        .table th {
            vertical-align: middle;
            font-size: 0.95rem;
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <h1 class="h3 fw-bold text-primary">Marks Register</h1>
            </div>
        </section>

        <!-- Main content -->
        <section class="content pb-5">
            <div class="container-fluid">
                <!-- Search Card -->
                <div class="card shadow-sm rounded-4 border-0 mb-4">
                    <div class="card-header bg-primary text-white rounded-top-4">
                        <h3 class="card-title mb-0">
                            <i class="fa-solid fa-magnifying-glass me-2"></i> Search Marks Register
                        </h3>
                    </div>
                    <form method="get" action="">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Exam</label>
                                    <select name="exam_id" class="form-select form-control">
                                        @foreach ($getExam as $exam)
                                            <option value="{{ $exam->id }}"
                                                {{ request('exam_id') == $exam->id ? 'selected' : '' }}>
                                                {{ $exam->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Class</label>
                                    <select class="form-select form-control" name="class_id" required>
                                        <option value="">Select</option>
                                        @foreach ($getClass as $class)
                                            <option value="{{ $class['class_id'] }}"
                                                {{ request('class_id') == $class['class_id'] ? 'selected' : '' }}>
                                                {{ $class['class_name'] }} {{ $class['class_opt'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex align-items-end gap-2">
                                    <button class="btn btn-primary w-100" type="submit">
                                        <i class="fa-solid fa-magnifying-glass me-1"></i> Search
                                    </button>
                                    <a href="{{ url('teacher/marks_register') }}" class="btn btn-success w-100">
                                        Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                @include('_message')

                @if (!empty($getSubject) && $getSubject->count())
                    <div class="card shadow-sm rounded-4 border-0">
                        <div
                            class="card-header bg-primary text-white rounded-top-4 d-flex justify-content-between align-items-center">
                            <h3 class="card-title mb-0">
                                <i class="fa-solid fa-list-ul me-2"></i> Exam Schedule
                            </h3>
                            <div class="d-flex gap-2">
                                <!-- Save All -->
                                <button type="button" id="saveAllBtn" class="btn btn-success">
                                    <i class="fa fa-save me-1"></i> Enregistrer Tout
                                </button>

                                <!-- Export Excel -->
                                <form method="GET" action="{{ route('teacher.marks_export') }}">
                                    <input type="hidden" name="exam_id" value="{{ request('exam_id') }}">
                                    <input type="hidden" name="class_id" value="{{ request('class_id') }}">
                                    <button class="btn btn-warning" type="submit">
                                        <i class="fa-solid fa-file-export me-1"></i> Exporter Excel
                                    </button>
                                </form>

                                <!-- Import Excel -->
                                <form method="POST" action="{{ route('teacher.marks_import') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="exam_id" value="{{ request('exam_id') }}">
                                    <input type="hidden" name="class_id" value="{{ request('class_id') }}">
                                    <label class="btn btn-info mb-0">
                                        <i class="fa-solid fa-file-import me-1"></i> Importer Excel
                                        <input type="file" name="file" class="d-none" onchange="this.form.submit()">
                                    </label>
                                </form>
                            </div>
                        </div>

                        <form id="marksForm">
                            @csrf
                            <input type="hidden" name="exam_id" value="{{ request('exam_id') }}">
                            <input type="hidden" name="class_id" value="{{ request('class_id') }}">

                            <div class="card-body p-0 table-responsive">
                                <table class="table table-hover table-bordered align-middle mb-0 styled-table">
                                    <thead class="table-primary text-center">
                                        <tr>
                                            <th>Étudiant</th>
                                            @foreach ($getSubject as $subject)
                                                <th>
                                                    {{ $subject->subject_name }} <br>
                                                    <small class="text-muted">({{ $subject->subject_type }} :
                                                        {{ $subject->passing_mark }}/{{ $subject->full_marks }})</small>
                                                </th>
                                            @endforeach
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($getStudent as $student)
                                            <tr>
                                                <td class="fw-semibold">{{ $student->name }} {{ $student->last_name }}
                                                </td>
                                                @foreach ($getSubject as $subject)
                                                    @php
                                                        $mark = $subject->getMark(
                                                            $student->id,
                                                            request('exam_id'),
                                                            request('class_id'),
                                                            $subject->subject_id,
                                                        );
                                                    @endphp
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            <input type="number"
                                                                name="marks[{{ $student->id }}][{{ $subject->subject_id }}][class_work]"
                                                                value="{{ $mark->class_work ?? '' }}" placeholder="CW"
                                                                class="form-control form-control-sm note-input"
                                                                style="width:70px;">
                                                            <input type="number"
                                                                name="marks[{{ $student->id }}][{{ $subject->subject_id }}][exam]"
                                                                value="{{ $mark->exam ?? '' }}" placeholder="Exam"
                                                                class="form-control form-control-sm note-input"
                                                                style="width:70px;">
                                                            <input type="hidden"
                                                                name="marks[{{ $student->id }}][{{ $subject->subject_id }}][id]"
                                                                value="{{ $subject->id }}">
                                                            <input type="hidden"
                                                                name="marks[{{ $student->id }}][{{ $subject->subject_id }}][subject_id]"
                                                                value="{{ $subject->subject_id }}">

                                                        </div>
                                                    </td>
                                                @endforeach
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm SaveSingleSubject"
                                                        data-student-id="{{ $student->id }}"
                                                        data-subject-id="{{ $subject->subject_id }}"
                                                        data-exam-id="{{ request('exam_id') }}"
                                                        data-schedule-id="{{ $subject->id }}"
                                                        data-class-id="{{ request('class_id') }}">
                                                        Save
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </section>
    </div>
@endsection

@section('script')
    <script>
        // Sauvegarde globale
        document.getElementById('saveAllBtn').addEventListener('click', function() {
            let form = document.getElementById('marksForm');
            let formData = new FormData(form);

            fetch("{{ route('teacher.submit_all_marks_register') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Succès!', data.message, 'success');
                    } else {
                        Swal.fire('Erreur', data.message, 'error');
                    }
                })
                .catch(() => {
                    Swal.fire('Erreur', 'Problème de réseau', 'error');
                });
        });

        // Sauvegarde individuelle
        $('.SaveSingleSubject').click(function(e) {
            e.preventDefault();
            const studentId = $(this).data('student-id');
            const subjectId = $(this).data('subject-id');
            const examId = $(this).data('exam-id');
            const classId = $(this).data('class-id');
            const id = $(this).data('schedule-id');

            const classWork = $('input[name="marks[' + studentId + '][' + subjectId + '][class_work]"]').val() || 0;
            const examScore = $('input[name="marks[' + studentId + '][' + subjectId + '][exam]"]').val() || 0;

            $.ajax({
                type: "POST",
                url: "{{ url('teacher/single_submit_marks_register') }}",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'id': id,
                    'student_id': studentId,
                    'subject_id': subjectId,
                    'exam_id': examId,
                    'class_id': classId,
                    'class_work': parseFloat(classWork),
                    'exam': parseFloat(examScore)
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Succès!', response.message, 'success');
                    } else {
                        Swal.fire('Erreur', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    Swal.fire('Erreur', xhr.responseJSON?.message || 'Erreur technique', 'error');
                }
            });
        });
    </script>
@endsection
