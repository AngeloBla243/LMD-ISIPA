@extends('layouts.app')

@section('style')
    <style>
        /* Table styling */
        .styled-table {
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(14, 74, 107, 0.07);
            background: #fff;
            min-width: 400px;
        }

        .styled-table thead tr {
            background-color: #2176bd;
            color: #fff;
            text-align: left;
            font-weight: 600;
        }

        .styled-table th,
        .styled-table td {
            padding: 13px 16px;
            vertical-align: middle !important;
            border: none;
        }

        .styled-table tbody tr {
            border-bottom: 1px solid #e9ecef;
            transition: background 0.13s;
        }

        .styled-table tbody tr:nth-of-type(even) {
            background-color: #f8fbff;
        }

        .styled-table tbody tr:last-of-type {
            border-bottom: 2px solid #2176bd;
        }

        .styled-table tbody tr:hover {
            background-color: #e6f1fb;
            cursor: pointer;
        }

        /* Bouton custom */
        .btn-primary {
            background-color: #2176bd;
            border-color: #2176bd;
            border-radius: 18px;
            font-weight: 600;
            letter-spacing: .5px;
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background-color: #1162a4;
            border-color: #1162a4;
        }

        .badge.bg-info {
            background: #50b5ff !important;
            color: #145884 !important;
            font-size: 0.97em;
            font-weight: 500;
            padding: 0.47em 1.1em;
            border-radius: 12px;
            margin: 0.08em 0.13em;
            vertical-align: middle;
        }

        .card {
            border-radius: 18px;
            border: none;
            box-shadow: 0 8px 24px rgba(14, 74, 107, 0.06);
        }

        /* Responsive */
        @media (max-width: 767px) {

            .styled-table th,
            .styled-table td {
                padding: .75rem;
                font-size: 0.97em;
                word-break: break-word;
            }

            .btn-primary {
                margin-top: 1em;
                width: 100%;
            }

            h1 {
                font-size: 1.36rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper py-4">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                <h1 class="fw-bold text-primary mb-3 mb-md-0"><i class="fas fa-layer-group me-2 text-primary"></i> Types de
                    frais</h1>
                <a href="{{ route('admin.fee_types.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i> Ajouter un type de frais
                </a>
            </div>
            <div class="card p-0">
                <div class="table-responsive rounded-4">
                    <table class="table styled-table mb-0">
                        <thead>
                            <tr>
                                <th>Frais</th>
                                <th>Montant</th>
                                <th>Période</th>
                                <th>Classes concernées</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($fee_types as $fee)
                                <tr>
                                    <td class="fw-semibold">{{ $fee->name }}</td>
                                    <td class="text-success fw-bold">{{ number_format($fee->amount, 2) }} F CFA</td>
                                    <td>
                                        <span class="text-muted">
                                            {{ \Carbon\Carbon::parse($fee->start_date)->format('d/m/Y') }}
                                            &nbsp;<i class="fas fa-arrow-right text-primary"></i>&nbsp;
                                            {{ \Carbon\Carbon::parse($fee->end_date)->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td>
                                        @forelse ($fee->classes as $class)
                                            <span class="badge bg-info">{{ $class->name }}</span>
                                        @empty
                                            <span class="badge bg-secondary">Aucune classe</span>
                                        @endforelse
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.fee_types.edit', $fee->id) }}"
                                            class="btn btn-warning btn-sm me-2">
                                            <i class="fas fa-edit"></i> Éditer
                                        </a>
                                        <!-- Autres actions (suppression, voir...) ici -->
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="fas fa-info-circle me-2"></i>Aucun type de frais trouvé.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-4">
                {{ $fee_types->links() }}
            </div>
        </div>
    </div>
@endsection
