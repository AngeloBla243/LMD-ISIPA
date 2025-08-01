@extends('layouts.app')

@section('style')
    <style>
        /* Table styles inspired by previous cards */
        .styled-table {
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(14, 74, 107, 0.07);
            background-color: #fff;
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            min-width: 600px;
            /* impose un min-width pour faciliter scroll sur mobiles */
        }

        .styled-table thead tr {
            background-color: #2176bd;
            color: #fff;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.05em;
        }

        .styled-table th,
        .styled-table td {
            padding: 14px 18px;
            vertical-align: middle;
            border-bottom: 1px solid #e9ecef;
            border-right: 1px solid #e9ecef;
            font-size: 0.95rem;
            white-space: nowrap;
            /* évite le wrapping, facilite le scroll */
        }

        /* Pas de bordure droite pour dernière colonne */
        .styled-table th:last-child,
        .styled-table td:last-child {
            border-right: none;
        }

        .styled-table tbody tr:nth-child(even) {
            background-color: #f8fbff;
        }

        .styled-table tbody tr:hover {
            background-color: #e6f1fb;
            cursor: pointer;
        }

        /* Badge styles (bootstrap utils + personnalisation) */
        .badge {
            font-weight: 600;
            font-size: 0.85rem;
            padding: 0.4em 0.75em;
            border-radius: 1rem;
            min-width: 80px;
            text-align: center;
            display: inline-block;
        }

        /* Buttons dans table */
        .styled-table .btn {
            border-radius: 1.25rem;
            font-size: 0.85rem;
            padding: 0.38em 0.8em;
            text-transform: none;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 0.3em;
        }

        /* Card and container modifications */
        .card {
            border-radius: 18px;
            border: none;
            box-shadow: 0 8px 24px rgba(14, 74, 107, 0.06);
        }

        /* Responsive behaviour */
        .table-responsive {
            border-radius: 14px;
            overflow-x: auto !important;
            box-shadow: inset 0 0 10px #d6e3f0;
            padding-bottom: 0.5rem;
        }

        /* Header titles */
        h2 {
            font-weight: 800;
            color: #2176bd;
            margin-bottom: 1.8rem;
            text-align: center;
            font-size: 2rem;
        }

        /* Utilitaires */
        .text-center {
            text-align: center !important;
        }

        /* Small screens */
        @media (max-width: 768px) {
            .styled-table {
                min-width: 500px;
            }

            .styled-table th,
            .styled-table td {
                padding: 10px 12px;
                font-size: 0.85rem;
            }

            h2 {
                font-size: 1.5rem;
            }

            .btn {
                font-size: 0.8rem;
                padding: 0.33em 0.7em;
            }

            .badge {
                font-size: 0.75rem;
                min-width: 65px;
                padding: 0.3em 0.6em;
            }
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="container py-4">
            <h2>Mes examens disponibles</h2>

            <div class="card mb-5">
                <div class="card-body">
                    @if ($exams->isEmpty())
                        <div class="alert alert-info d-flex justify-content-center align-items-center gap-2 py-3 fs-5">
                            <i class="fas fa-info-circle fa-2x"></i>
                            Aucun examen disponible actuellement
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="styled-table">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Matière</th>
                                        <th class="text-center">Début</th>
                                        <th class="text-center">Fin</th>
                                        <th class="text-center">Durée</th>
                                        <th class="text-center">Statut</th>
                                        <th class="text-center">Actions</th>
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
                                                        <span class="badge bg-secondary" title="Pas commencé">Pas commencé</span>
                                                    @break

                                                    @case('expired')
                                                        <span class="badge bg-dark" title="Date dépassée">Date dépassée</span>
                                                    @break

                                                    @case('submitted')
                                                        <span class="badge bg-success" title="Soumis">Soumis</span>
                                                    @break

                                                    @case('in_progress')
                                                        <span class="badge bg-warning text-dark" title="En cours">En cours</span>
                                                    @break

                                                    @default
                                                        <span class="badge bg-primary" title="Disponible">Disponible</span>
                                                @endswitch
                                            </td>
                                            <td class="text-center">
                                                @if ($status == 'not_started')
                                                    <button class="btn btn-secondary" disabled><i class="fas fa-lock"></i>
                                                        Pas commencé</button>
                                                @elseif ($status == 'expired')
                                                    <button class="btn btn-outline-dark" disabled><i
                                                            class="fas fa-clock"></i> Date dépassée</button>
                                                @elseif ($status == 'submitted')
                                                    <button class="btn btn-success mb-1" disabled><i
                                                            class="fas fa-check-circle"></i> Soumis</button>
                                                    @if ($score !== null)
                                                        <a href="{{ route('student.exams.result', $exam->id) }}"
                                                            class="btn btn-info">
                                                            <i class="fas fa-chart-bar"></i> Résultat
                                                        </a>
                                                    @endif
                                                @elseif ($status == 'in_progress')
                                                    <a href="{{ route('student.exams.show', $exam->id) }}"
                                                        class="btn btn-warning">
                                                        <i class="fas fa-play-circle"></i> Reprendre
                                                    </a>
                                                @else
                                                    <a href="{{ route('student.exams.show', $exam->id) }}"
                                                        class="btn btn-primary">
                                                        <i class="fas fa-play"></i> Commencer
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> <!-- /.table-responsive -->
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
