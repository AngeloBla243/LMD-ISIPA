@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="h3 fw-bold text-primary">Assigner une classe à un enseignant</h1>
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

                            <form id="assignClassForm" method="POST" action="{{ route('admin.assign_class_teacher.add') }}"
                                novalidate>
                                @csrf
                                <div class="card-body">
                                    <div class="mb-4">
                                        <label for="academicYear" class="form-label fw-semibold">Année académique <span
                                                class="text-danger">*</span></label>
                                        <select name="academic_year_id" id="academicYear"
                                            class="form-select form-control @error('academic_year_id') is-invalid @enderror"
                                            required>
                                            <option value="">Sélectionner une année</option>
                                            @foreach ($academicYears as $year)
                                                <option value="{{ $year->id }}"
                                                    {{ old('academic_year_id') == $year->id ? 'selected' : '' }}>
                                                    {{ $year->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('academic_year_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="classSelect" class="form-label fw-semibold">Classe <span
                                                class="text-danger">*</span></label>
                                        <select name="class_id" id="classSelect"
                                            class="form-select form-control @error('class_id') is-invalid @enderror"
                                            required>
                                            <option value="">Choisissez d'abord une année</option>
                                            {{-- Les options seront chargées dynamiquement --}}
                                        </select>
                                        @error('class_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Sélectionner un ou plusieurs enseignants <span
                                                class="text-danger">*</span></label>
                                        <div class="d-flex flex-column gap-2"
                                            style="max-height: 250px; overflow-y: auto; border: 1px solid #ced4da; border-radius: 0.375rem; padding: 10px;">
                                            @foreach ($getTeacher as $teacher)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="{{ $teacher->id }}" name="teacher_id[]"
                                                        id="teacher_{{ $teacher->id }}">
                                                    <label class="form-check-label" for="teacher_{{ $teacher->id }}">
                                                        {{ $teacher->name }} {{ $teacher->last_name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                        @error('teacher_id')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="status" class="form-label fw-semibold">Statut</label>
                                        <select id="status" name="status"
                                            class="form-select form-control @error('status') is-invalid @enderror">
                                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Actif
                                            </option>
                                            <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Inactif
                                            </option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="card-footer d-flex justify-content-end bg-white border-0">
                                    <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold shadow-sm">
                                        Assigner la classe
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
        }

        .form-label {
            font-weight: 600;
        }

        .form-select:focus,
        .form-check-input:focus {
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

@section('script')
    <script>
        document.getElementById('academicYear').addEventListener('change', function() {
            const yearId = this.value;
            const classSelect = document.getElementById('classSelect');

            classSelect.innerHTML = '<option value="">Chargement...</option>';
            classSelect.disabled = true;

            if (!yearId) {
                classSelect.innerHTML = '<option value="">Veuillez d\'abord choisir une année</option>';
                return;
            }

            // Génération dynamique de l'URL avec la fonction Laravel url()
            const url = "{{ url('/admin/assign_class_teacher/get-classes') }}/" + yearId;

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur HTTP : ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    classSelect.innerHTML = '<option value="">Sélectionner une classe</option>';
                    data.forEach(cls => {
                        classSelect.innerHTML +=
                            `<option value="${cls.id}">${cls.name} ${cls.opt ? '(' + cls.opt + ')' : ''}</option>`;
                    });
                    classSelect.disabled = false;
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des classes :', error);
                    classSelect.innerHTML = `<option value="">Erreur de chargement (${error.message})</option>`;
                    classSelect.disabled = true;
                });
        });
    </script>
@endsection
