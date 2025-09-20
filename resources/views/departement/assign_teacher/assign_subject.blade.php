@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="h3 fw-bold text-primary">Assigner des matières à un enseignant</h1>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content pb-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">

                        <div class="card shadow-sm rounded-4 border-0">
                            @include('_message')
                            <div class="card-body">
                                <form method="post"
                                    action="{{ route('departement.assign_teacher.assign_subject.submit') }}" novalidate>
                                    @csrf

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Enseignant</label>
                                        <input type="text" class="form-control"
                                            value="{{ $selectedTeacher->name }} {{ $selectedTeacher->last_name }}" readonly>
                                        <input type="hidden" name="teacher_id" value="{{ $selectedTeacher->id }}">
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Classe Assignée</label>
                                        @if ($classes->count() > 1)
                                            <select name="class_id" id="classSelect" class="form-select" required
                                                onchange="window.location.href='?class_id='+this.value">
                                                @foreach ($classes as $c)
                                                    <option value="{{ $c->id }}"
                                                        {{ $selectedClass->id == $c->id ? 'selected' : '' }}>
                                                        {{ $c->name }} {{ $c->opt }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            <input type="text" class="form-control"
                                                value="{{ $selectedClass->name }} {{ $selectedClass->opt }}" readonly>
                                            <input type="hidden" name="class_id" value="{{ $selectedClass->id }}">
                                        @endif
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Année Académique</label>
                                        <input type="text" class="form-control" value="{{ $academicYear->name }}"
                                            readonly>
                                        <input type="hidden" name="academic_year_id" value="{{ $academicYear->id }}">
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Matières Disponibles</label>
                                        <select name="subject_ids[]" class="form-select" multiple required size="8"
                                            style="min-height: 200px;">
                                            @foreach ($subjects as $subject)
                                                @php $isAssigned = in_array($subject->id, $assignedSubjectIds) @endphp
                                                <option value="{{ $subject->id }}" {{ $isAssigned ? 'selected' : '' }}>
                                                    {{ $subject->name }} ({{ $subject->code }})
                                                    @if ($isAssigned)
                                                        - <em>Déjà assigné</em>
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Les matières déjà assignées sont présélectionnées</small>
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <button type="submit"
                                            class="btn btn-primary px-4 py-2 fw-semibold shadow-sm">Assigner</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>

    <style>
        .card {
            border-radius: 1.5rem;
        }

        .form-label {
            font-weight: 600;
        }

        .form-select:focus,
        input.form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, .25);
        }

        .btn-primary {
            background: linear-gradient(90deg, #0d6efd 60%, #0dcaf0 100%);
            border: none;
            transition: background 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(90deg, #0dcaf0 0%, #0d6efd 100%);
        }
    </style>
@endsection
