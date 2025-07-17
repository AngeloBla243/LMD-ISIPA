{{-- @extends('layouts.app')

@section('content')
    <div class="content-wrapper py-4">
        <div class="container">
            <h2>Ajouter des questions : {{ $exam->title }}</h2>

            <form method="POST" action="{{ route('teacher.exams.questions.store', $exam->id) }}">
                @csrf
                <div class="card shadow mb-4">
                    <div class="card-body">
                        @for ($i = 1; $i <= $exam->question_count; $i++)
                            <div class="mb-5 border p-3">
                                <h5>Question #{{ $i }}</h5>

                                <div class="mb-3">
                                    <label class="form-label">Texte de la question</label>
                                    <textarea name="questions[{{ $i }}][text]" class="form-control" required></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Points</label>
                                    <input type="number" name="questions[{{ $i }}][points]" class="form-control"
                                        min="1" value="1" required>
                                </div>

                                <h6>Réponses :</h6>

                                @for ($j = 1; $j <= $exam->choices_per_question; $j++)
                                    <div class="row mb-2">
                                        <div class="col-10">
                                            <input type="text"
                                                name="questions[{{ $i }}][choices][{{ $j }}][text]"
                                                class="form-control" placeholder="Option {{ $j }}" required>
                                        </div>
                                        <div class="col-2">
                                            <div class="form-check">
                                                <input type="radio" name="questions[{{ $i }}][correct]"
                                                    value="{{ $j }}" class="form-check-input" required>
                                                <label class="form-check-label">Correcte</label>
                                            </div>
                                        </div>
                                    </div>
                                @endfor

                            </div>
                        @endfor

                        <button type="submit" class="btn btn-success">Enregistrer les questions</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection --}}


@extends('layouts.app')

@section('content')
    <div class="content-wrapper py-4">
        <div class="container">
            <h2>Ajouter ou modifier les questions : {{ $exam->title }}</h2>

            <form method="POST" action="{{ route('teacher.exams.questions.store', $exam->id) }}">
                @csrf
                <div class="card shadow mb-4">
                    <div class="card-body">
                        @php
                            $questions = $exam->questions ?? collect();
                            $totalQuestions = $exam->question_count;
                            $choicesPerQuestion = $exam->choices_per_question;
                        @endphp

                        @for ($i = 1; $i <= $totalQuestions; $i++)
                            @php
                                $question = $questions->get($i - 1); // 0-based index
                            @endphp
                            <div class="mb-5 border p-3">
                                <h5>Question #{{ $i }}</h5>

                                <div class="mb-3">
                                    <label class="form-label">Texte de la question</label>
                                    <textarea name="questions[{{ $i }}][text]" class="form-control" required>{{ old("questions.$i.text", $question?->question_text) }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Points</label>
                                    <input type="number" name="questions[{{ $i }}][points]" class="form-control"
                                        min="1" value="{{ old("questions.$i.points", $question?->points ?? 1) }}"
                                        required>
                                </div>

                                <h6>Réponses :</h6>

                                @for ($j = 1; $j <= $choicesPerQuestion; $j++)
                                    @php
                                        $choice = $question?->choices->get($j - 1); // 0-based index
                                    @endphp
                                    <div class="row mb-2">
                                        <div class="col-10">
                                            <input type="text"
                                                name="questions[{{ $i }}][choices][{{ $j }}][text]"
                                                class="form-control" placeholder="Option {{ $j }}"
                                                value="{{ old("questions.$i.choices.$j.text", $choice?->choice_text) }}"
                                                required>
                                        </div>
                                        <div class="col-2">
                                            <div class="form-check">
                                                <input type="radio" name="questions[{{ $i }}][correct]"
                                                    value="{{ $j }}" class="form-check-input" required
                                                    @if (old("questions.$i.correct", isset($choice) && $choice->is_correct ? $j : null) == $j) checked @endif>
                                                <label class="form-check-label">Correcte</label>
                                            </div>
                                        </div>
                                    </div>
                                @endfor

                            </div>
                        @endfor

                        <button type="submit" class="btn btn-success">Enregistrer les questions</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
