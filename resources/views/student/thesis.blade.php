@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content py-4">
            <div class="container">
                <h2 class="mb-4 text-primary fw-bold">Soumission de mémoire/projet</h2>

                <div class="card shadow-sm rounded-4 border-0">
                    <div class="card-header bg-primary text-white rounded-top-4">
                        <h4 class="mb-0">Type de soumission</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('thesis.submit') }}" enctype="multipart/form-data" novalidate>
                            @csrf

                            <!-- Sélection du type -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Type de soumission <span
                                        class="text-danger">*</span></label>
                                <select name="type" id="submission_type" class="form-select form-control" required>
                                    <option value="">Choisir le type...</option>
                                    <option value="1" {{ old('type') == 1 ? 'selected' : '' }}>Mémoire</option>
                                    <option value="2" {{ old('type') == 2 ? 'selected' : '' }}>Projet</option>
                                </select>
                            </div>

                            <!-- Section Mémoire -->
                            <div id="memoir-section" style="display: none;">
                                <div class="mb-3">
                                    <label for="subject" class="form-label fw-semibold">Sujet du mémoire <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="subject" name="subject"
                                        class="form-control @error('subject') is-invalid @enderror"
                                        value="{{ old('subject') }}">
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Directeur de mémoire <span
                                            class="text-danger">*</span></label>
                                    <select name="directeur_id"
                                        class="form-select form-control @error('directeur_id') is-invalid @enderror">
                                        <option value="">Choisir un directeur</option>
                                        @foreach ($professeurs as $prof)
                                            <option value="{{ $prof->id }}"
                                                {{ old('directeur_id') == $prof->id ? 'selected' : '' }}>
                                                {{ $prof->name }} {{ $prof->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('directeur_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Section Projet -->
                            <div id="project-section" style="display: none;">
                                <div class="mb-3">
                                    <label for="project_name" class="form-label fw-semibold">Nom du projet <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="project_name" name="project_name"
                                        class="form-control @error('project_name') is-invalid @enderror"
                                        value="{{ old('project_name') }}">
                                    @error('project_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Encadreur du projet <span
                                            class="text-danger">*</span></label>
                                    <select name="encadreur_id"
                                        class="form-select form-control @error('encadreur_id') is-invalid @enderror">
                                        <option value="">Choisir un encadreur</option>
                                        @foreach ($encadreurs as $encadreur)
                                            <option value="{{ $encadreur->id }}"
                                                {{ old('encadreur_id') == $encadreur->id ? 'selected' : '' }}>
                                                {{ $encadreur->name }} {{ $encadreur->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('encadreur_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Champ commun -->
                            <div class="mb-4">
                                <label for="thesis_file" class="form-label fw-semibold">Fichier (PDF/DOCX) <span
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('submission_type');
            const memoireSection = document.getElementById('memoir-section');
            const projetSection = document.getElementById('project-section');

            function toggleSections() {
                const value = typeSelect.value;
                memoireSection.style.display = value === '1' ? 'block' : 'none';
                projetSection.style.display = value === '2' ? 'block' : 'none';
            }

            typeSelect.addEventListener('change', toggleSections);
            toggleSections(); // Initial call
        });
    </script>


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
