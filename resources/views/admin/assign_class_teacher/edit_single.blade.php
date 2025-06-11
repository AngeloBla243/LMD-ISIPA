@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="h3 fw-bold text-primary">Modifier l'affectation d'un enseignant à une classe</h1>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content pb-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card shadow-sm rounded-4 border-0">
                            <form method="post" action="" novalidate>
                                @csrf
                                <div class="card-body">
                                    <div class="mb-4">
                                        <label for="class_id" class="form-label fw-semibold">Classe <span
                                                class="text-danger">*</span></label>
                                        <select id="class_id" name="class_id"
                                            class="form-select form-control @error('class_id') is-invalid @enderror"
                                            required>
                                            <option value="">Sélectionner une classe</option>
                                            @foreach ($getClass as $class)
                                                <option value="{{ $class->id }}"
                                                    {{ old('class_id', $getRecord->class_id) == $class->id ? 'selected' : '' }}>
                                                    {{ $class->name }} {{ $class->opt }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('class_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="teacher_id" class="form-label fw-semibold">Enseignant <span
                                                class="text-danger">*</span></label>
                                        <select id="teacher_id" name="teacher_id"
                                            class="form-select form-control @error('teacher_id') is-invalid @enderror"
                                            required>
                                            <option value="">Sélectionner un enseignant</option>
                                            @foreach ($getTeacher as $teacher)
                                                <option value="{{ $teacher->id }}"
                                                    {{ old('teacher_id', $getRecord->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                                    {{ $teacher->name }} {{ $teacher->last_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('teacher_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="status" class="form-label fw-semibold">Statut</label>
                                        <select id="status" name="status"
                                            class="form-select form-control @error('status') is-invalid @enderror">
                                            <option value="0"
                                                {{ old('status', $getRecord->status) == 0 ? 'selected' : '' }}>Actif
                                            </option>
                                            <option value="1"
                                                {{ old('status', $getRecord->status) == 1 ? 'selected' : '' }}>Inactif
                                            </option>
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
