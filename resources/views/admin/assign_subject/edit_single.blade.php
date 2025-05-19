@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="h3 fw-bold text-primary">Modifier une affectation de matière</h1>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content pb-5">
            <div class="container">
                <div class="card shadow-sm rounded-4 border-0 mx-auto" style="max-width: 600px;">
                    <form method="post" action="" novalidate>
                        @csrf
                        <div class="card-body">
                            <div class="mb-4">
                                <label for="academic_year_id" class="form-label fw-semibold">Année Académique <span
                                        class="text-danger">*</span></label>
                                <select id="academic_year_id" name="academic_year_id"
                                    class="form-select form-control @error('academic_year_id') is-invalid @enderror"
                                    required>
                                    @foreach ($academicYears as $year)
                                        <option value="{{ $year->id }}"
                                            {{ $selectedAcademicYear == $year->id ? 'selected' : '' }}>
                                            {{ $year->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('academic_year_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="class_id" class="form-label fw-semibold">Classe <span
                                        class="text-danger">*</span></label>
                                <select id="class_id" name="class_id"
                                    class="form-select form-control @error('class_id') is-invalid @enderror" required>
                                    <option value="">Sélectionner une classe</option>
                                    @foreach ($getClass as $class)
                                        <option value="{{ $class->id }}"
                                            {{ $getRecord->class_id == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }} {{ $class->opt }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('class_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="subject_id" class="form-label fw-semibold">Matière <span
                                        class="text-danger">*</span></label>
                                <select id="subject_id" name="subject_id"
                                    class="form-select form-control @error('subject_id') is-invalid @enderror" required>
                                    <option value="">Sélectionner une matière</option>
                                    @foreach ($getSubject as $subject)
                                        <option value="{{ $subject->id }}"
                                            {{ $getRecord->subject_id == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->name }} / {{ $subject->code }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('subject_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="status" class="form-label fw-semibold">Statut <span
                                        class="text-danger">*</span></label>
                                <select id="status" name="status"
                                    class="form-select form-control @error('status') is-invalid @enderror" required>
                                    <option value="0" {{ $getRecord->status == 0 ? 'selected' : '' }}>Actif</option>
                                    <option value="1" {{ $getRecord->status == 1 ? 'selected' : '' }}>Inactif</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="card-footer d-flex justify-content-end bg-white border-0">
                            <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold shadow-sm">
                                Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

    <style>
        .card {
            border-radius: 1.5rem;
            max-width: 600px;
            margin: auto;
        }

        .form-label {
            font-weight: 600;
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
