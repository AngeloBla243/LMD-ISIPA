@extends('layouts.app')

@section('style')
    <style>
        /* Conteneur principal */
        .content-wrapper {
            padding-top: 2rem;
            padding-bottom: 2rem;
        }

        h2 {
            color: #2176bd;
            font-weight: 700;
            margin-bottom: 2rem;
            text-align: center;
        }

        /* Card */
        .card {
            border-radius: 18px;
            box-shadow: 0 8px 24px rgba(14, 74, 107, 0.07);
            border: none;
        }

        /* Table styling */
        .styled-table {
            width: 100%;
            min-width: 700px;
            /* pour faciliter scroll en responsive */
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(14, 74, 107, 0.07);
            background-color: #fff;
        }

        .styled-table thead tr {
            background-color: #2176bd;
            color: #fff;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.06em;
        }

        .styled-table th,
        .styled-table td {
            padding: 14px 18px;
            vertical-align: middle;
            border-bottom: 1px solid #e9ecef;
            border-right: 1px solid #e9ecef;
            font-size: 0.95rem;
            white-space: nowrap;
        }

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

        /* Badge styling */
        .badge {
            font-weight: 600;
            font-size: 0.85rem;
            padding: 0.4em 0.8em;
            border-radius: 1rem;
            min-width: 80px;
            text-align: center;
            display: inline-block;
            box-shadow: 0 1px 6px rgba(0, 0, 0, 0.1);
        }

        /* Boutons d'action */
        .btn {
            border-radius: 1.25rem;
            font-size: 0.85rem;
            padding: 0.35em 0.75em;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 0.3em;
        }

        /* Responsive table container */
        .table-responsive {
            border-radius: 14px;
            overflow-x: auto !important;
            box-shadow: inset 0 0 10px #d6e3f0;
            padding-bottom: 0.7rem;
        }

        /* Text center utility */
        .text-center {
            text-align: center !important;
        }

        /* Adaptation mobile */
        @media (max-width: 768px) {
            .styled-table {
                min-width: 520px;
            }

            .styled-table th,
            .styled-table td {
                padding: 10px 12px;
                font-size: 0.82rem;
            }

            h2 {
                font-size: 1.6rem;
                margin-bottom: 1.2rem;
                padding: 0 1rem;
            }

            .btn {
                font-size: 0.78rem;
                padding: 0.3em 0.6em;
            }

            .badge {
                font-size: 0.7rem;
                min-width: 65px;
                padding: 0.3em 0.6em;
            }
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="container py-4" style="max-width: 900px;">
            <h2>Mes soumissions - <span class="text-primary">{{ $academicYear->name }}</span></h2>

            @if ($submissions->isEmpty())
                <div class="alert alert-info d-flex justify-content-center align-items-center gap-3 fs-5 py-3">
                    <i class="fas fa-info-circle fa-2x"></i> Aucun soumission trouvée pour cette année académique.
                </div>
            @else
                <div class="card shadow-sm rounded-4 border-0">
                    <div class="card-body p-0 table-responsive">
                        <table class="styled-table table-hover align-middle mb-0">
                            <thead>
                                <tr class="text-center">
                                    <th>Type</th>
                                    <th>Titre</th>
                                    <th>Encadrant</th>
                                    <th style="min-width: 200px;">Taux de plagiat</th>
                                    <th style="min-width: 180px;">Statut plagiat</th>
                                    <th style="min-width: 180px;">Statut de validation</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($submissions as $sub)
                                    <tr>
                                        <td class="text-center">
                                            <span class="badge bg-info shadow-sm" title="Type de soumission">
                                                {{ $sub->type == 1 ? 'Mémoire' : 'Projet' }}
                                            </span>
                                        </td>
                                        <td>{{ $sub->type == 1 ? $sub->subject : $sub->project_name }}</td>
                                        <td>{{ $sub->type == 1 ? $sub->directeur->full_name : $sub->encadreur->full_name }}
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center" style="gap:10px; min-width: 100px;">
                                                <div class="progress flex-grow-1"
                                                    style="height: 22px; border-radius:15px; overflow:hidden; box-shadow: inset 0 1px 3px rgba(0,0,0,0.15);">
                                                    <div class="progress-bar
                                                    @if ($sub->plagiarism_rate > 20) bg-danger
                                                    @elseif($sub->plagiarism_rate > 10) bg-warning
                                                    @else bg-success @endif"
                                                        role="progressbar" style="width: {{ $sub->plagiarism_rate }}%;">
                                                    </div>
                                                </div>
                                                <span class="fw-semibold fs-6" style="min-width:40px; text-align:right;">
                                                    {{ $sub->plagiarism_rate }}%
                                                </span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge
                                            @if ($sub->plagiarism_rate <= 10) bg-success
                                            @else bg-warning text-dark @endif px-3 py-2 shadow-sm"
                                                title="Statut plagiat">
                                                {{ $sub->plagiarism_rate <= 10 ? 'Accepté' : 'En revue' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $statusClass =
                                                    $sub->status === 'accepted'
                                                        ? 'bg-success'
                                                        : ($sub->status === 'rejected'
                                                            ? 'bg-danger'
                                                            : 'bg-secondary');
                                                $statusLabel = ucfirst($sub->status);
                                            @endphp
                                            <span class="badge {{ $statusClass }} px-3 py-2 shadow-sm"
                                                title="Statut de validation">
                                                {{ $statusLabel }}
                                            </span>
                                        </td>
                                        <td class="text-center">{{ $sub->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
