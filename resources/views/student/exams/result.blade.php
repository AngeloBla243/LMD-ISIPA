@extends('layouts.app')

@section('style')
    <style>
        .content-wrapper {
            padding-top: 2rem;
            padding-bottom: 2rem;
        }

        h2 {
            color: #2176bd;
            font-weight: 700;
            margin-bottom: 1.8rem;
            text-align: center;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 8px 24px rgba(14, 74, 107, 0.07);
            border: none;
            margin-bottom: 2rem;
        }

        .card-body h4,
        .card-body h5 {
            color: #2176bd;
            font-weight: 600;
        }

        .question-card {
            border: 1px solid #dde6f7;
            border-radius: 15px;
            padding: 1.5rem 1.8rem;
            margin-bottom: 1.8rem;
            background: #fff;
            box-shadow: 0 2px 12px rgba(14, 74, 107, 0.04);
        }

        .question-card h5 {
            font-weight: 700;
            margin-bottom: 0.7rem;
            color: #0e4a6b;
        }

        .question-text {
            font-size: 1.1rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        ul.choice-list {
            list-style-type: none;
            padding-left: 0;
            margin: 0;
        }

        ul.choice-list li {
            padding: 0.6rem 1rem;
            border-radius: 10px;
            margin-bottom: 0.5rem;
            background: #f8faff;
            font-size: 1rem;
            transition: background-color 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        /* Bonne réponse */
        ul.choice-list li.correct {
            font-weight: 700;
            color: #2e7d32;
            /* Vert foncé */
            background-color: #d0f0d9;
        }

        /* Mauvais choix de l'étudiant */
        ul.choice-list li.wrong-choice {
            color: #b00020;
            /* Rouge sombre */
            background-color: #f9d6d5;
        }

        /* Le choix de l'étudiant (peut être correct ou pas) */
        ul.choice-list li.student-choice {
            border: 2px solid #2196f3;
            /* Bleu clair */
            box-shadow: 0 0 6px #2196f3;
            font-weight: 600;
        }

        /* Badges */
        .badge {
            font-size: 0.75rem;
            padding: 0.25em 0.6em;
            border-radius: 12px;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            flex-shrink: 0;
            box-shadow: 0 1px 6px rgba(0, 0, 0, 0.1);
        }

        .badge.bg-success {
            background-color: #2e7d32 !important;
            color: #fff !important;
        }

        .badge.bg-info {
            background-color: #2196f3 !important;
            color: #fff !important;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .question-card {
                padding: 1rem 1.2rem;
            }

            ul.choice-list li {
                font-size: 0.95rem;
                padding: 0.5rem 0.8rem;
                gap: 0.4rem;
            }

            h2 {
                font-size: 1.6rem;
                padding: 0 1rem;
            }

            .card-body h4 {
                font-size: 1.2rem;
            }

            .card-body h5 {
                font-size: 1rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="container" style="max-width: 720px;">
            <h2>Résultat pour l'examen : <span class="text-primary">{{ $exam->title }}</span></h2>

            <div class="card shadow mb-4">
                <div class="card-body text-center">
                    <h4>
                        Score obtenu : <span class="text-primary">{{ $attempt->score }}</span>
                    </h4>
                    <h5>
                        Date de soumission : {{ \Carbon\Carbon::parse($attempt->submitted_at)->format('d/m/Y H:i') }}
                    </h5>
                </div>
            </div>

            @foreach ($exam->questions as $index => $question)
                @php
                    $studentResponse = $responses[$question->id] ?? null;
                @endphp
                <div class="question-card">
                    <h5>Question #{{ $index + 1 }} ({{ $question->points }} points)</h5>
                    <p class="question-text">{{ $question->question_text }}</p>
                    <ul class="choice-list">
                        @foreach ($question->choices as $choice)
                            @php
                                $classes = [];
                                // Marquer bonne réponse
                                if ($choice->is_correct) {
                                    $classes[] = 'correct';
                                }
                                // Marquer choix de l'étudiant (si ce choix)
if ($studentResponse && $studentResponse->choice_id == $choice->id) {
    $classes[] = 'student-choice';
    // Si mauvais choix, ajouter un rouge
    if (!$choice->is_correct) {
        $classes[] = 'wrong-choice';
                                    }
                                }
                            @endphp
                            <li class="{{ implode(' ', $classes) }}">
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
