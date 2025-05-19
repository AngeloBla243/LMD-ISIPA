@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content py-4">
            <div class="container">
                <h2 class="mb-4 text-primary fw-bold">Soumission de mémoire</h2>

                <div class="card shadow-sm rounded-4 border-0">
                    <div class="card-header bg-primary text-white rounded-top-4">
                        <h4 class="mb-0">Formulaire de soumission</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('thesis.submit') }}" enctype="multipart/form-data" novalidate>
                            @csrf

                            <div class="mb-3">
                                <label for="subject" class="form-label fw-semibold">Sujet du mémoire <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="subject" name="subject"
                                    class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject') }}"
                                    required>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="thesis_file" class="form-label fw-semibold">Fichier PDF ou DOCX <span
                                        class="text-danger">*</span></label>
                                <input type="file" id="thesis_file" name="thesis_file"
                                    class="form-control @error('thesis_file') is-invalid @enderror" accept=".pdf,.docx"
                                    required>
                                @error('thesis_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow-sm">
                                Soumettre
                            </button>
                        </form>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger mt-4 rounded-3 shadow-sm">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
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

        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .btn-primary {
            background: linear-gradient(90deg, #0d6efd 60%, #0dcaf0 100%);
            border: none;
            transition: background 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(90deg, #0dcaf0 0%, #0d6efd 100%);
        }

        .alert-danger ul {
            margin-bottom: 0;
        }
    </style>

@endsection
