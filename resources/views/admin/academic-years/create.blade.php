@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="h3 fw-bold text-primary">
                            {{ isset($academicYear) ? 'Modifier' : 'Ajouter' }} une année académique
                        </h1>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content pb-5">
            <div class="container">
                <div class="card shadow-sm rounded-4 border-0 mx-auto" style="max-width: 600px;">
                    <form
                        action="{{ isset($academicYear) ? route('admin.academic-years.update', $academicYear) : route('admin.academic-years.store') }}"
                        method="POST" novalidate>
                        @csrf
                        @if (isset($academicYear))
                            @method('PUT')
                        @endif

                        <div class="card-body">
                            <div class="mb-4">
                                <label for="name" class="form-label fw-semibold">Nom (ex: 2024-2025) <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $academicYear->name ?? '') }}"
                                    required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="start_date" class="form-label fw-semibold">Date de début <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                    id="start_date" name="start_date"
                                    value="{{ old('start_date', isset($academicYear) ? $academicYear->start_date->format('Y-m-d') : '') }}"
                                    required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="end_date" class="form-label fw-semibold">Date de fin <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                    id="end_date" name="end_date"
                                    value="{{ old('end_date', isset($academicYear) ? $academicYear->end_date->format('Y-m-d') : '') }}"
                                    required>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold shadow-sm">
                                    Enregistrer
                                </button>
                                <a href="{{ route('admin.academic-years.index') }}"
                                    class="btn btn-secondary px-4 py-2 fw-semibold shadow-sm">
                                    Annuler
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

    <style>
        .card {
            border-radius: 1.5rem;
            max-width: 600px;
            margin: auto;
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

        .btn-secondary {
            border: none;
            background-color: #6c757d;
            color: white;
            transition: background-color 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            color: white;
        }

        .invalid-feedback {
            display: block;
        }
    </style>
@endsection
