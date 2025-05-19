@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <div class="row mb-2 align-items-center">
                    <div class="col-sm-6">
                        <h1 class="h3 fw-bold text-primary">Mon Profil</h1>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-10 col-xl-8">
                        @include('_message')
                        <div class="card shadow-sm border-0 rounded-4">
                            <div class="card-header bg-primary text-white rounded-top-4">
                                <h3 class="card-title mb-0">Mettre à jour mes informations</h3>
                            </div>
                            <form method="post" action="" enctype="multipart/form-data">
                                {{ csrf_field() }}
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
                                            <select class="form-select form-control @error('gender') is-invalid @enderror"
                                                required name="gender">
                                                <option value="">Sélectionner</option>
                                                <option {{ old('gender', $getRecord->gender) == 'Male' ? 'selected' : '' }}
                                                    value="Male">Homme</option>
                                                <option
                                                    {{ old('gender', $getRecord->gender) == 'Female' ? 'selected' : '' }}
                                                    value="Female">Femme</option>
                                                <option {{ old('gender', $getRecord->gender) == 'Other' ? 'selected' : '' }}
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
                                            <label class="form-label fw-semibold">Numéro de mobile</label>
                                            <input type="text"
                                                class="form-control @error('mobile_number') is-invalid @enderror"
                                                value="{{ old('mobile_number', $getRecord->mobile_number) }}"
                                                name="mobile_number" placeholder="Mobile">
                                            @error('mobile_number')
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
                                        <div class="col-md-12">
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
                                    </div>
                                    <hr class="my-4" />
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary px-4 py-2 fw-bold">
                                            Mettre à jour
                                        </button>
                                    </div>
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
            border-radius: 1.25rem;
        }

        .card-header {
            border-radius: 1.25rem 1.25rem 0 0;
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
        }

        .btn-primary:hover {
            background: linear-gradient(90deg, #0dcaf0 0%, #0d6efd 100%);
        }
    </style>
@endsection
