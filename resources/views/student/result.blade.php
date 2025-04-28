@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <h2>Résultat de la soumission</h2>

        <div class="alert {{ $submission->plagiarism_rate < 20 ? 'alert-success' : 'alert-danger' }}">
            Taux de plagiat : <strong>{{ $submission->plagiarism_rate }}%</strong> -
            {{ $submission->plagiarism_rate < 20 ? 'Soumission acceptée 🎉' : 'Soumission refusée ❌' }}
        </div>

        <div class="mt-4">
            <a href="{{ route('thesis.download', $submission->id) }}" class="btn btn-primary">
                📥 Télécharger le mémoire
            </a>
        </div>

        @if ($submission->plagiarism_rate > 0)
            <div class="mt-4">
                <h4>Détections similaires :</h4>
                @foreach (json_decode($submission->plagiarism_results, true) as $match)
                    <div class="small text-muted">
                        Document #{{ $match['document_id'] }} -
                        Similarité : {{ $match['similarity'] }}%
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
