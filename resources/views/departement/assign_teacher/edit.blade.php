@extends('layouts.app')

@section('style')
    <style>
        .form-label {
            font-weight: 600;
        }

        .card {
            border-radius: 1.5rem;
            max-width: 600px;
            margin: auto;
        }

        .form-control:focus,
        .form-select:focus {
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

        .invalid-feedback {
            display: block;
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <h1 class="h3 fw-bold text-primary">{{ $header_title ?? 'Modifier une assignation' }}</h1>
            </div>
        </section>

        <section class="content pb-5">
            <div class="container">
                <div class="card shadow-sm rounded-4 border-0">
                    <form method="POST" action="{{ route('departement.assign_teacher.update', $getRecord->id) }}">
                        @csrf
                        <div class="card-body">
                            <div class="mb-4">
                                <label for="academic_year_id" class="form-label">Année Académique <span
                                        class="text-danger">*</span></label>
                                <select id="academic_year_id" name="academic_year_id"
                                    class="form-select form-control @error('academic_year_id') is-invalid @enderror"
                                    required>
                                    @foreach ($academicYears as $year)
                                        <option value="{{ $year->id }}"
                                            {{ $getRecord->academic_year_id == $year->id ? 'selected' : '' }}>
                                            {{ $year->name }}</option>
                                    @endforeach
                                </select>
                                @error('academic_year_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="class_id" class="form-label">Classe <span class="text-danger">*</span></label>
                                <select id="class_id" name="class_id"
                                    class="form-select form-control @error('class_id') is-invalid @enderror" required>
                                    @foreach ($getClass as $class)
                                        <option value="{{ $class->id }}"
                                            {{ $getRecord->class_id == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('class_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="teacher_id" class="form-label">Professeur <span
                                        class="text-danger">*</span></label>
                                <select id="teacher_id" name="teacher_id"
                                    class="form-select form-control @error('teacher_id') is-invalid @enderror" required>
                                    @foreach ($getTeacher as $teacher)
                                        <option value="{{ $teacher->id }}"
                                            {{ $getRecord->teacher_id == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('teacher_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="status" class="form-label">Statut <span class="text-danger">*</span></label>
                                <select id="status" name="status"
                                    class="form-select form-control @error('status') is-invalid @enderror" required>
                                    <option value="0" {{ $getRecord->status == 0 ? 'selected' : '' }}>Actif</option>
                                    <option value="1" {{ $getRecord->status == 1 ? 'selected' : '' }}>Inactif
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="card-footer d-flex justify-content-end bg-white border-0">
                            <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold shadow-sm">Mettre à
                                jour</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection
