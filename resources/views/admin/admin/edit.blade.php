@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="h3 fw-bold text-primary">Modifier un admin</h1>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content pb-5">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-md-6 col-lg-5">
                        <div class="card shadow-sm rounded-4 border-0">
                            <form method="post" action="" enctype="multipart/form-data" novalidate>
                                @csrf
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="name" class="form-label fw-semibold">Nom <span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="name" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name', $getRecord->name) }}" placeholder="Nom" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label fw-semibold">Email <span
                                                class="text-danger">*</span></label>
                                        <input type="email" id="email" name="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email', $getRecord->email) }}" placeholder="Email" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label fw-semibold">Mot de passe</label>
                                        <input type="text" id="password" name="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="Nouveau mot de passe">
                                        <small class="form-text text-muted">Si vous souhaitez changer le mot de passe,
                                            veuillez saisir un nouveau.</small>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="profile_pic" class="form-label fw-semibold">Photo de profil</label>
                                        <input type="file" id="profile_pic" name="profile_pic"
                                            class="form-control @error('profile_pic') is-invalid @enderror"
                                            accept="image/*">
                                        @error('profile_pic')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        @if (!empty($getRecord->getProfile()))
                                            <div class="mt-3">
                                                <img src="{{ $getRecord->getProfile() }}" alt="Photo de profil"
                                                    class="rounded shadow-sm" style="height: 50px; object-fit: cover;">
                                            </div>
                                        @endif
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

        .form-label {
            font-weight: 600;
        }

        .form-control:focus {
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
