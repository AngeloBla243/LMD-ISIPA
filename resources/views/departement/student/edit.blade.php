@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <h1 class="h3 fw-bold text-primary">{{ $header_title }}</h1>
            </div>
        </section>

        <section class="content pb-5">
            <div class="container-fluid">
                <form action="{{ route('departement.student.update', $getRecord->id) }}" method="POST">
                    @csrf

                    <div class="card p-3 my-4 bg-light border-0 shadow-sm">
                        <label class="fw-bold mb-2">Affectation Classe(s) et Année(s) académique(s)</label>
                        <div id="classYearContainer">
                            @foreach ($getRecord->studentClasses as $enrollment)
                                <div class="row align-items-end mb-2 class-academic-row g-2"
                                    data-class-id="{{ $enrollment->class_id }}">
                                    <div class="col-md-5">
                                        <select name="class_ids[]" class="form-select class-select form-control" required>
                                            <option value="">Sélectionnez une classe</option>
                                            {{-- Les options seront chargées par JS --}}
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        <select name="academic_year_ids[]"
                                            class="form-select academic-year-select form-control" required>
                                            <option value="">Sélectionnez une année académique</option>
                                            @foreach ($academicYears as $year)
                                                <option value="{{ $year->id }}"
                                                    {{ $enrollment->pivot->academic_year_id == $year->id ? 'selected' : '' }}>
                                                    {{ $year->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <button type="button" class="btn btn-danger btn-sm remove-row"
                                            title="Supprimer cette affectation">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="addClassYearRow">
                            <i class="fas fa-plus"></i> Ajouter une classe/année
                        </button>
                    </div>

                    <div class="card-footer d-flex justify-content-end bg-white border-0">
                        <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold shadow-sm">
                            Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection

@section('style')
    <style>
        .card {
            border-radius: 1.5rem;
        }

        .card-header {
            border-radius: 1.5rem 1.5rem 0 0;
        }

        .form-label {
            font-weight: 600;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, .15);
        }

        .btn-primary {
            background: linear-gradient(90deg, #0d6efd 60%, #0dcaf0 100%);
            border: none;
            transition: background 0.3s ease;
        }

        .btn-primary:hover,
        .btn-outline-primary:hover {
            background: linear-gradient(90deg, #0dcaf0 0%, #0d6efd 100%);
            color: #fff;
        }

        .invalid-feedback {
            display: block;
        }

        .remove-row {
            margin-top: 0.5rem;
        }
    </style>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const classYearContainer = document.getElementById('classYearContainer');
            const academicYears = @json($academicYears);
            const departementId = @json($departementId);
            const baseUrl = "{{ url('departement/get-classes-by-department-year') }}";

            // Fonction AJAX pour récupérer les classes selon département + année
            function fetchClasses(departmentId, yearId, classSelect, selectedClassId = null) {
                if (!yearId) {
                    classSelect.innerHTML = '<option value="">Sélectionnez une année académique</option>';
                    return;
                }

                fetch(`${baseUrl}/${departmentId}/${yearId}`, {
                        credentials: 'same-origin'
                    })
                    .then(response => {
                        if (!response.ok) return Promise.reject('Erreur chargement');
                        return response.json();
                    })
                    .then(classes => {
                        classSelect.innerHTML = '<option value="">Sélectionnez une classe</option>';
                        classes.forEach(c => {
                            const option = document.createElement('option');
                            option.value = c.id;
                            option.textContent = c.name + (c.opt ? ' - ' + c.opt : '');
                            if (selectedClassId && selectedClassId == c.id) option.selected = true;
                            classSelect.appendChild(option);
                        });
                    })
                    .catch(() => {
                        classSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                    });
            }

            // Charger les classes existantes pour chaque ligne
            classYearContainer.querySelectorAll('.class-academic-row').forEach(row => {
                const classSelect = row.querySelector('.class-select');
                const yearSelect = row.querySelector('.academic-year-select');
                // Récupérer id de classe stocké dans data-class-id
                const selectedClassId = row.dataset.classId ?? null;
                fetchClasses(departementId, yearSelect.value, classSelect, selectedClassId);
            });

            // Ajouter une nouvelle ligne dynamique
            document.getElementById('addClassYearRow').addEventListener('click', function() {
                const row = document.createElement('div');
                row.className = 'row align-items-end mb-2 class-academic-row g-2';

                let yearOptions = '<option value="">Sélectionnez une année académique</option>';
                academicYears.forEach(y => {
                    yearOptions += `<option value="${y.id}">${y.name}</option>`;
                });

                row.innerHTML = `
            <div class="col-md-5">
                <select name="class_ids[]" class="form-select class-select" required>
                    <option value="">Sélectionnez une classe</option>
                </select>
            </div>
            <div class="col-md-5">
                <select name="academic_year_ids[]" class="form-select academic-year-select" required>
                    ${yearOptions}
                </select>
            </div>
            <div class="col-md-2 text-end">
                <button type="button" class="btn btn-danger btn-sm remove-row" title="Supprimer cette affectation">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
        `;

                classYearContainer.appendChild(row);
            });

            // Recharger classes quand année académique change
            classYearContainer.addEventListener('change', function(e) {
                if (e.target.classList.contains('academic-year-select')) {
                    const row = e.target.closest('.class-academic-row');
                    const classSelect = row.querySelector('.class-select');
                    const yearId = e.target.value;
                    fetchClasses(departementId, yearId, classSelect);
                }
            });

            // Supprimer une ligne
            classYearContainer.addEventListener('click', function(e) {
                if (e.target.closest('.remove-row')) {
                    e.target.closest('.class-academic-row').remove();
                }
            });
        });
    </script>
@endsection
