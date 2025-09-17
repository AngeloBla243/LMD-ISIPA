@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="h3 fw-bold text-primary">
                            <i class="fa-solid fa-user-plus me-2"></i>Ajouter un nouvel étudiant
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
                                                value="{{ old('name') }}" name="name" required placeholder="Prénom">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Nom <span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('last_name') is-invalid @enderror"
                                                value="{{ old('last_name') }}" name="last_name" required placeholder="Nom">
                                            @error('last_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">N° Admission <span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('admission_number') is-invalid @enderror"
                                                value="{{ old('admission_number') }}" name="admission_number" required
                                                placeholder="N° Admission">
                                            @error('admission_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">N° Appel <span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('roll_number') is-invalid @enderror"
                                                value="{{ old('roll_number') }}" name="roll_number" required
                                                placeholder="N° Appel">
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
                                                <option value="">Choisir le département</option>
                                                <option value="Informatique de gestion">Informatique de gestion</option>
                                                <option value="Techniques de Maintenance">Techniques de Maintenance</option>
                                                <option value="Gestion financière">Gestion financière</option>
                                                <option value="Communication numérique">Communication numérique</option>
                                                <option value="Gestion Douanière et Accises">Gestion Douanière et Accises
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
                                                <option value="">Choisir le département</option>
                                                @foreach ($departments as $department)
                                                    <option value="{{ $department->id }}"
                                                        {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                                        {{ $department->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('department_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        {{-- <div class="col-md-6">
                                            <label class="form-label fw-semibold">Classe <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select @error('class_id') is-invalid @enderror"
                                                name="class_id" required>
                                                <option value="">Sélectionner la classe</option>
                                                @foreach ($getClass as $value)
                                                    <option {{ old('class_id') == $value->id ? 'selected' : '' }}
                                                        value="{{ $value->id }}">
                                                        {{ $value->name }} {{ $value->opt }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('class_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div> --}}
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Genre <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select form-control @error('gender') is-invalid @enderror"
                                                name="gender" required>
                                                <option value="">Sélectionner le genre</option>
                                                <option {{ old('gender') == 'Male' ? 'selected' : '' }} value="Male">
                                                    Homme</option>
                                                <option {{ old('gender') == 'Female' ? 'selected' : '' }} value="Female">
                                                    Femme</option>
                                                <option {{ old('gender') == 'Other' ? 'selected' : '' }} value="Other">
                                                    Autre</option>
                                            </select>
                                            @error('gender')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Date de naissance <span
                                                    class="text-danger">*</span></label>
                                            <input type="date"
                                                class="form-control @error('date_of_birth') is-invalid @enderror"
                                                value="{{ old('date_of_birth') }}" name="date_of_birth" required>
                                            @error('date_of_birth')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Caste</label>
                                            <input type="text" class="form-control @error('caste') is-invalid @enderror"
                                                value="{{ old('caste') }}" name="caste" placeholder="Caste">
                                            @error('caste')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Religion</label>
                                            <input type="text"
                                                class="form-control @error('religion') is-invalid @enderror"
                                                value="{{ old('religion') }}" name="religion" placeholder="Religion">
                                            @error('religion')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Téléphone</label>
                                            <input type="text"
                                                class="form-control @error('mobile_number') is-invalid @enderror"
                                                value="{{ old('mobile_number') }}" name="mobile_number"
                                                placeholder="Téléphone">
                                            @error('mobile_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Date d'admission <span
                                                    class="text-danger">*</span></label>
                                            <input type="date"
                                                class="form-control @error('admission_date') is-invalid @enderror"
                                                value="{{ old('admission_date') }}" name="admission_date" required>
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
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Groupe sanguin</label>
                                            <input type="text"
                                                class="form-control @error('blood_group') is-invalid @enderror"
                                                name="blood_group" value="{{ old('blood_group') }}"
                                                placeholder="Groupe sanguin">
                                            @error('blood_group')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Taille</label>
                                            <input type="text"
                                                class="form-control @error('height') is-invalid @enderror" name="height"
                                                value="{{ old('height') }}" placeholder="Taille">
                                            @error('height')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Poids</label>
                                            <input type="text"
                                                class="form-control @error('weight') is-invalid @enderror" name="weight"
                                                value="{{ old('weight') }}" placeholder="Poids">
                                            @error('weight')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Statut <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select form-control @error('status') is-invalid @enderror"
                                                name="status" required>
                                                <option value="">Sélectionner le statut</option>
                                                <option {{ old('status') == 0 ? 'selected' : '' }} value="0">Actif
                                                </option>
                                                <option {{ old('status') == 1 ? 'selected' : '' }} value="1">Inactif
                                                </option>
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
                                                value="{{ old('email') }}" required placeholder="Email">
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Mot de passe <span
                                                    class="text-danger">*</span></label>
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                name="password" required placeholder="Mot de passe">
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer d-flex justify-content-end bg-white border-0">
                                    <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold shadow-sm">
                                        <i class="fa-solid fa-paper-plane me-2"></i>Soumettre
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

        .btn-primary:hover {
            background: linear-gradient(90deg, #0dcaf0 0%, #0d6efd 100%);
        }

        .invalid-feedback {
            display: block;
        }
    </style>
@endsection
