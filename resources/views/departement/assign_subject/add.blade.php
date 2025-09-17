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

        /* autres styles Select2 */
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="card shadow-sm rounded-4 border-0 mx-auto" style="max-width: 900px;">
            <div class="card-header bg-primary text-white rounded-top-4">
                <h3 class="mb-0 fw-bold">Assigner des matières à une classe</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('departement.assign_subject.insert') }}" method="POST" id="assignmentForm" novalidate>
                    @csrf
                    <div class="row g-3">
                        <!-- Année Académique -->
                        <div class="col-md-4">
                            <label for="academicYear" class="form-label fw-semibold">Année Académique <span
                                    class="text-danger">*</span></label>
                            <select name="academic_year_id" id="academicYear"
                                class="form-select form-control @error('academic_year_id') is-invalid @enderror" required>
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
                                class="form-select form-control @error('class_id') is-invalid @enderror" required disabled>
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
                                class="form-select select2 form-control @error('subject_id') is-invalid @enderror" multiple
                                required disabled>
                                <option value="">Choisissez d'abord une année</option>
                            </select>
                            @error('subject_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Statut -->
                        <div class="col-md-4">
                            <label for="status" class="form-label fw-semibold">Statut</label>
                            <select id="status" name="status" class="form-select form-control">
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
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#subjectSelect').select2({
                theme: 'bootstrap-5',
                placeholder: 'Sélectionnez les matières',
                allowClear: true
            });

            $('#academicYear').on('change', function() {
                let yearId = $(this).val();
                if (!yearId) {
                    resetSelect('#classSelect', "Choisissez d'abord une année");
                    resetSelect('#subjectSelect', "Choisissez d'abord une année");
                    return;
                }

                // Charger les classes du département pour l'année sélectionnée
                $.ajax({
                    url: "{{ url('departement/get-classes-by-department-year') }}" +
                        "/{{ auth()->user()->department_id }}/" + yearId,
                    type: 'GET',
                    success: function(data) {
                        let options = '<option value="">Sélectionner une classe</option>';
                        data.forEach(cls => {
                            options +=
                                `<option value="${cls.id}">${cls.name} ${cls.opt || ''}</option>`;
                        });
                        $('#classSelect').html(options).prop('disabled', false);
                        resetSelect('#subjectSelect', "Choisissez une classe d'abord");
                    },
                    error: function() {
                        resetSelect('#classSelect', "Erreur de chargement");
                        resetSelect('#subjectSelect', "Erreur de chargement");
                    }
                });

                // Charger toutes les matières pour l'année (à adapter si vous souhaitez filtrer)
                $.ajax({
                    url: "{{ url('departement/get-subjects-by-year') }}/" + yearId,
                    type: 'GET',
                    success: function(data) {
                        let options = '';
                        data.forEach(subject => {
                            const displayName = subject.code ?
                                `${subject.code} - ${subject.name}` : subject.name;
                            options +=
                                `<option value="${subject.id}">${displayName}</option>`;
                        });
                        $('#subjectSelect').html(options).prop('disabled', false);
                        $('#subjectSelect').trigger('change');
                    },
                    error: function() {
                        resetSelect('#subjectSelect', "Erreur de chargement");
                    }
                });
            });

            $('#classSelect').on('change', function() {
                let classId = $(this).val();
                if (!classId) {
                    resetSelect('#subjectSelect', "Choisissez une classe d'abord");
                    return;
                }
            });

            function resetSelect(selector, message) {
                $(selector).html(`<option>${message}</option>`).prop('disabled', true).val(null).trigger('change');
            }
        });
    </script>
@endsection
