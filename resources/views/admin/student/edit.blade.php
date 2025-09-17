@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="h3 fw-bold text-primary">
                            <i class="fa-solid fa-user-graduate me-2"></i>Modifier un étudiant
                        </h1>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content pb-5">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-xl-10 col-lg-11">
                        <div class="card shadow-sm rounded-4 border-0">
                            <form method="post" action="" enctype="multipart/form-data" novalidate>
                                @csrf
                                <div class="card-body">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Prénom <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                value="{{ old('name', $getRecord->name) }}" name="name" required
                                                placeholder="Prénom">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Nom <span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('last_name') is-invalid @enderror"
                                                value="{{ old('last_name', $getRecord->last_name) }}" name="last_name"
                                                required placeholder="Nom">
                                            @error('last_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">N° Admission <span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('admission_number') is-invalid @enderror"
                                                value="{{ old('admission_number', $getRecord->admission_number) }}"
                                                name="admission_number" required placeholder="N° Admission">
                                            @error('admission_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">N° Appel <span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('roll_number') is-invalid @enderror"
                                                value="{{ old('roll_number', $getRecord->roll_number) }}" name="roll_number"
                                                required placeholder="N° Appel">
                                            @error('roll_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Département <span
                                                    class="text-danger">*</span></label>
                                            <select
                                                class="form-select form-control @error('departement') is-invalid @enderror"
                                                name="departement" required>
                                                <option value="">Sélectionner</option>
                                                <option
                                                    {{ $getRecord->departement == 'Informatique de gestion' ? 'selected' : '' }}
                                                    value="Informatique de gestion">Informatique de gestion</option>
                                                <option
                                                    {{ $getRecord->departement == 'Techniques de Maintenance' ? 'selected' : '' }}
                                                    value="Techniques de Maintenance">Techniques de Maintenance</option>
                                                <option
                                                    {{ $getRecord->departement == 'Gestion financière' ? 'selected' : '' }}
                                                    value="Gestion financière">Gestion financière</option>
                                                <option
                                                    {{ $getRecord->departement == 'Communication numérique' ? 'selected' : '' }}
                                                    value="Communication numérique">Communication numérique</option>
                                                <option
                                                    {{ $getRecord->departement == 'Gestion Douanière et Accises' ? 'selected' : '' }}
                                                    value="Gestion Douanière et Accises">Gestion Douanière et Accises
                                                </option>
                                            </select>
                                            @error('departement')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Département <span
                                                    class="text-danger">*</span></label>
                                            <select name="department_id"
                                                class="form-select form-control @error('department_id') is-invalid @enderror"
                                                required>
                                                <option value="">Sélectionner</option>
                                                @foreach ($departments as $department)
                                                    <option value="{{ $department->id }}"
                                                        {{ old('department_id', $getRecord->department_id ?? '') == $department->id ? 'selected' : '' }}>
                                                        {{ $department->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('department_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Genre <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select form-control @error('gender') is-invalid @enderror"
                                                required name="gender">
                                                <option value="">Sélectionner</option>
                                                <option {{ old('gender', $getRecord->gender) == 'Male' ? 'selected' : '' }}
                                                    value="Male">Homme</option>
                                                <option
                                                    {{ old('gender', $getRecord->gender) == 'Female' ? 'selected' : '' }}
                                                    value="Female">Femme</option>
                                                <option
                                                    {{ old('gender', $getRecord->gender) == 'Other' ? 'selected' : '' }}
                                                    value="Other">Autre</option>
                                            </select>
                                            @error('gender')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Date de naissance <span
                                                    class="text-danger">*</span></label>
                                            <input type="date"
                                                class="form-control form-control @error('date_of_birth') is-invalid @enderror"
                                                value="{{ old('date_of_birth', $getRecord->date_of_birth) }}"
                                                name="date_of_birth" required>
                                            @error('date_of_birth')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Caste</label>
                                            <input type="text" class="form-control @error('caste') is-invalid @enderror"
                                                value="{{ old('caste', $getRecord->caste) }}" name="caste"
                                                placeholder="Caste">
                                            @error('caste')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Religion</label>
                                            <input type="text"
                                                class="form-control @error('religion') is-invalid @enderror"
                                                value="{{ old('religion', $getRecord->religion) }}" name="religion"
                                                placeholder="Religion">
                                            @error('religion')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Téléphone</label>
                                            <input type="text"
                                                class="form-control @error('mobile_number') is-invalid @enderror"
                                                value="{{ old('mobile_number', $getRecord->mobile_number) }}"
                                                name="mobile_number" placeholder="Téléphone">
                                            @error('mobile_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Date d'admission <span
                                                    class="text-danger">*</span></label>
                                            <input type="date"
                                                class="form-control @error('admission_date') is-invalid @enderror"
                                                value="{{ old('admission_date', $getRecord->admission_date) }}"
                                                name="admission_date" required>
                                            @error('admission_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Photo de profil</label>
                                            <input type="file"
                                                class="form-control @error('profile_pic') is-invalid @enderror"
                                                name="profile_pic">
                                            @error('profile_pic')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            @if (!empty($getRecord->getProfile()))
                                                <div class="mt-2">
                                                    <img src="{{ $getRecord->getProfile() }}" class="rounded shadow-sm"
                                                        style="height: 60px;">
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Groupe sanguin</label>
                                            <input type="text"
                                                class="form-control @error('blood_group') is-invalid @enderror"
                                                name="blood_group"
                                                value="{{ old('blood_group', $getRecord->blood_group) }}"
                                                placeholder="Groupe sanguin">
                                            @error('blood_group')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Taille</label>
                                            <input type="text"
                                                class="form-control @error('height') is-invalid @enderror" name="height"
                                                value="{{ old('height', $getRecord->height) }}" placeholder="Taille">
                                            @error('height')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Poids</label>
                                            <input type="text"
                                                class="form-control @error('weight') is-invalid @enderror" name="weight"
                                                value="{{ old('weight', $getRecord->weight) }}" placeholder="Poids">
                                            @error('weight')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Statut <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select form-control @error('status') is-invalid @enderror"
                                                required name="status">
                                                <option value="">Sélectionner</option>
                                                <option {{ old('status', $getRecord->status) == 0 ? 'selected' : '' }}
                                                    value="0">Actif</option>
                                                <option {{ old('status', $getRecord->status) == 1 ? 'selected' : '' }}
                                                    value="1">Inactif</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <hr class="my-4" />

                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Email <span
                                                    class="text-danger">*</span></label>
                                            <input type="email"
                                                class="form-control @error('email') is-invalid @enderror" name="email"
                                                value="{{ old('email', $getRecord->email) }}" required
                                                placeholder="Email">
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Mot de passe</label>
                                            <input type="text"
                                                class="form-control @error('password') is-invalid @enderror"
                                                name="password" placeholder="Mot de passe">
                                            <small class="form-text text-muted">Pour changer le mot de passe, saisissez-en
                                                un nouveau.</small>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="card p-3 my-4 bg-light border-0 shadow-sm">
                                        <label class="fw-bold mb-2">Affectation Classe(s) et Année(s) académique(s)</label>
                                        <div id="classYearContainer">
                                            @foreach ($getRecord->studentClasses as $enrollment)
                                                <div class="row align-items-end mb-2 class-academic-row g-2">
                                                    <div class="col-md-5">
                                                        <select name="class_ids[]" class="form-select class-select"
                                                            required>
                                                            <option value="">Sélectionnez une classe</option>
                                                            @foreach ($getClass as $class)
                                                                <option value="{{ $class->id }}"
                                                                    {{ $enrollment->id == $class->id ? 'selected' : '' }}>
                                                                    {{ $class->name }} - {{ $class->opt }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <select name="academic_year_ids[]"
                                                            class="form-select academic-year-select" required>
                                                            <option value="">Sélectionnez une année académique
                                                            </option>
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
                                        <button type="button" class="btn btn-outline-primary btn-sm mt-2"
                                            id="addClassYearRow">
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
                    </div>
                </div>
            </div>
        </section>
    </div>

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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const departmentSelect = document.querySelector('select[name="department_id"]');
            const classYearContainer = document.getElementById('classYearContainer');

            // Fonction pour charger les classes via API
            function loadClasses(departmentId, yearId, classSelect, selectedClassId = null) {
                if (!departmentId) {
                    classSelect.innerHTML = '<option value="">Sélectionnez un département d\'abord</option>';
                    return;
                }

                // Construction dynamique de l'URL
                let url = `{{ url('admin/get-classes-by-department') }}/${departmentId}`;
                if (yearId) {
                    url = `{{ url('admin/get-classes-by-department-year') }}/${departmentId}/${yearId}`;
                }

                fetch(url)
                    .then(res => {
                        if (!res.ok) throw new Error(`HTTP error ${res.status}`);
                        return res.json();
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
                    .catch(err => {
                        console.error('Erreur:', err);
                        classSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                    });
            }

            // Recharger classes à chaque changement de département
            if (departmentSelect) {
                departmentSelect.addEventListener('change', function() {
                    const depId = this.value;
                    classYearContainer.querySelectorAll('.class-academic-row').forEach(row => {
                        const yearSelect = row.querySelector('.academic-year-select');
                        const classSelect = row.querySelector('.class-select');
                        loadClasses(depId, yearSelect.value, classSelect, classSelect.value);
                    });
                });
            }

            // Recharger classes à chaque changement d'année académique dans une ligne
            if (classYearContainer) {
                classYearContainer.addEventListener('change', function(e) {
                    if (e.target.classList.contains('academic-year-select')) {
                        const row = e.target.closest('.class-academic-row');
                        const classSelect = row.querySelector('.class-select');
                        loadClasses(departmentSelect ? departmentSelect.value : null, e.target.value,
                            classSelect, classSelect.value);
                    }
                });
            }

            // Ajouter une nouvelle ligne classe/année
            document.getElementById('addClassYearRow').addEventListener('click', function() {
                const row = document.createElement('div');
                row.className = 'row align-items-end mb-2 class-academic-row g-2';

                // Récupération dynamique des options année via Blade JSON
                const academicYearOptions = @json($academicYears->map(fn($y) => ['id' => $y->id, 'name' => $y->name]));

                let yearOptionsHTML = '<option value="">Sélectionnez une année académique</option>';
                academicYearOptions.forEach(y => {
                    yearOptionsHTML += `<option value="${y.id}">${y.name}</option>`;
                });

                row.innerHTML = `
            <div class="col-md-5">
                <select name="class_ids[]" class="form-select class-select" required>
                    <option value="">Sélectionnez une classe</option>
                </select>
            </div>
            <div class="col-md-5">
                <select name="academic_year_ids[]" class="form-select academic-year-select" required>
                    ${yearOptionsHTML}
                </select>
            </div>
            <div class="col-md-2 text-end">
                <button type="button" class="btn btn-danger btn-sm remove-row" title="Supprimer cette affectation">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
        `;

                classYearContainer.appendChild(row);

                // Charger les classes pour la nouvelle ligne
                const newClassSelect = row.querySelector('.class-select');
                const newYearSelect = row.querySelector('.academic-year-select');
                loadClasses(departmentSelect ? departmentSelect.value : null, newYearSelect.value,
                    newClassSelect);
            });

            // Supprimer une ligne classe/année
            if (classYearContainer) {
                classYearContainer.addEventListener('click', function(e) {
                    if (e.target.closest('.remove-row')) {
                        e.target.closest('.class-academic-row').remove();
                    }
                });
            }

            // Initialisation au chargement : charger les classes déjà présentes
            if (departmentSelect && classYearContainer) {
                const depIdInit = departmentSelect.value;
                classYearContainer.querySelectorAll('.class-academic-row').forEach(row => {
                    const classSelect = row.querySelector('.class-select');
                    const yearSelect = row.querySelector('.academic-year-select');
                    loadClasses(depIdInit, yearSelect.value, classSelect, classSelect.value);
                });
            }
        });
    </script>
@endsection
