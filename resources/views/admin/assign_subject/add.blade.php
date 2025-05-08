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
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Assigner des matières à une classe</h3>
            </div>
            <div class="card-body">
                <form action="" method="POST" id="assignmentForm">
                    @csrf

                    <div class="row">
                        <!-- Sélection de l'année académique -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Année Académique</label>
                                <select name="academic_year_id" id="academicYear" class="form-control" required>
                                    <option value="">Sélectionner une année</option>
                                    @foreach ($academicYears as $year)
                                        <option value="{{ $year->id }}">{{ $year->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Sélection dynamique des classes -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Classe</label>
                                <select name="class_id" id="classSelect" class="form-control" required disabled>
                                    <option value="">Choisissez d'abord une année</option>
                                </select>
                            </div>
                        </div>

                        <!-- Sélection dynamique des matières -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Matières</label>
                                <select name="subject_id[]" id="subjectSelect" class="form-control select2" multiple
                                    required disabled>
                                    <option value="">Choisissez d'abord une année</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" name="status">
                                    <option value="0">Active</option>
                                    <option value="1">Inactive</option>
                                </select>

                            </div>
                        </div>

                    </div>


                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
