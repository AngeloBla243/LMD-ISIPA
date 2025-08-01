@extends('layouts.app')

@section('style')
    <style>
        /* Conteneur principal */
        .content-wrapper {
            padding: 2.5rem 0;
            min-height: 100vh;
            background: #f8fbff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #222;
        }

        h2 {
            color: #2176bd;
            font-weight: 700;
            margin-bottom: 2rem;
            text-align: left;
            font-size: 2rem;
        }

        /* Card Styling */
        .card {
            border-radius: 18px;
            box-shadow: 0 8px 24px rgba(14, 74, 107, 0.07);
            border: none;
            background: #fff;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 1rem;
            color: #222;
        }

        thead tr {
            background-color: #2176bd;
            color: white;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.05em;
        }

        thead th,
        tbody td {
            padding: 0.75rem 1rem;
            border: 1px solid #dee2e6;
            vertical-align: middle;
            white-space: nowrap;
        }

        tbody tr:hover {
            background-color: #e6f1fb;
            cursor: pointer;
        }

        /* Buttons */
        .btn {
            border-radius: 18px;
            font-weight: 400;
            padding: 0.45em 1em;
            font-size: 0.7rem;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 0.4em;
            transition: background-color 0.25s ease;
        }

        .btn-primary {
            background-color: #2176bd;
            border-color: #2176bd;
            color: white;
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background-color: #145a8d;
            border-color: #145a8d;
            color: white;
        }

        .btn-success {
            background-color: #27c381;
            border-color: #27c381;
            color: white;
        }

        .btn-success:hover,
        .btn-success:focus {
            background-color: #1db16a;
            border-color: #1db16a;
            color: white;
        }

        .btn-info {
            background-color: #3a99d8;
            border-color: #3a99d8;
            color: white;
        }

        .btn-info:hover,
        .btn-info:focus {
            background-color: #2f7cc4;
            border-color: #2f7cc4;
            color: white;
        }

        .btn-warning {
            background-color: #f0b619;
            border-color: #f0b619;
            color: #222;
        }

        .btn-warning:hover,
        .btn-warning:focus {
            background-color: #d4a30d;
            border-color: #d4a30d;
            color: #222;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
        }

        .btn-danger:hover,
        .btn-danger:focus {
            background-color: #b72b3a;
            border-color: #b72b3a;
            color: white;
        }

        /* Responsive wrapper for small screens only */
        @media (max-width: 575.98px) {
            .card-body {
                padding: 1rem 0.5rem;
            }

            .table-responsive-sm {
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch;
                border-radius: 12px;
                box-shadow: inset 0 0 8px #c8dafb;
                padding-bottom: 0.5rem;
            }

            /* Ensure table layout for phones with scroll */
            table {
                min-width: 700px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Mes questionnaires</h2>
            </div>

            @if ($current_academic_year && !$current_academic_year->is_active)
                <div class="alert alert-warning mb-4">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Année académique non active - Les étudiants ne peuvent pas passer les examens
                </div>
            @endif

            <div class="mb-3 text-end">
                <a href="{{ route('teacher.exams.create') }}" class="btn btn-success">
                    <i class="fa fa-plus"></i> Créer un questionnaire
                </a>
            </div>

            <div class="card shadow">
                <div class="card-body p-0">
                    @if ($exams->isEmpty())
                        <div class="alert alert-info m-3">
                            Aucun questionnaire créé pour cette année académique
                        </div>
                    @else
                        {{-- Responsive only on small screens --}}
                        <div class="table-responsive-sm">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Classe</th>
                                        <th>Matière</th>
                                        <th>Date de début</th>
                                        <th>Date de fin</th>
                                        <th style="min-width: 250px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($exams as $exam)
                                        <tr>
                                            <td>{{ $exam->title }}</td>
                                            <td>{{ $exam->class->name }}</td>
                                            <td>{{ $exam->subject->name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($exam->start_time)->format('d/m/Y H:i') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($exam->end_time)->format('d/m/Y H:i') }}</td>
                                            <td style="min-width: 200px;">
                                                <div class="d-flex gap-2 flex-wrap">
                                                    <a href="{{ route('teacher.exams.questions.create', $exam->id) }}"
                                                        class="btn btn-sm btn-primary" title="Ajouter/Modifier Questions">
                                                        <i class="fa fa-plus"></i> Questions
                                                    </a>
                                                    <a href="{{ route('teacher.exams.edit', $exam->id) }}"
                                                        class="btn btn-sm btn-info" title="Modifier l'examen">
                                                        <i class="fa fa-edit"></i> Modifier
                                                    </a>
                                                    <a href="{{ route('teacher.exams.results', $exam->id) }}"
                                                        class="btn btn-sm btn-warning" title="Voir les résultats">
                                                        <i class="fa fa-chart-bar"></i> Résultats
                                                    </a>
                                                    <form method="POST"
                                                        action="{{ route('teacher.exams.destroy', $exam->id) }}"
                                                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet examen ?');"
                                                        class="m-0 p-0">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            title="Supprimer l'examen">
                                                            <i class="fa fa-trash"></i> Supprimer
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $exams->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
