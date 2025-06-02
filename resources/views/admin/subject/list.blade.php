@extends('layouts.app')
@section('style')
    <style type="text/css">
        .styled-table {
            border-collapse: collapse;
            margin: 25px 0;
            min-width: 400px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
            border-radius: 8px;
            overflow: hidden;
        }

        .styled-table thead tr {
            background-color: #009879;
            color: #ffffff;
            text-align: left;
        }

        .styled-table th,
        .styled-table td {
            padding: 12px 15px;
        }

        .styled-table tbody tr {
            border-bottom: 1px solid #dddddd;
        }

        .styled-table tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }

        .styled-table tbody tr:last-of-type {
            border-bottom: 2px solid #009879;
        }

        /* Effet survol (hover) */
        .styled-table tbody tr:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }
    </style>
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h1 class="h3 fw-bold text-primary">
                            Liste des matières
                        </h1>
                    </div>
                    <div class="col-sm-6 text-end">
                        <a href="{{ url('admin/subject/add') }}" class="btn btn-info shadow-sm rounded-3">
                            <i class="fa-solid fa-file-circle-plus me-2"></i> Ajouter une matière
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content pb-5">
            <div class="container-fluid">
                <!-- Recherche -->
                <div class="card shadow-sm rounded-4 border-0 mb-4">
                    <div class="card-header bg-primary text-white rounded-top-4">
                        <h3 class="card-title mb-0">
                            <i class="fa-solid fa-magnifying-glass me-2"></i>Rechercher une matière
                        </h3>
                    </div>
                    <form method="get" action="">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Nom</label>
                                    <input type="text" class="form-control" value="{{ Request::get('name') }}"
                                        name="name" placeholder="Nom">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Code</label>
                                    <input type="text" class="form-control" value="{{ Request::get('code') }}"
                                        name="code" placeholder="Code">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Type de matière</label>
                                    <select class="form-select form-control" name="type">
                                        <option value="">Sélectionner le type</option>
                                        <option {{ Request::get('type') == 'Theory' ? 'selected' : '' }} value="Theory">
                                            Théorique</option>
                                        <option {{ Request::get('type') == 'Practical' ? 'selected' : '' }}
                                            value="Practical">Pratique</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Date</label>
                                    <input type="date" class="form-control" name="date"
                                        value="{{ Request::get('date') }}">
                                </div>
                                <div class="col-md-3 d-flex align-items-end gap-2">
                                    <button class="btn btn-primary w-100" type="submit">
                                        <i class="fa-solid fa-magnifying-glass me-1"></i> Rechercher
                                    </button>
                                    <a href="{{ url('admin/subject/list') }}"
                                        class="btn btn-success w-100">Réinitialiser</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                @include('_message')

                <!-- Liste des matières -->
                <div class="card shadow-sm rounded-4 border-0">
                    <div class="card-header bg-primary text-white rounded-top-4">
                        <h3 class="card-title mb-0">
                            <i class="fa-solid fa-list-ul me-2"></i>Liste des matières
                        </h3>
                    </div>
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-hover table-bordered align-middle mb-0">
                            <thead class="table-primary text-center text-uppercase small">
                                <tr>
                                    <th>#</th>
                                    <th>Année Académique</th>
                                    <th>Code Ue</th>
                                    <th>Nom</th>
                                    <th>Code Ec</th>
                                    <th>Type</th>
                                    <th>Statut</th>
                                    <th>Créé par</th>
                                    <th>Date création</th>
                                    <th style="width: 180px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($getRecord as $value)
                                    <tr>
                                        <td class="text-center" style="min-width: 200px;">
                                            {{ ($getRecord->currentPage() - 1) * $getRecord->perPage() + $loop->iteration }}
                                        </td>
                                        <td style="min-width: 200px;">{{ $value->academic_year_name ?? 'N/A' }}</td>
                                        <td style="min-width: 200px;">{{ $value->ue_code ?? 'N/A' }}</td>
                                        <td style="min-width: 200px;">{{ $value->name }}</td>
                                        <td style="min-width: 200px;">{{ $value->code ?? 'N/A' }}</td>
                                        <td style="min-width: 200px;">
                                            @if ($value->type == 'Theory')
                                                <span class="badge bg-info text-dark">Théorique</span>
                                            @elseif($value->type == 'Practical')
                                                <span class="badge bg-warning text-dark">Pratique</span>
                                            @else
                                                <span class="badge bg-secondary">N/A</span>
                                            @endif
                                        </td>
                                        <td class="text-center" style="min-width: 200px;">
                                            <span class="badge {{ $value->status == 0 ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $value->status == 0 ? 'Actif' : 'Inactif' }}
                                            </span>
                                        </td>
                                        <td style="min-width: 200px;">{{ $value->created_by_name }}</td>
                                        <td style="min-width: 200px;">
                                            {{ date('d-m-Y H:i A', strtotime($value->created_at)) }}</td>
                                        <td class="text-center" style="min-width: 200px;">
                                            <a href="{{ url('admin/subject/edit/' . $value->id) }}"
                                                class="btn btn-info btn-sm me-1" title="Modifier">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            <a href="{{ url('admin/subject/delete/' . $value->id) }}"
                                                class="btn btn-danger btn-sm" title="Supprimer"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette matière ?');">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">
                                            <i class="fa-solid fa-folder-open fa-2x mb-2"></i><br>
                                            Aucune matière trouvée.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @if ($getRecord instanceof \Illuminate\Pagination\AbstractPaginator && $getRecord->hasPages())
                            <div class="mt-3 d-flex justify-content-end px-3">
                                {!! $getRecord->appends(Illuminate\Support\Facades\Request::except('page'))->links() !!}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>

    <style>
        .card {
            border-radius: 1.25rem;
        }

        .card-header {
            border-radius: 1.25rem 1.25rem 0 0;
        }

        .table-primary th {
            background-color: #cfe2ff !important;
            color: #084298 !important;
            font-weight: 600;
        }

        .btn-info,
        .btn-danger,
        .btn-success,
        .btn-primary {
            font-weight: 500;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .badge {
            font-size: 0.9rem;
            padding: 0.4em 0.75em;
        }
    </style>
@endsection
