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
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h1>Détails de la soumission</h1>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="{{ route('admin.theses.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour à la liste
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.theses.update', $submission->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><strong>Étudiant :</strong></label>
                                        <p class="form-control-plaintext">
                                            {{ $submission->student->name ?? 'N/A' }}
                                            {{ $submission->student->last_name ?? '' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><strong>Sujet :</strong></label>
                                        <input type="text" name="subject" class="form-control"
                                            value="{{ $submission->subject }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><strong>Taux de plagiat :</strong></label>
                                        <p class="form-control-plaintext">{{ $submission->plagiarism_rate }}%</p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><strong>Statut :</strong></label>
                                        <select name="status" class="form-control"
                                            {{ $submission->status === 'accepted' ? 'disabled' : '' }}>
                                            <option value="pending"
                                                {{ $submission->status === 'pending' ? 'selected' : '' }}>En
                                                attente</option>
                                            <option value="accepted"
                                                {{ $submission->status === 'accepted' ? 'selected' : '' }}>
                                                Accepté</option>
                                            <option value="rejected"
                                                {{ $submission->status === 'rejected' ? 'selected' : '' }}>
                                                Rejeté</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><strong>Téléchargement :</strong></label>
                                        <a href="{{ route('admin.theses.download', $submission->id) }}"
                                            class="btn btn-primary" target="_blank" rel="noopener">
                                            <i class="fas fa-download"></i> Télécharger le mémoire
                                        </a>


                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><strong>Contenu extrait :</strong></label>
                                <div class="card border">
                                    <div class="card-body pre-scrollable" style="max-height: 300px;">
                                        {!! nl2br(e($submission->content)) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-save"></i> Enregistrer les modifications
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- @if ($submission->plagiarism_results)
                    <div class="card mt-4">
                        <div class="card-header bg-warning">
                            <h3 class="card-title">Détections de similarité</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach (json_decode($submission->plagiarism_results, true) as $match)
                                    <div class="col-md-6 mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="text-danger">Similarité : {{ $match['similarity'] ?? 0 }}%</h5>
                                                <div class="bg-light p-3 mb-2">
                                                    <small>Phrase du mémoire :</small><br>
                                                    "{{ $match['phrase'] ?? 'N/A' }}"
                                                </div>
                                                <div class="bg-light p-3">
                                                    <small>Correspondance trouvée :</small><br>
                                                    "{{ $match['matched_sentence'] ?? 'N/A' }}"
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif --}}
            </div>
        </section>
    </div>
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
