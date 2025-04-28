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
