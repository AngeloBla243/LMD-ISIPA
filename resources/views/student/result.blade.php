@extends('layouts.app')

@section('content')
    <div class="content-wrapper">

        <section class="content">
            <h2>Résultat de la soumission</h2>

            <div class="alert {{ $submission->plagiarism_rate < 20 ? 'alert-success' : 'alert-danger' }}">
                Taux de plagiat : <strong>{{ $submission->plagiarism_rate }}%</strong> -
                {{ $submission->plagiarism_rate < 20 ? 'Soumission acceptée 🎉' : 'Soumission refusée ❌' }}
            </div>

            <div class="mt-4">
                {{-- Le bouton de téléchargement, on empêche le comportement normal pour gérer l'alerte --}}
                {{-- <a href="{{ route('downloadReport', $submission->id) }}" id="downloadLink" class="btn btn-primary">
                    📥 Télécharger l'Autorisation de dépôt
                </a> --}}


                <button type="button" id="downloadButton" data-url="{{ route('downloadReport', $submission->id) }}"
                    class="btn btn-primary">
                    📥 Télécharger l'Autorisation de dépôt
                </button>

            </div>

            @php
                $matches = json_decode($submission->plagiarism_results, true) ?? [];
            @endphp

            @if (count($matches) > 0)
                <div class="mt-4">
                    <h4>Détections similaires :</h4>
                    <ul>
                        @foreach ($matches as $match)
                            <li class="mb-3">
                                <strong>Phrase du mémoire :</strong><br>
                                <q>{{ $match['phrase'] ?? 'Non disponible' }}</q><br>
                                <strong>Similarité :</strong> {{ $match['similarity'] ?? '0' }}%<br>
                                <strong>Document #{{ $match['document_id'] ?? 'N/A' }} :</strong><br>
                                <q>{{ $match['matched_sentence'] ?? 'Extrait non disponible' }}</q>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <p class="mt-4"><em>Aucune détection de similarité trouvée.</em></p>
            @endif
        </section>
    </div>

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
