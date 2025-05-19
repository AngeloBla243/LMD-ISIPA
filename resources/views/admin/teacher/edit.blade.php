@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="h3 fw-bold text-primary">
                            <i class="fa-solid fa-chalkboard-user me-2"></i>Modifier un enseignant
                        </h1>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content pb-5">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-10 col-xl-9">
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
                                            <label class="form-label fw-semibold">Genre <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select @error('gender') is-invalid @enderror" required
                                                name="gender">
                                                <option value="">Sélectionner</option>
                                                <option
                                                    {{ old('gender', $getRecord->gender) == 'Male' ? 'selected' : '' }}
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
                                                class="form-control @error('date_of_birth') is-invalid @enderror"
                                                value="{{ old('date_of_birth', $getRecord->date_of_birth) }}"
                                                name="date_of_birth" required>
                                            @error('date_of_birth')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Date d'entrée <span
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
                                            <label class="form-label fw-semibold">Statut marital</label>
                                            <input type="text"
                                                class="form-control @error('marital_status') is-invalid @enderror"
                                                value="{{ old('marital_status', $getRecord->marital_status) }}"
                                                name="marital_status" placeholder="Statut marital">
                                            @error('marital_status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Photo de profil</label>
                                            <input type="file"
                                                class="form-control @error('profile_pic') is-invalid @enderror"
                                                name="profile_pic" accept="image/*">
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
                                            <label class="form-label fw-semibold">Adresse actuelle <span
                                                    class="text-danger">*</span></label>
                                            <textarea class="form-control @error('address') is-invalid @enderror" name="address" required>{{ old('address', $getRecord->address) }}</textarea>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Adresse permanente</label>
                                            <textarea class="form-control @error('permanent_address') is-invalid @enderror" name="permanent_address">{{ old('permanent_address', $getRecord->permanent_address) }}</textarea>
                                            @error('permanent_address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Qualification</label>
                                            <textarea class="form-control @error('qualification') is-invalid @enderror" name="qualification">{{ old('qualification', $getRecord->qualification) }}</textarea>
                                            @error('qualification')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Expérience professionnelle</label>
                                            <textarea class="form-control @error('work_experience') is-invalid @enderror" name="work_experience">{{ old('work_experience', $getRecord->work_experience) }}</textarea>
                                            @error('work_experience')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Note</label>
                                            <textarea class="form-control @error('note') is-invalid @enderror" name="note">{{ old('note', $getRecord->note) }}</textarea>
                                            @error('note')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Statut <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select @error('status') is-invalid @enderror" required
                                                name="status">
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

        .btn-primary:hover {
            background: linear-gradient(90deg, #0dcaf0 0%, #0d6efd 100%);
        }

        .invalid-feedback {
            display: block;
        }
    </style>
@endsection
