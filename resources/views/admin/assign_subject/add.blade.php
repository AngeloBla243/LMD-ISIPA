@extends('layouts.app')
@section('style')
    <style>
        .select2-container--default .select2-selection--multiple {
            min-height: 38px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            padding: 4px 6px;
            font-size: 14px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #007bff;
            border: none;
            color: white;
            padding: 3px 10px;
            margin-top: 4px;
            margin-right: 5px;
            border-radius: 3px;
            font-weight: 500;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: rgba(255, 255, 255, 0.7);
            margin-right: 5px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: white;
            cursor: pointer;
        }
    </style>
@endsection
@section('content')
    <div class="content-wrapper">
        <div class="card shadow-sm rounded-4 border-0 mx-auto" style="max-width: 900px;">
            <div class="card-header bg-primary text-white rounded-top-4">
                <h3 class="mb-0 fw-bold">Assigner des matières à une classe</h3>
            </div>
            <div class="card-body">
                <form action="" method="POST" id="assignmentForm" novalidate>
                    @csrf
                    <div class="row g-3">
                        <!-- Année Académique -->
                        <div class="col-md-4">
                            <label for="academicYear" class="form-label fw-semibold">Année Académique <span
                                    class="text-danger">*</span></label>
                            <select name="academic_year_id" id="academicYear"
                                class="form-select @error('academic_year_id') is-invalid @enderror" required>
                                <option value="">Sélectionner une année</option>
                                @foreach ($academicYears as $year)
                                    <option value="{{ $year->id }}">{{ $year->name }}</option>
                                @endforeach
                            </select>
                            @error('academic_year_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Classe -->
                        <div class="col-md-4">
                            <label for="classSelect" class="form-label fw-semibold">Classe <span
                                    class="text-danger">*</span></label>
                            <select name="class_id" id="classSelect"
                                class="form-select @error('class_id') is-invalid @enderror" required disabled>
                                <option value="">Choisissez d'abord une année</option>
                            </select>
                            @error('class_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Matières -->
                        <div class="col-md-4">
                            <label for="subjectSelect" class="form-label fw-semibold">Matières <span
                                    class="text-danger">*</span></label>
                            <select name="subject_id[]" id="subjectSelect"
                                class="form-select select2 @error('subject_id') is-invalid @enderror" multiple required
                                disabled>
                                <option value="">Choisissez d'abord une année</option>
                            </select>
                            @error('subject_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Statut -->
                        <div class="col-md-4">
                            <label for="status" class="form-label fw-semibold">Statut</label>
                            <select id="status" name="status" class="form-select">
                                <option value="0">Active</option>
                                <option value="1">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-success px-4 py-2 fw-semibold shadow-sm">
                            <i class="fas fa-save me-2"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .card {
            border-radius: 1.5rem;
        }

        .form-label {
            font-weight: 600;
        }

        .form-select:focus {
            border-color: #198754;
            box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
        }

        .btn-success {
            background: linear-gradient(90deg, #198754 60%, #20c997 100%);
            border: none;
            transition: background 0.3s ease;
        }

        .btn-success:hover {
            background: linear-gradient(90deg, #20c997 0%, #198754 100%);
        }

        .select2-container--bootstrap5 .select2-selection {
            border-radius: 0.375rem;
            border: 1px solid #ced4da;
            height: calc(2.5rem + 2px);
            padding: 0.375rem 0.75rem;
        }

        .select2-container--bootstrap5 .select2-selection--multiple {
            min-height: calc(2.5rem + 2px);
        }
    </style>

    <!-- N'oublie pas d'inclure jQuery et Select2 JS/CSS dans ta page pour que le select2 fonctionne -->
    <script>
        $(document).ready(function() {
            // Initialiser Select2
            $('#subjectSelect').select2({
                theme: 'bootstrap-5',
                placeholder: 'Sélectionnez les matières',
                allowClear: true
            });

            // Exemple de gestion dynamique des classes et matières selon l'année sélectionnée
            $('#academicYear').on('change', function() {
                let yearId = $(this).val();

                if (!yearId) {
                    $('#classSelect').prop('disabled', true).html(
                        '<option>Choisissez d\'abord une année</option>');
                    $('#subjectSelect').prop('disabled', true).val(null).trigger('change');
                    return;
                }

                // Activer et charger les classes via AJAX (exemple)
                $.ajax({
                    url: `/api/classes?year_id=${yearId}`,
                    type: 'GET',
                    success: function(data) {
                        let options = '<option value="">Sélectionner une classe</option>';
                        data.forEach(cls => {
                            options +=
                                `<option value="${cls.id}">${cls.name} ${cls.opt}</option>`;
                        });
                        $('#classSelect').html(options).prop('disabled', false);
                        $('#subjectSelect').prop('disabled', true).val(null).trigger('change');
                    }
                });
            });

            // Charger les matières selon la classe sélectionnée
            $('#classSelect').on('change', function() {
                let classId = $(this).val();

                if (!classId) {
                    $('#subjectSelect').prop('disabled', true).val(null).trigger('change');
                    return;
                }

                // Activer et charger les matières via AJAX (exemple)
                $.ajax({
                    url: `/api/subjects?class_id=${classId}`,
                    type: 'GET',
                    success: function(data) {
                        let options = '';
                        data.forEach(subject => {
                            options +=
                                `<option value="${subject.id}">${subject.name} / ${subject.code}</option>`;
                        });
                        $('#subjectSelect').html(options).prop('disabled', false).trigger(
                            'change');
                    }
                });
            });
        });
    </script>
@endsection


@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const yearSelect = document.getElementById('academicYear');
            const classSelect = document.getElementById('classSelect');
            const subjectSelect = document.getElementById('subjectSelect');

            yearSelect.addEventListener('change', function() {
                const yearId = this.value;
                classSelect.innerHTML = '<option value="">Chargement...</option>';
                subjectSelect.innerHTML = '<option value="">Chargement...</option>';
                classSelect.disabled = true;
                subjectSelect.disabled = true;

                if (yearId) {
                    // Charger les classes
                    fetch("{{ url('/admin/assign_subject/get-classes') }}/" + yearId)
                        .then(response => response.json())
                        .then(data => {
                            classSelect.innerHTML = '<option value="">Choisir une classe</option>';
                            data.forEach(classe => {
                                classSelect.innerHTML +=
                                    `<option value="${classe.id}">${classe.name} ${classe.opt || ''}</option>`;
                            });
                            classSelect.disabled = false;
                        })
                        .catch(() => {
                            classSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                        });

                    // Charger les matières
                    fetch("{{ url('/admin/assign_subject/get-subjects') }}/" + yearId)
                        .then(response => response.json())
                        .then(data => {
                            subjectSelect.innerHTML = '';
                            data.forEach(subject => {
                                const displayName = subject.code ?
                                    `${subject.code} - ${subject.name}` :
                                    subject.name;
                                subjectSelect.innerHTML +=
                                    `<option value="${subject.id}">${displayName}</option>`;
                            });
                            subjectSelect.disabled = false;
                            // Initialiser Select2 si présent
                            if (window.$ && $.fn.select2) {
                                $('#subjectSelect').select2();
                            }
                        })
                        .catch(() => {
                            subjectSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                        });
                } else {
                    classSelect.innerHTML = '<option value="">Choisir une année</option>';
                    subjectSelect.innerHTML = '<option value="">Choisir une année</option>';
                    classSelect.disabled = true;
                    subjectSelect.disabled = true;
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#subjectSelect').select2({
                placeholder: "Choisissez une ou plusieurs matières",
                width: '100%',
                allowClear: true,
                closeOnSelect: false
            });
        });
    </script>
@endsection
