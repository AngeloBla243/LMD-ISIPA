@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content py-4">
            <div class="container">
                <h2 class="mb-4 text-primary fw-bold">Résultat de la soumission</h2>

                <div
                    class="alert {{ $submission->plagiarism_rate < 20 ? 'alert-success' : 'alert-danger' }} shadow-sm rounded-3 d-flex align-items-center gap-3">
                    <div style="font-size: 1.5rem;">
                        {!! $submission->plagiarism_rate < 20 ? '🎉' : '❌' !!}
                    </div>
                    <div>
                        <strong>Taux de plagiat :</strong> {{ $submission->plagiarism_rate }}%<br>
                        <span class="fw-semibold">
                            {{ $submission->plagiarism_rate < 20 ? 'Soumission acceptée' : 'Soumission refusée' }}
                        </span>
                    </div>
                </div>

                <div class="mb-4">
                    <button type="button" id="downloadButton" data-url="{{ route('downloadReport', $submission->id) }}"
                        class="btn btn-primary px-4 py-2 fw-semibold shadow-sm">
                        <i class="fas fa-download me-2"></i> Télécharger l'Autorisation de dépôt
                    </button>
                </div>

                @php
                    $matches = json_decode($submission->plagiarism_results, true) ?? [];
                @endphp

                @if (count($matches) > 0)
                    <div class="mt-4">
                        <h4 class="mb-3 text-secondary fw-semibold">Détections similaires :</h4>
                        <ul class="list-group">
                            @foreach ($matches as $match)
                                <li class="list-group-item mb-3 rounded-3 shadow-sm">
                                    <p class="mb-2"><strong>Phrase du mémoire :</strong></p>
                                    <blockquote class="blockquote ps-3 border-start border-4 border-primary fst-italic">
                                        {{ $match['phrase'] ?? 'Non disponible' }}
                                    </blockquote>

                                    <p class="mb-1"><strong>Similarité :</strong> <span
                                            class="badge bg-info text-dark">{{ $match['similarity'] ?? '0' }}%</span></p>

                                    <p class="mb-1"><strong>Document #{{ $match['document_id'] ?? 'N/A' }} :</strong></p>
                                    <blockquote
                                        class="blockquote ps-3 border-start border-4 border-secondary fst-italic text-muted">
                                        {{ $match['matched_sentence'] ?? 'Extrait non disponible' }}
                                    </blockquote>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <p class="mt-4 fst-italic text-muted">Aucune détection de similarité trouvée.</p>
                @endif
            </div>
        </section>
    </div>

    <style>
        .alert {
            font-size: 1.1rem;
        }

        blockquote {
            margin-bottom: 0.75rem;
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

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log("Script SweetAlert2 prêt...");

            const downloadBtn = document.getElementById('downloadButton');
            if (!downloadBtn) {
                console.error('Bouton téléchargement introuvable');
                return;
            }

            const plagiarismRate = {{ $submission->plagiarism_rate }};
            console.log("Taux de plagiat:", plagiarismRate);

            downloadBtn.addEventListener('click', function() {
                if (plagiarismRate < 20) {
                    Swal.fire({
                        icon: 'question',
                        title: 'Confirmer le téléchargement',
                        text: "Voulez-vous vraiment télécharger l'autorisation ?",
                        showCancelButton: true,
                        confirmButtonText: 'Oui, télécharger',
                        cancelButtonText: 'Annuler',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Téléchargement en cours',
                                text: "Votre autorisation est bien téléchargée.",
                                timer: 1800,
                                timerProgressBar: true,
                                showConfirmButton: false,
                                willClose: () => {
                                    const url = downloadBtn.getAttribute('data-url');
                                    window.location.href = url;
                                }
                            });
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Téléchargement refusé',
                        text: "Vous avez dépassé le taux de plagiat requis, veuillez refaire votre travail avec vos propres mots.",
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    </script>
@endsection
