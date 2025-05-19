@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="h3 fw-bold text-primary">Paramètres</h1>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content pb-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-md-10">
                        @include('_message')

                        <div class="card shadow-sm rounded-4 border-0">
                            <form method="post" action="" enctype="multipart/form-data" novalidate>
                                @csrf
                                <div class="card-body">
                                    <div class="mb-4">
                                        <label for="paypal_email" class="form-label fw-semibold">Paypal Business Email <span
                                                class="text-danger">*</span></label>
                                        <input type="email" id="paypal_email" name="paypal_email"
                                            class="form-control @error('paypal_email') is-invalid @enderror"
                                            value="{{ old('paypal_email', $getRecord->paypal_email) }}"
                                            placeholder="Paypal Business Email" required>
                                        @error('paypal_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="stripe_key" class="form-label fw-semibold">Stripe Public Key</label>
                                        <input type="text" id="stripe_key" name="stripe_key"
                                            class="form-control @error('stripe_key') is-invalid @enderror"
                                            value="{{ old('stripe_key', $getRecord->stripe_key) }}"
                                            placeholder="Stripe Public Key">
                                        @error('stripe_key')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="stripe_secret" class="form-label fw-semibold">Stripe Secret Key</label>
                                        <input type="text" id="stripe_secret" name="stripe_secret"
                                            class="form-control @error('stripe_secret') is-invalid @enderror"
                                            value="{{ old('stripe_secret', $getRecord->stripe_secret) }}"
                                            placeholder="Stripe Secret Key">
                                        @error('stripe_secret')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="logo" class="form-label fw-semibold">Logo</label>
                                        <input type="file" id="logo" name="logo"
                                            class="form-control @error('logo') is-invalid @enderror">
                                        @error('logo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        @if (!empty($getRecord->getLogo()))
                                            <img src="{{ $getRecord->getLogo() }}" alt="Logo"
                                                class="mt-2 rounded shadow-sm" style="height: 50px; object-fit: contain;">
                                        @endif
                                    </div>

                                    <div class="mb-4">
                                        <label for="fevicon_icon" class="form-label fw-semibold">Favicon Icon</label>
                                        <input type="file" id="fevicon_icon" name="fevicon_icon"
                                            class="form-control @error('fevicon_icon') is-invalid @enderror">
                                        @error('fevicon_icon')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        @if (!empty($getRecord->getFevicon()))
                                            <img src="{{ $getRecord->getFevicon() }}" alt="Favicon"
                                                class="mt-2 rounded shadow-sm" style="height: 50px; object-fit: contain;">
                                        @endif
                                    </div>

                                    <div class="mb-4">
                                        <label for="school_name" class="form-label fw-semibold">Nom de l'école</label>
                                        <input type="text" id="school_name" name="school_name"
                                            class="form-control @error('school_name') is-invalid @enderror"
                                            value="{{ old('school_name', $getRecord->school_name) }}"
                                            placeholder="Nom de l'école">
                                        @error('school_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="exam_description" class="form-label fw-semibold">Description de
                                            l'examen</label>
                                        <textarea id="exam_description" name="exam_description"
                                            class="form-control @error('exam_description') is-invalid @enderror" rows="4"
                                            placeholder="Description de l'examen">{{ old('exam_description', $getRecord->exam_description) }}</textarea>
                                        @error('exam_description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="card-footer d-flex justify-content-end bg-white border-0">
                                    <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold shadow-sm">
                                        Enregistrer
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

        .invalid-feedback {
            display: block;
        }
    </style>
@endsection
