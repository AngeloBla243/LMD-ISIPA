@extends('layouts.app')

@section('content')
    <div class="py-4 content-wrapper">
        <div class="container">
            <h2 class="mb-4 text-center fw-bold text-primary">Mes examens disponibles</h2>

            <div class="card shadow-sm mb-5 rounded-4 border-0">
                <div class="card-body">
                    @if ($exams->isEmpty())
                        <div
                            class="alert alert-info text-center fs-5 d-flex justify-content-center align-items-center gap-2">
                            <i class="fas fa-info-circle fs-3"></i> Aucun examen disponible actuellement
                        </div>
                    @else
                        <div class="table-responsive shadow-sm rounded-3 overflow-hidden">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-primary text-center text-uppercase small">
                                    <tr>
                                        <th>Titre</th>
                                        <th>Matière</th>
                                        <th>Début</th>
                                        <th>Fin</th>
                                        <th>Durée</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($exams as $exam)
                                        @php
                                            $status = $examStatus[$exam->id]['status'] ?? 'available';
                                            $score = $examStatus[$exam->id]['score'] ?? null;
                                        @endphp
                                        <tr>
                                            <td>{{ $exam->title }}</td>
                                            <td>{{ $exam->subject->name }}</td>
                                            <td class="text-center">{{ $exam->start_time->format('d/m/Y H:i') }}</td>
                                            <td class="text-center">{{ $exam->end_time->format('d/m/Y H:i') }}</td>
                                            <td class="text-center">{{ $exam->duration_minutes }} min</td>
                                            <td class="text-center">
                                                @switch($status)
                                                    @case('not_started')
                                                        <span
                                                            class="badge bg-secondary text-white text-uppercase px-3 py-2 shadow-sm"
                                                            title="Pas commencé">
                                                            Pas commencé
                                                        </span>
                                                    @break

                                                    @case('expired')
                                                        <span class="badge bg-dark text-white text-uppercase px-3 py-2 shadow-sm"
                                                            title="Date dépassée">
                                                            Date dépassée
                                                        </span>
                                                    @break

                                                    @case('submitted')
                                                        <span class="badge bg-success text-uppercase px-3 py-2 shadow-sm"
                                                            title="Soumis">
                                                            Soumis
                                                        </span>
                                                    @break

                                                    @case('in_progress')
                                                        <span class="badge bg-warning text-dark text-uppercase px-3 py-2 shadow-sm"
                                                            title="En cours">
                                                            En cours
                                                        </span>
                                                    @break

                                                    @default
                                                        <span class="badge bg-primary text-uppercase px-3 py-2 shadow-sm"
                                                            title="Disponible">
                                                            Disponible
                                                        </span>
                                                @endswitch
                                            </td>
                                            <td class="text-center">
                                                @if ($status == 'not_started')
                                                    <button class="btn btn-sm btn-secondary" disabled>
                                                        <i class="fas fa-lock me-1"></i> Pas commencé
                                                    </button>
                                                @elseif ($status == 'expired')
                                                    <button class="btn btn-sm btn-outline-dark" disabled>
                                                        <i class="fas fa-clock me-1"></i> Date dépassée
                                                    </button>
                                                @elseif ($status == 'submitted')
                                                    <button class="btn btn-sm btn-success mb-1" disabled>
                                                        <i class="fas fa-check-circle me-1"></i> Soumis
                                                    </button>
                                                    @if ($score !== null)
                                                        <a href="{{ route('student.exams.result', $exam->id) }}"
                                                            class="btn btn-sm btn-info">
                                                            <i class="fas fa-chart-bar me-1"></i> Résultat
                                                        </a>
                                                    @endif
                                                @elseif ($status == 'in_progress')
                                                    <a href="{{ route('student.exams.show', $exam->id) }}"
                                                        class="btn btn-sm btn-warning">
                                                        <i class="fas fa-play-circle me-1"></i> Reprendre
                                                    </a>
                                                @else
                                                    <a href="{{ route('student.exams.show', $exam->id) }}"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="fas fa-play me-1"></i> Commencer
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
