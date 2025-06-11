@extends('layouts.app')
@section('style')
    <style type="text/css">
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

        .styled-table thead tr {
            background-color: #009879;
            color: #ffffff;
            text-align: left;
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
                        <h1 class="h3 fw-bold text-primary">Marks Register</h1>
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
                                    <i class="fa-solid fa-magnifying-glass me-2"></i>Search Marks Register
                                </h3>
                            </div>
                            <form method="get" action="">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Exam</label>
                                            <select class="form-select form-control getClass" name="exam_id" required>
                                                <option value="">Select</option>
                                                @foreach ($getExam as $exam)
                                                    <option
                                                        {{ Request::get('exam_id') == $exam->exam_id ? 'selected' : '' }}
                                                        value="{{ $exam->exam_id }}">{{ $exam->exam_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Class</label>
                                            <select class="form-select form-control getClass" name="class_id" required>
                                                <option value="">Select</option>
                                                @foreach ($getClass as $class)
                                                    <option
                                                        {{ Request::get('class_id') == $class->class_id ? 'selected' : '' }}
                                                        value="{{ $class->class_id }}">{{ $class->class_name }}
                                                        {{ $class->class_opt }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 d-flex align-items-end gap-2">
                                            <button class="btn btn-primary w-100" type="submit">
                                                <i class="fa-solid fa-magnifying-glass me-1"></i> Search
                                            </button>
                                            <a href="{{ url('admin/examinations/marks_register') }}"
                                                class="btn btn-success w-100">
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

                        @include('_message')

                        @if (!empty($getSubject) && !empty($getSubject->count()))
                            <div class="card shadow-sm rounded-4 border-0">
                                <div class="card-header bg-primary text-white rounded-top-4">
                                    <h3 class="card-title mb-0">
                                        <i class="fa-solid fa-list-ul me-2"></i>Exam Schedule
                                    </h3>
                                </div>
                                <div class="card-body p-0 table-responsive">
                                    <table class="table table-hover table-bordered align-middle mb-0 styled-table">
                                        <thead class="table-primary text-center text-uppercase small">
                                            <tr>
                                                <th style="min-width: 180px;">Nom de l'étudiant</th>
                                                @foreach ($getSubject as $subject)
                                                    <th style="min-width: 340px;">
                                                        {{ $subject->subject_name }} <br />
                                                        <span class="text-muted small">
                                                            ({{ $subject->subject_type }}: {{ $subject->passing_mark }} /
                                                            {{ $subject->full_marks }})
                                                        </span>
                                                    </th>
                                                @endforeach
                                                <th style="min-width: 230px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (!empty($getStudent) && !empty($getStudent->count()))
                                                @foreach ($getStudent as $student)
                                                    <form name="post" class="SubmitForm">
                                                        {{ csrf_field() }}
                                                        <input type="hidden" name="student_id"
                                                            value="{{ $student->id }}">
                                                        <input type="hidden" name="exam_id"
                                                            value="{{ Request::get('exam_id') }}">
                                                        <input type="hidden" name="class_id"
                                                            value="{{ Request::get('class_id') }}">
                                                        <tr>
                                                            <td class="fw-semibold">{{ $student->name }}
                                                                {{ $student->last_name }}</td>
                                                            @php
                                                                $i = 1;
                                                                $totalStudentMark = 0;
                                                                $totalFullMarks = 0;
                                                                $totalPassinglMarks = 0;
                                                                $pass_fail_vali = 0;
                                                            @endphp
                                                            @foreach ($getSubject as $subject)
                                                                @php
                                                                    $totalFullMarks =
                                                                        $totalFullMarks +
                                                                        $subject->full_marks * $subject->ponde;
                                                                    $totalPassinglMarks =
                                                                        $totalPassinglMarks +
                                                                        $subject->passing_mark * $subject->ponde;
                                                                    $totalMark = 0;
                                                                    $totalMarks = 0;
                                                                    $getMark = $subject->getMark(
                                                                        $student->id,
                                                                        Request::get('exam_id'),
                                                                        Request::get('class_id'),
                                                                        $subject->subject_id,
                                                                    );
                                                                    if (!empty($getMark)) {
                                                                        $totalMark =
                                                                            $getMark->class_work +
                                                                            $getMark->home_work +
                                                                            $getMark->test_work +
                                                                            $getMark->exam;
                                                                        $totalMarks = $totalMark * $subject->ponde;
                                                                        $totalPass =
                                                                            $subject->passing_mark * $subject->ponde;
                                                                    }
                                                                    $totalStudentMark = $totalStudentMark + $totalMarks;
                                                                @endphp

                                                                <td>
                                                                    <div class="d-flex align-items-center gap-2"
                                                                        style="gap:10px;">
                                                                        <div>
                                                                            <label
                                                                                class="form-label small mb-1">TP/TD</label>
                                                                            <input type="hidden"
                                                                                name="mark[{{ $i }}][full_marks]"
                                                                                value="{{ $subject->full_marks }}">
                                                                            <input type="hidden"
                                                                                name="mark[{{ $i }}][passing_mark]"
                                                                                value="{{ $subject->passing_mark }}">
                                                                            <input type="hidden"
                                                                                name="mark[{{ $i }}][ponde]"
                                                                                value="{{ $subject->ponde }}">
                                                                            <input type="hidden"
                                                                                name="mark[{{ $i }}][id]"
                                                                                value="{{ $subject->id }}">
                                                                            <input type="hidden"
                                                                                name="mark[{{ $i }}][subject_id]"
                                                                                value="{{ $subject->subject_id }}">
                                                                            <input type="number"
                                                                                name="mark[{{ $i }}][class_work]"
                                                                                id="class_work_{{ $student->id }}{{ $subject->subject_id }}"
                                                                                class="form-control form-control-sm note-input"
                                                                                style="width:70px; display:inline-block;"
                                                                                placeholder="CW"
                                                                                value="{{ !empty($getMark->class_work) ? $getMark->class_work : '' }}">
                                                                        </div>
                                                                        <div>
                                                                            <label
                                                                                class="form-label small mb-1">Exam</label>
                                                                            <input type="number"
                                                                                name="mark[{{ $i }}][exam]"
                                                                                id="exam_{{ $student->id }}{{ $subject->subject_id }}"
                                                                                class="form-control form-control-sm note-input"
                                                                                style="width:70px; display:inline-block;"
                                                                                placeholder="Exam"
                                                                                value="{{ !empty($getMark->exam) ? $getMark->exam : '' }}">
                                                                        </div>
                                                                        <button type="button"
                                                                            class="btn btn-primary btn-sm SaveSingleSubject note-btn"
                                                                            data-student-id="{{ $student->id }}"
                                                                            data-subject-id="{{ $subject->subject_id }}"
                                                                            data-exam-id="{{ Request::get('exam_id') }}"
                                                                            data-schedule-id="{{ $subject->id }}"
                                                                            data-class-id="{{ Request::get('class_id') }}">
                                                                            Save
                                                                        </button>
                                                                    </div>
                                                                </td>

                                                                @php $i++; @endphp
                                                            @endforeach
                                                            <td>
                                                                <button type="submit"
                                                                    class="btn btn-success mb-2">Save</button>
                                                                @if (!empty($totalStudentMark))
                                                                    <br>
                                                                    @if ($pass_fail_vali == 0)
                                                                        <b>Résultat :</b> <span
                                                                            class="text-success fw-bold">VAL</span>
                                                                    @else
                                                                        <b>Résultat :</b> <span
                                                                            class="text-danger fw-bold">NVL</span>
                                                                    @endif
                                                                @endif
                                                            </td>

                                                        </tr>


                                                    </form>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="{{ 2 + $getSubject->count() }}"
                                                        class="text-center text-muted py-4">
                                                        <i class="fa-solid fa-folder-open fa-2x mb-2"></i><br>
                                                        Aucun étudiant trouvé.
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>

                                        <div class="mb-3 text-end">
                                            <button type="button" id="saveAllBtn" class="btn btn-success">
                                                <i class="fa fa-save me-1"></i> Save All
                                            </button>
                                        </div>
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
            vertical-align: middle;
        }

        .table td,
        .table th {
            vertical-align: middle;
            font-size: 0.96rem;
        }

        .styled-table tbody tr:hover {
            background-color: #e9f0ff;
            transition: background-color 0.3s ease;
        }

        .form-label {
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

        .fw-bold,
        .fw-semibold {
            font-weight: 600 !important;
        }
    </style>

@endsection

@section('script')
    .
    <script type="text/javascript">
        // Soumission globale du formulaire de notes (plusieurs étudiants)
        $('.SubmitForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "{{ url('teacher/submit_marks_register') }}",
                data: $(this).serialize(),
                dataType: "json",
                success: function(data) {
                    let msg = data.message ? data.message.toLowerCase() : '';
                    if (msg.includes('successfully saved') || msg.includes('enregistrée')) {
                        Swal.fire({
                            title: 'Succès !',
                            text: data.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                    } else if (msg.includes('greater than full mark') || msg.includes(
                            'dépasse le maximum')) {
                        Swal.fire({
                            title: 'Erreur !',
                            text: data.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        title: 'Erreur !',
                        text: xhr.responseJSON?.message || 'Erreur technique',
                        icon: 'error'
                    });
                }
            });
        });

        // Soumission individuelle d'une note
        $('.SaveSingleSubject').click(function(e) {
            e.preventDefault();

            // Récupération des données via data-*
            const studentId = $(this).data('student-id');
            const subjectId = $(this).data('subject-id');
            const examId = $(this).data('exam-id');
            const classId = $(this).data('class-id');
            const id = $(this).data('schedule-id');

            // Correction des sélecteurs avec underscore
            const classWork = $('#class_work_' + studentId + subjectId).val() || 0;
            const examScore = $('#exam_' + studentId + subjectId).val() || 0;

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
                    'class_work': parseFloat(classWork), // Conversion en nombre
                    'exam': parseFloat(examScore)
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Succès !',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Rechargement ciblé (optionnel)
                            $btn.closest('tr').find('.status-indicator').html(
                                '<i class="fas fa-check text-success"></i>');
                        });
                    } else {
                        Swal.fire({
                            title: 'Erreur !',
                            text: response.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr) {
                    let errorMsg = "Erreur technique";
                    if (xhr.status === 422) { // Gestion des erreurs de validation Laravel
                        errorMsg = Object.values(xhr.responseJSON.errors).join(', ');
                    } else if (xhr.responseJSON?.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        title: 'Erreur !',
                        text: errorMsg,
                        icon: 'error'
                    });
                }
            });
        });
    </script>

    <script>
        document.getElementById('saveAllBtn').addEventListener('click', function() {
            const formData = new FormData(document.getElementById('marksForm'));

            fetch("{{ route('teacher.submit_all_marks_register') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Succès!', data.message, 'success');
                        document.querySelectorAll('.note-input').forEach(el => el.disabled = true);
                    } else {
                        let errorMsg = data.message;
                        if (data.errors) {
                            errorMsg += '<ul>';
                            data.errors.forEach(err => errorMsg += `<li>${err}</li>`);
                            errorMsg += '</ul>';
                        }
                        Swal.fire('Erreur', errorMsg, 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Erreur', 'Une erreur réseau est survenue', 'error');
                });
        });
    </script>
@endsection
