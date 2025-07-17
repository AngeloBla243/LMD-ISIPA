@extends('layouts.app')

@section('content')
    <div class="content-wrapper py-4">
        <div class="container">
            <h2>Résultat pour l'examen : {{ $exam->title }}</h2>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <h4>Score obtenu : <span class="text-primary">{{ $attempt->score }}</span></h4>
                    <h5>Date de soumission : {{ \Carbon\Carbon::parse($attempt->submitted_at)->format('d/m/Y H:i') }}</h5>
                </div>
            </div>

            @foreach ($exam->questions as $index => $question)
                <div class="mb-4 border p-3">
                    <h5>Question #{{ $index + 1 }} ({{ $question->points }} points)</h5>
                    <p class="fw-bold">{{ $question->question_text }}</p>
                    @php
                        $studentResponse = $responses[$question->id] ?? null;
                    @endphp
                    <ul>
                        @foreach ($question->choices as $choice)
                            <li @if ($choice->is_correct) style="font-weight:bold;color:green;" @endif
                                @if ($studentResponse && $studentResponse->choice_id == $choice->id && !$choice->is_correct) style="color:red;" @endif>
                                {{ $choice->choice_text }}
                                @if ($choice->is_correct)
                                    <span class="badge bg-success">Bonne réponse</span>
                                @endif
                                @if ($studentResponse && $studentResponse->choice_id == $choice->id)
                                    <span class="badge bg-info">Votre choix</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
@endsection
