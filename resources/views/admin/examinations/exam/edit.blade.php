@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="h3 fw-bold text-primary">Modifier un examen</h1>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content pb-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-7">
                        <div class="card shadow-sm rounded-4 border-0">
                            <form method="post" action="" novalidate>
                                @csrf
                                <div class="card-body">
                                    <div class="mb-4">
                                        <label for="name" class="form-label fw-semibold">Nom de l'examen <span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="name" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name', $getRecord->name) }}" placeholder="Nom de l'examen"
                                            required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="academic_year_id" class="form-label fw-semibold">Année Académique <span
                                                class="text-danger">*</span></label>
                                        <select id="academic_year_id" name="academic_year_id"
                                            class="form-select @error('academic_year_id') is-invalid @enderror" required>
                                            <option value="">Sélectionner une année</option>
                                            @foreach ($academicYears as $year)
                                                <option value="{{ $year->id }}"
                                                    {{ old('academic_year_id', $getRecord->academic_year_id) == $year->id ? 'selected' : '' }}>
                                                    {{ $year->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('academic_year_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="note" class="form-label fw-semibold">Note</label>
                                        <textarea id="note" name="note" class="form-control @error('note') is-invalid @enderror" placeholder="Note"
                                            rows="4">{{ old('note', $getRecord->note) }}</textarea>
                                        @error('note')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
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
            max-width: 600px;
            margin: auto;
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
