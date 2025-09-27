@extends('layouts.app')

@section('style')
    <style type="text/css">
        .modal {
            display: none;
            position: fixed;
            z-index: 1050;
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
    </style>
@endsection

@section('content')

    <div class="content-wrapper">
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="h3 fw-bold text-primary">Fiche de Cotation</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content pb-5">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">

                        <div class="card shadow-sm rounded-4 border-0 mb-4">
                            <div class="card-header bg-primary text-white rounded-top-4">
                                <h3 class="card-title mb-0"><i class="fa-solid fa-list-ul me-2"></i>Registre des Notes</h3>
                            </div>

                            <!-- Formulaire de filtre -->
                            <form method="get" action="" class="mb-3 p-3">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Année Académique</label>
                                        <select class="form-select form-control" name="academic_year_id"
                                            onchange="this.form.submit()">
                                            <option value="">Sélectionner</option>
                                            @foreach ($academicYears as $year)
                                                <option value="{{ $year->id }}"
                                                    {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>
                                                    {{ $year->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Examen</label>
                                        <select class="form-select form-control" name="exam_id"
                                            onchange="this.form.submit()" {{ empty($filteredExams) ? 'disabled' : '' }}>
                                            <option value="">Sélectionner</option>
                                            @foreach ($filteredExams as $exam)
                                                <option value="{{ $exam->id }}"
                                                    {{ request('exam_id') == $exam->id ? 'selected' : '' }}>
                                                    {{ $exam->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Classe</label>
                                        <select class="form-select form-control" name="class_id"
                                            onchange="this.form.submit()" {{ empty($filteredClasses) ? 'disabled' : '' }}>
                                            <option value="">Sélectionner</option>
                                            @foreach ($filteredClasses as $class)
                                                <option value="{{ $class->id }}"
                                                    {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                                    {{ $class->name }} {{ $class->opt }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3 d-flex align-items-end gap-2">
                                        <a href="{{ url('departement/marks_register') }}" class="btn btn-success w-100">
                                            Réinitialiser
                                        </a>
                                    </div>
                                </div>
                            </form>

                            @include('_message')

                            @if (!empty($getSubject) && !empty($getStudent))
                                <div class="card-body p-0 table-responsive">
                                    <table class="table table-hover table-bordered align-middle mb-0">
                                        <thead class="table-primary text-center text-uppercase small">
                                            <tr>
                                                <th style="min-width: 250px;">Nom Étudiant</th>
                                                @foreach ($getSubject as $subject)
                                                    <th style="min-width: 200px;">{{ $subject->subject_name }}
                                                        <br />
                                                        <span class="text-muted small">({{ $subject->subject_type }}:
                                                            {{ $subject->passing_mark }}/{{ $subject->full_marks }})</span>
                                                    </th>
                                                @endforeach
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($getStudent as $student)
                                                <form name="post" class="SubmitForm">
                                                    {{ csrf_field() }}
                                                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                                                    <input type="hidden" name="exam_id"
                                                        value="{{ Request::get('exam_id') }}">
                                                    <input type="hidden" name="class_id"
                                                        value="{{ Request::get('class_id') }}">
                                                    <input type="hidden" name="academic_year_id"
                                                        value="{{ $class->academic_year_id ?? '' }}">
                                                    <tr>
                                                        <td class="fw-semibold">{{ $student->name }}
                                                            {{ $student->last_name }}</td>
                                                        @php $i = 1; @endphp
                                                        @foreach ($getSubject as $subject)
                                                            @php
                                                                $getMark = $subject->getMark(
                                                                    $student->id,
                                                                    Request::get('exam_id'),
                                                                    Request::get('class_id'),
                                                                    $subject->subject_id,
                                                                );
                                                                $totalMark =
                                                                    !empty($getMark) &&
                                                                    is_numeric($getMark->class_work) &&
                                                                    is_numeric($getMark->exam)
                                                                        ? $getMark->class_work + $getMark->exam
                                                                        : 0;
                                                            @endphp
                                                            <td>
                                                                <div class="mb-2">
                                                                    <label class="form-label small">Travail</label>
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
                                                                    <input type="text"
                                                                        name="mark[{{ $i }}][class_work]"
                                                                        id="class_work_{{ $student->id }}{{ $subject->subject_id }}"
                                                                        class="form-control form-control-sm"
                                                                        style="width:120px" placeholder="Notes"
                                                                        value="{{ !empty($getMark->class_work) ? $getMark->class_work : '' }}">
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label class="form-label small">Examen</label>
                                                                    <input type="text"
                                                                        name="mark[{{ $i }}][exam]"
                                                                        id="exam_{{ $student->id }}{{ $subject->subject_id }}"
                                                                        class="form-control form-control-sm"
                                                                        style="width:120px" placeholder="Notes"
                                                                        value="{{ !empty($getMark->exam) ? $getMark->exam : '' }}">
                                                                </div>
                                                                <div class="mb-2">
                                                                </div>
                                                                @if (!empty($getMark))
                                                                    <div class="mb-2">
                                                                        <b>Total Crédit : {{ $subject->ponde }}</b><br />
                                                                        @if ($totalMark >= 10)
                                                                            <b
                                                                                style="color: green; font-weight: bold;">Valide</b>
                                                                        @else
                                                                            <b style="color: red; font-weight: bold;">Non
                                                                                valide</b>
                                                                        @endif
                                                                    </div>
                                                                @endif
                                                            </td>
                                                            @php $i++; @endphp
                                                        @endforeach
                                                        <td style="min-width: 230px;">
                                                            {{-- <button type="submit"
                                                                class="btn btn-success btn-sm shadow-sm SubmitForm">
                                                                <i class="fas fa-save"></i> Enregistrer
                                                            </button> --}}
                                                            <a class="btn btn-primary btn-sm shadow-sm" target="_blank"
                                                                href="{{ url('admin/my_exam_result/print?exam_id=' . Request::get('exam_id') . '&student_id=' . $student->id) }}">
                                                                Imprimer
                                                            </a>
                                                        </td>
                                                    </tr>
                                                </form>
                                            @endforeach
                                            @if ($getStudent->count() == 0)
                                                <tr>
                                                    <td colspan="{{ 2 + $getSubject->count() }}"
                                                        class="text-center text-muted py-4">
                                                        <i class="fa-solid fa-folder-open fa-2x mb-2"></i><br>
                                                        Aucun étudiant trouvé.
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div style="text-align: center; padding: 20px;">
                                    <a class="btn btn-primary" target="_blank"
                                        href="{{ url('departement/result_print/print?exam_id=' . Request::get('exam_id') . '&class_id=' . Request::get('class_id')) }}">
                                        Imprimer
                                    </a>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('.SubmitForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "{{ url('admin/examinations/submit_marks_register') }}",
                    data: $(this).serialize(),
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Succès !',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Attention !',
                                text: response.message,
                                icon: 'warning',
                                confirmButtonText: 'OK'
                            });
                        }
                    }
                });
            });

            $('.SaveSingleSubject').click(function() {
                const studentId = $(this).data('student-id');
                const subjectId = $(this).data('subject-id');
                const examId = $(this).data('exam-id');
                const classId = $(this).data('class-id');
                const id = $(this).data('schedule-id');

                const classWork = $('#class_work_' + studentId + subjectId).val() || 0;
                const examScore = $('#exam_' + studentId + subjectId).val() || 0;

                $.ajax({
                    type: "POST",
                    url: "{{ url('admin/examinations/single_submit_marks_register') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        student_id: studentId,
                        subject_id: subjectId,
                        exam_id: examId,
                        class_id: classId,
                        class_work: parseFloat(classWork),
                        exam: parseFloat(examScore)
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Succès !',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
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
                        Swal.fire({
                            title: 'Erreur technique',
                            text: xhr.responseJSON?.message || 'Échec de la requête',
                            icon: 'error'
                        });
                    }
                });
            });
        });
    </script>
@endsection
