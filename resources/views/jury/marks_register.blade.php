@extends('layouts.app')

@section('style')
    <style type="text/css">
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
                        <h1 class="h3 fw-bold text-primary">Fiche de Jury</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content pb-5">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">

                        <!-- Formulaire filtres -->
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
                                    <select class="form-select form-control" name="exam_id" onchange="this.form.submit()"
                                        {{ empty($filteredExams) ? 'disabled' : '' }}>
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
                                    <select class="form-select form-control" name="class_id" onchange="this.form.submit()"
                                        {{ empty($filteredClasses) ? 'disabled' : '' }}>
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
                                    <a href="{{ url('jury/marks_register') }}"
                                        class="btn btn-success w-100">Réinitialiser</a>
                                </div>
                            </div>
                        </form>

                        @include('_message')

                        @if (!empty($getSubject) && !empty($getStudent))
                            <form id="marksRegisterForm" method="POST">
                                @csrf
                                <!-- Champs cachés -->
                                <input type="hidden" name="exam_id" value="{{ request('exam_id') }}">
                                <input type="hidden" name="class_id" value="{{ request('class_id') }}">
                                <input type="hidden" name="academic_year_id" value="{{ request('academic_year_id') }}">

                                <div class="card-body p-0 table-responsive">
                                    <table class="table table-hover table-bordered align-middle mb-0">
                                        <thead class="table-primary text-center text-uppercase small">
                                            <tr>
                                                <th style="min-width: 250px;">Nom Étudiant</th>
                                                @foreach ($getSubject as $subject)
                                                    <th style="min-width: 200px;">
                                                        {{ $subject->subject_name }}
                                                        <br />
                                                        <span class="text-muted small">
                                                            ({{ $subject->subject_type }}:
                                                            {{ $subject->passing_mark }}/{{ $subject->full_marks }})
                                                        </span>
                                                    </th>
                                                @endforeach
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($getStudent as $student)
                                                <tr>
                                                    <td class="fw-semibold text-start">
                                                        {{ $student->name }} {{ $student->last_name }}
                                                    </td>
                                                    @foreach ($getSubject as $subject)
                                                        @php
                                                            $getMark = \App\Models\ExamScheduleModel::getMark(
                                                                $student->id,
                                                                request('exam_id'),
                                                                request('class_id'),
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
                                                            <input type="number"
                                                                name="marks[{{ $student->id }}][{{ $subject->subject_id }}][class_work]"
                                                                class="form-control form-control-sm mb-1"
                                                                placeholder="Travail"
                                                                value="{{ $getMark->class_work ?? '' }}" min="0"
                                                                max="{{ $subject->full_marks }}" step="0.01" />

                                                            <input type="number"
                                                                name="marks[{{ $student->id }}][{{ $subject->subject_id }}][exam]"
                                                                class="form-control form-control-sm" placeholder="Examen"
                                                                value="{{ $getMark->exam ?? '' }}" min="0"
                                                                max="{{ $subject->full_marks }}" step="0.01" />

                                                            @if ($totalMark > 0)
                                                                <small>
                                                                    Crédit: {{ $subject->ponde }} -
                                                                    @if ($totalMark >= $subject->passing_mark)
                                                                        <span class="text-success fw-bold">Valide</span>
                                                                    @else
                                                                        <span class="text-danger fw-bold">Non valide</span>
                                                                    @endif
                                                                </small>
                                                            @endif
                                                        </td>
                                                    @endforeach
                                                    <td>
                                                        <a href="{{ url('admin/my_exam_result/print?exam_id=' . request('exam_id') . '&student_id=' . $student->id) }}"
                                                            target="_blank" class="btn btn-primary btn-sm">
                                                            Imprimer
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @if ($getStudent->isEmpty())
                                                <tr>
                                                    <td colspan="{{ count($getSubject) + 2 }}"
                                                        class="text-center text-muted py-4">
                                                        <i class="fa-solid fa-folder-open fa-2x mb-2"></i><br />
                                                        Aucun étudiant trouvé.
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                                <button type="button" id="saveAllBtn" class="btn btn-success mt-3">
                                    <i class="fas fa-save"></i> Enregistrer Tout
                                </button>
                            </form>
                        @endif

                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#saveAllBtn').click(function(e) {
                e.preventDefault();
                let formData = $('#marksRegisterForm').serialize();
                $.ajax({
                    url: "{{ route('jury.save_all_marks') }}",
                    method: 'POST',
                    data: formData,
                    dataType: 'json',
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
                            Swal.fire('Attention', response.message, 'warning');
                        }
                    },
                    error: function() {
                        Swal.fire('Erreur', 'Une erreur est survenue, merci de réessayer.',
                            'error');
                    }
                });
            });
        });
    </script>
@endsection
