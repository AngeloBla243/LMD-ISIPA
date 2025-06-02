@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="h3 fw-bold text-primary">Mon Compte</h1>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content pb-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">

                        @include('_message')

                        <div class="card shadow-sm rounded-4 border-0">
                            <form method="post" action="" enctype="multipart/form-data" novalidate>
                                @csrf
                                <div class="card-body">
                                    <div class="row g-3">

                                        <div class="col-md-6">
                                            <label for="name" class="form-label fw-semibold">Prénom <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="name" name="name"
                                                class="form-control @error('name') is-invalid @enderror"
                                                value="{{ old('name', $getRecord->name) }}" placeholder="Prénom" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="last_name" class="form-label fw-semibold">Nom <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="last_name" name="last_name"
                                                class="form-control @error('last_name') is-invalid @enderror"
                                                value="{{ old('last_name', $getRecord->last_name) }}" placeholder="Nom"
                                                required>
                                            @error('last_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="gender" class="form-label fw-semibold">Genre <span
                                                    class="text-danger">*</span></label>
                                            <select id="gender" name="gender"
                                                class="form-select @error('gender') is-invalid @enderror" required>
                                                <option value="">Sélectionner</option>
                                                <option value="Male"
                                                    {{ old('gender', $getRecord->gender) == 'Male' ? 'selected' : '' }}>
                                                    Homme</option>
                                                <option value="Female"
                                                    {{ old('gender', $getRecord->gender) == 'Female' ? 'selected' : '' }}>
                                                    Femme</option>
                                                <option value="Other"
                                                    {{ old('gender', $getRecord->gender) == 'Other' ? 'selected' : '' }}>
                                                    Autre</option>
                                            </select>
                                            @error('gender')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="date_of_birth" class="form-label fw-semibold">Date de naissance
                                                <span class="text-danger">*</span></label>
                                            <input type="date" id="date_of_birth" name="date_of_birth"
                                                class="form-control @error('date_of_birth') is-invalid @enderror"
                                                value="{{ old('date_of_birth', $getRecord->date_of_birth) }}" required>
                                            @error('date_of_birth')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="mobile_number" class="form-label fw-semibold">Téléphone</label>
                                            <input type="text" id="mobile_number" name="mobile_number"
                                                class="form-control @error('mobile_number') is-invalid @enderror"
                                                value="{{ old('mobile_number', $getRecord->mobile_number) }}"
                                                placeholder="Téléphone">
                                            @error('mobile_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="marital_status" class="form-label fw-semibold">Situation
                                                familiale</label>
                                            <input type="text" id="marital_status" name="marital_status"
                                                class="form-control @error('marital_status') is-invalid @enderror"
                                                value="{{ old('marital_status', $getRecord->marital_status) }}"
                                                placeholder="Situation familiale">
                                            @error('marital_status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="profile_pic" class="form-label fw-semibold">Photo de profil</label>
                                            <input type="file" id="profile_pic" name="profile_pic"
                                                class="form-control @error('profile_pic') is-invalid @enderror">
                                            @error('profile_pic')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            @if (!empty($getRecord->getProfile()))
                                                <img src="{{ $getRecord->getProfile() }}" alt="Profile Pic"
                                                    class="mt-2 rounded-circle"
                                                    style="height: 60px; width: 60px; object-fit: cover;">
                                            @endif
                                        </div>

                                        <div class="col-md-6">
                                            <label for="address" class="form-label fw-semibold">Adresse actuelle <span
                                                    class="text-danger">*</span></label>
                                            <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror" rows="3"
                                                required>{{ old('address', $getRecord->address) }}</textarea>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="permanent_address" class="form-label fw-semibold">Adresse
                                                permanente</label>
                                            <textarea id="permanent_address" name="permanent_address"
                                                class="form-control @error('permanent_address') is-invalid @enderror" rows="3">{{ old('permanent_address', $getRecord->permanent_address) }}</textarea>
                                            @error('permanent_address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="qualification"
                                                class="form-label fw-semibold">Qualification</label>
                                            <textarea id="qualification" name="qualification" class="form-control @error('qualification') is-invalid @enderror"
                                                rows="3">{{ old('qualification', $getRecord->qualification) }}</textarea>
                                            @error('qualification')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="work_experience" class="form-label fw-semibold">Expérience
                                                professionnelle</label>
                                            <textarea id="work_experience" name="work_experience"
                                                class="form-control @error('work_experience') is-invalid @enderror" rows="3">{{ old('work_experience', $getRecord->work_experience) }}</textarea>
                                            @error('work_experience')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <hr>
                                        </div>

                                        <div class="col-md-12">
                                            <label for="email" class="form-label fw-semibold">Email <span
                                                    class="text-danger">*</span></label>
                                            <input type="email" id="email" name="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                value="{{ old('email', $getRecord->email) }}" placeholder="Email"
                                                required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                    </div>
                                </div>

                                <div class="card-footer d-flex justify-content-end bg-white border-0">
                                    <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold shadow-sm">Mettre
                                        à jour</button>
                                </div>
                            </form>
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
        .form-select:focus,
        textarea.form-control:focus {
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

        img.rounded-circle {
            border: 2px solid #0d6efd;
            box-shadow: 0 2px 8px rgba(13, 110, 253, 0.15);
        }
    </style>
@endsection
