@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="h3 fw-bold text-primary">Ajouter une nouvelle classe</h1>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content pb-5">
            <div class="container">
                <div class="card shadow-sm rounded-4 border-0 mx-auto" style="max-width: 700px;">
                    <form method="post" action="" novalidate>
                        @csrf
                        <div class="card-body">
                            <div class="mb-4">
                                <label for="academic_year_id" class="form-label fw-semibold">Année Académique <span
                                        class="text-danger">*</span></label>
                                <select id="academic_year_id" name="academic_year_id"
                                    class="form-select form-control @error('academic_year_id') is-invalid @enderror"
                                    required>
                                    <option value="">Sélectionner une année</option>
                                    @foreach ($academicYears as $year)
                                        <option value="{{ $year->id }}"
                                            {{ old('academic_year_id') == $year->id ? 'selected' : '' }}>
                                            {{ $year->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('academic_year_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="name" class="form-label fw-semibold">Nom de la classe <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="name" name="name"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                    placeholder="Nom de la classe" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="department_id">Département</label>
                                <select name="department_id" id="department_id" class="form-control" required>
                                    <option value="">Sélectionnez un département</option>
                                    @foreach ($departments as $dept)
                                        <option value="{{ $dept->id }}"
                                            @if (isset($getRecord) && $getRecord->department_id == $dept->id) selected @endif>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="opt" class="form-label fw-semibold">Option</label>
                                <select id="opt" name="opt"
                                    class="form-select form-control @error('opt') is-invalid @enderror">
                                    <option value="">Sélectionnez une option</option>
                                    <option value="Administration Réseau et Telecommunication">Administration Réseau et
                                        Télécommunication</option>
                                    <option value="Administration des Bases de données">Administration des Bases de données
                                    </option>
                                    <option value="Intelligence artificielle">Intelligence artificielle</option>
                                    <option value="Génie Logiciel">Génie Logiciel</option>
                                    <option value="Fiscalité">Fiscalité</option>
                                    <option value="Gestion Financière">Gestion Financière</option>
                                    <option value="Commerce extérieur">Commerce extérieur</option>
                                    <option value="Communication numérique">Communication numérique</option>
                                    <option value="Science informatique">Science informatique</option>
                                </select>
                                @error('opt')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>




                            <div class="mb-4">
                                <label for="amount" class="form-label fw-semibold">Montant ($) <span
                                        class="text-danger">*</span></label>
                                <input type="number" id="amount" name="amount"
                                    class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}"
                                    placeholder="Montant" required min="0" step="0.01">
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="status" class="form-label fw-semibold">Statut</label>
                                <select id="status" name="status"
                                    class="form-select form-control @error('status') is-invalid @enderror">
                                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Actif</option>
                                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Inactif</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="card-footer d-flex justify-content-end bg-white border-0">
                            <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold shadow-sm">
                                Soumettre
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

    <style>
        .card {
            border-radius: 1.5rem;
            max-width: 700px;
            margin: auto;
        }

        .form-label {
            font-weight: 600;
        }

        .form-control:focus,
        .form-select:focus {
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
