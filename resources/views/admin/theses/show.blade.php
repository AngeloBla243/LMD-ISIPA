@extends('layouts.app')

@section('style')
    <style type="text/css">
        .pre-scrollable {
            white-space: pre-wrap;
            word-wrap: break-word;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }

        .card-header.bg-warning {
            font-weight: bold;
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <div class="row align-items-center mb-3">
                    <div class="col-md-6">
                        <h1 class="h3 fw-bold text-primary">Détails de la soumission</h1>
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="{{ route('admin.theses.index') }}" class="btn btn-secondary shadow-sm rounded-3">
                            <i class="fas fa-arrow-left me-2"></i> Retour à la liste
                        </a>
                    </div>
                </div>

                <div class="card shadow-sm rounded-4 border-0">
                    <div class="card-body">
                        <form action="{{ route('admin.theses.update', $submission->id) }}" method="POST" novalidate>
                            @csrf
                            @method('PUT')

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Étudiant :</label>
                                    <p class="form-control-plaintext fs-5">
                                        {{ $submission->student->name ?? 'N/A' }}
                                        {{ $submission->student->last_name ?? '' }}
                                    </p>
                                </div>

                                <div class="col-md-6">
                                    <label for="subject" class="form-label fw-semibold">Sujet :</label>
                                    <input type="text" id="subject" name="subject"
                                        class="form-control @error('subject') is-invalid @enderror"
                                        value="{{ $submission->subject }}" required>
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row g-4 mt-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Taux de plagiat :</label>
                                    <p class="form-control-plaintext fs-5">{{ $submission->plagiarism_rate }}%</p>
                                </div>

                                <div class="col-md-4">
                                    <label for="status" class="form-label fw-semibold">Statut :</label>
                                    <select id="status" name="status"
                                        class="form-select @error('status') is-invalid @enderror"
                                        {{ $submission->status === 'accepted' ? 'disabled' : '' }}>
                                        <option value="pending" {{ $submission->status === 'pending' ? 'selected' : '' }}>En
                                            attente</option>
                                        <option value="accepted" {{ $submission->status === 'accepted' ? 'selected' : '' }}>
                                            Accepté</option>
                                        <option value="rejected"
                                            {{ $submission->status === 'rejected' ? 'selected' : '' }}>Rejeté</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 d-flex flex-column align-items-start">
                                    <label class="form-label fw-semibold mb-2">Téléchargement :</label>
                                    <a href="{{ route('admin.theses.download', $submission->id) }}" class="btn btn-primary"
                                        target="_blank" rel="noopener">
                                        <i class="fas fa-download me-2"></i> Télécharger le mémoire
                                    </a>
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="form-label fw-semibold">Contenu extrait :</label>
                                <div class="card border rounded-3">
                                    <div class="card-body pre-scrollable"
                                        style="max-height: 300px; white-space: pre-wrap; font-family: monospace;">
                                        {!! nl2br(e($submission->content)) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-success btn-lg shadow-sm px-5">
                                    <i class="fas fa-save me-2"></i> Enregistrer les modifications
                                </button>
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
        .form-select:focus {
            border-color: #198754;
            box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
        }

        .btn-success {
            background: linear-gradient(90deg, #198754 60%, #20c997 100%);
            border: none;
            transition: background 0.3s ease;
        }

        .btn-success:hover {
            background: linear-gradient(90deg, #20c997 0%, #198754 100%);
        }

        .pre-scrollable {
            overflow-y: auto;
        }
    </style>
@endsection



@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire({
                    title: 'Succès',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonText: 'Retour à la liste',
                    cancelButtonText: 'Rester ici',
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('admin.theses.index') }}";
                    }
                    // Sinon, on reste sur la page !
                });
            @endif
        });
    </script>
@endsection
