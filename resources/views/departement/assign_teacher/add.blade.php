@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <h1 class="h3 fw-bold text-primary">Ajouter une assignation de professeur</h1>
            </div>
        </section>

        <!-- Main Content -->
        <section class="content pb-5">
            <div class="container" style="max-width: 600px;">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('departement.assign_teacher.insert') }}" novalidate>
                    @csrf

                    <div class="mb-4">
                        <label for="academic_year_id" class="form-label fw-semibold">
                            Année Académique <span class="text-danger">*</span>
                        </label>
                        <select id="academic_year_id" name="academic_year_id"
                            class="form-select form-control @error('academic_year_id') is-invalid @enderror" required>
                            <option value="">Sélectionner une année</option>
                            @foreach ($academicYears as $year)
                                <option value="{{ $year->id }}"
                                    {{ old('academic_year_id') == $year->id ? 'selected' : '' }}>{{ $year->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('academic_year_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="class_id" class="form-label fw-semibold">
                            Classe <span class="text-danger">*</span>
                        </label>
                        <select id="class_id" name="class_id"
                            class="form-select form-control @error('class_id') is-invalid @enderror" required disabled>
                            <option value="">Choisissez d'abord une année</option>
                        </select>
                        @error('class_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Sélectionner un ou plusieurs enseignants <span
                                class="text-danger">*</span></label>
                        <div class="d-flex flex-column gap-2"
                            style="max-height: 250px;overflow-y: auto;border: 1px solid #ced4da;border-radius: 0.375rem;padding: 10px;">
                            @foreach ($getTeacher as $teacher)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="{{ $teacher->id }}"
                                        name="teacher_id[]" id="teacher_{{ $teacher->id }}"
                                        {{ is_array(old('teacher_id')) && in_array($teacher->id, old('teacher_id')) ? 'checked' : '' }}>
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
                            class="form-select form-control @error('status') is-invalid @enderror" required>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Actif</option>
                            <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Inactif</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold shadow-sm">Assigner la
                            classe</button>
                    </div>
                </form>
            </div>
        </section>
    </div>

    <style>
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
        document.getElementById('academic_year_id').addEventListener('change', function() {
            const yearId = this.value;
            const classSelect = document.getElementById('class_id');
            classSelect.innerHTML = '<option>Chargement...</option>';
            classSelect.disabled = true;
            if (!yearId) {
                classSelect.innerHTML = '<option>Choisissez d\'abord une année</option>';
                classSelect.disabled = true;
                return;
            }
            const baseUrl =
                "{{ route('departement.assign_teacher.get_classes_by_year', ['yearId' => 'YEARID_REPLACE']) }}";
            const url = baseUrl.replace('YEARID_REPLACE', yearId);
            fetch(url)
                .then(response => {
                    if (!response.ok) throw new Error('HTTP status ' + response.status);
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
                    classSelect.innerHTML = `<option>Erreur de chargement (${error.message})</option>`;
                    classSelect.disabled = true;
                    console.error('Erreur lors du chargement:', error);
                });
        });
    </script>
@endsection
