@extends('layouts.app')

@section('style')
    <style>
        /* Conteneur principal */
        .content-wrapper {
            padding: 2.5rem 0;
            background: #f8fbff;
            min-height: 100vh;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            color: #222;
        }

        h2 {
            color: #2176bd;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-align: left;
            font-size: 2rem;
        }

        /* Card styling */
        .card {
            border-radius: 18px;
            box-shadow: 0 8px 24px rgba(33, 118, 189, 0.1);
            border: none;
            background: #fff;
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
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.05em;
            font-size: 0.85rem;
        }

        thead th,
        tbody td {
            padding: 0.75rem 1rem;
            border: 1px solid #e1e9f8;
            vertical-align: middle;
            white-space: nowrap;
        }

        tbody tr:nth-child(even) {
            background-color: #f7fbff;
        }

        tbody tr:hover {
            background-color: #dbe9ff;
            cursor: pointer;
        }

        /* Badge styling */
        .badge {
            font-weight: 600;
            font-size: 0.85rem;
            padding: 0.4em 0.75em;
            border-radius: 12px;
            display: inline-block;
            min-width: 60px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(33, 118, 189, 0.2);
            color: white;
        }

        .bg-primary {
            background-color: #2176bd !important;
        }

        /* Responsive wrapper — only on mobile */
        @media (max-width: 575.98px) {
            .table-responsive-mobile {
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch;
                border-radius: 12px;
                box-shadow: inset 0 0 12px rgba(33, 118, 189, 0.15);
                padding-bottom: 0.5rem;
            }

            table {
                min-width: 600px;
                /* force horizontal scroll */
            }
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="container py-4" style="max-width: 960px;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Mes étudiants encadrés</h2>
                <a href="{{ route('teacher.encadres.export') }}" class="btn btn-primary">
                    <i class="fas fa-download me-2"></i> Télécharger la liste
                </a>
            </div>

            <div class="card shadow-sm">
                <div class="table-responsive-mobile">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Étudiant</th>
                                <th>Classe</th>
                                <th>Type</th>
                                <th>Titre</th>
                                <th>Année académique</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($submissions as $sub)
                                <tr>
                                    <td>{{ $sub->student->name }} {{ $sub->student->last_name }}</td>
                                    <td>{{ $sub->student->classes->first()->name ?? 'N/A' }}
                                        {{ $sub->student->classes->first()->opt ?? '' }}</td>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ $sub->type == 1 ? 'Mémoire' : 'Projet' }}
                                        </span>
                                    </td>
                                    <td>{{ $sub->type == 1 ? $sub->subject : $sub->project_name }}</td>
                                    <td>{{ $sub->academicYear->name ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
