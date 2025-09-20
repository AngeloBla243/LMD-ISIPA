@extends('layouts.app')

@section('style')
    <style>
        .card {
            border-radius: 1.25rem;
        }

        .card-header {
            border-radius: 1.25rem 1.25rem 0 0;
        }

        .table-custom th {
            background-color: #cfe2ff !important;
            color: #084298 !important;
            font-weight: 600;
            text-transform: uppercase;
        }

        .table-custom {
            border-collapse: collapse;
            margin-bottom: 0;
            width: 100%;
        }

        .table-custom td,
        .table-custom th {
            padding: 12px 15px;
            vertical-align: middle;
        }

        .table-custom tbody tr:nth-of-type(even) {
            background-color: #f9f9f9;
        }

        .table-custom tbody tr:hover {
            background-color: #f1f7ff;
        }

        .btn-sm {
            padding: 0.25rem 0.6rem;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .badge {
            font-size: 0.85rem;
            padding: 0.4em 0.75em;
            border-radius: 0.375rem;
        }

        .badge.bg-success {
            background-color: #198754 !important;
        }

        .badge.bg-secondary {
            background-color: #6c757d !important;
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <!-- Header -->
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid d-flex justify-content-between align-items-center">
                <h1 class="h3 fw-bold text-primary mb-0">
                    <i class="fa-solid fa-list-check me-2"></i>
                    Assignations professeurs ({{ $getRecord->total() }})
                </h1>
                <a href="{{ route('departement.assign_teacher.add') }}" class="btn btn-success shadow-sm rounded-3">
                    <i class="fas fa-plus-circle me-2"></i> Nouvelle assignation
                </a>
            </div>
        </section>

        <!-- Content -->
        <section class="content pb-5">
            <div class="container-fluid">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title mb-0">
                            <i class="fa-solid fa-table me-2"></i> Liste des assignations
                        </h3>
                    </div>
                    <div class="card-body p-0 table-responsive">
                        @include('_message')
                        <table class="table table-hover table-bordered table-custom align-middle mb-0">
                            <thead class="text-center small">
                                <tr>
                                    <th>#</th>
                                    <th>Année Académique</th>
                                    <th>Classe</th>
                                    <th>Professeur</th>
                                    <th>Statut</th>
                                    <th>Matière</th>
                                    {{-- <th>Créé par</th> --}}
                                    <th>Date de création</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($getRecord as $value)
                                    <tr>
                                        <td class="text-center">{{ $value->id }}</td>
                                        <td style="min-width: 200px;">{{ $value->academicYear->name ?? 'N/A' }}</td>
                                        <td style="min-width: 250px;">{{ $value->class->name ?? 'N/A' }}
                                            {{ $value->class->opt ?? '' }}</td>
                                        <td style="min-width: 200px;"> {{ $value->teacher->name ?? 'N/A' }}
                                            {{ $value->teacher->last_name ?? '' }}</td>
                                        <td class="text-center">
                                            <span class="badge {{ $value->status == 0 ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $value->status == 0 ? 'Actif' : 'Inactif' }}
                                            </span>
                                        </td>
                                        <td style="min-width: 300px;">{{ $value->subject->name ?? 'N/A' }}</td>
                                        {{-- <td>{{ $value->creator->name ?? 'N/A' }}</td> --}}
                                        <td style="min-width: 150px;">{{ $value->created_at->format('d-m-Y H:i') }}</td>
                                        <td style="min-width: 200px;" class="text-center" style="min-width: 280px;">
                                            <a href="{{ route('departement.assign_teacher.edit', $value->id) }}"
                                                class="btn btn-primary btn-sm me-1 mb-1" title="Modifier">
                                                <i class="fas fa-pencil-alt"></i> Modifier
                                            </a>
                                            <a href="{{ route('departement.assign_teacher.assign_subject', $value->teacher_id) }}"
                                                class="btn btn-warning btn-sm me-1 mb-1" title="Assigner matières">
                                                <i class="fas fa-book"></i> Matières
                                            </a>
                                            <a href="{{ url('departement/assign_teacher/delete/' . $value->id) }}"
                                                class="btn btn-danger btn-sm mb-1" title="Supprimer"
                                                onclick="return confirm('Voulez-vous vraiment supprimer cette assignation ?');">
                                                <i class="fas fa-trash"></i> Supprimer
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">
                                            <i class="fa-regular fa-folder-open me-2"></i> Aucune assignation trouvée
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="mt-3 d-flex justify-content-end px-3">
                            {!! $getRecord->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
