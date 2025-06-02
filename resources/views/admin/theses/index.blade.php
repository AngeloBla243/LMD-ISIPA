@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h1 class="h3 fw-bold text-primary">Soumissions de mémoires</h1>
                    </div>
                </div>

                <!-- Recherche -->
                <form method="GET" action="" class="mb-4">
                    <div class="row g-2">
                        <div class="col-md-6 col-lg-4">
                            <input type="text" name="search" class="form-control"
                                placeholder="Recherche étudiant, classe ou mémoire" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2 col-lg-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa-solid fa-magnifying-glass me-1"></i> Rechercher
                            </button>
                        </div>
                        <div class="col-md-2 col-lg-2">

                            <a href="{{ route('admin.theses.export') }}" class="btn btn-success">
                                <i class="fas fa-file-pdf"></i> Exporter la liste
                            </a>
                        </div>


                    </div>
                </form>
            </div>
        </section>

        <!-- Main content -->
        <section class="content pb-5">
            <div class="container-fluid">
                <div class="card shadow-sm rounded-4 border-0">
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-striped table-bordered align-middle mb-0">
                            <thead class="table-primary text-center text-uppercase small">
                                <tr>
                                    <th>#</th>
                                    <th>Étudiant</th>
                                    <th>Classe</th>
                                    <th>Sujet / Projet</th>
                                    <th>Type</th>
                                    <th>Encadrant</th>
                                    <th>Taux de plagiat</th>
                                    <th>Statut</th>
                                    <th>Date de soumission</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $start = ($submissions->currentPage() - 1) * $submissions->perPage() + 1;
                                @endphp
                                @forelse($submissions as $i => $submission)
                                    <tr>
                                        <td class="text-center">{{ $start + $i }}</td>
                                        <td style="min-width: 200px;">
                                            {{ $submission->student->name ?? 'N/A' }}
                                            {{ $submission->student->last_name ?? '' }}
                                        </td>
                                        <td style="min-width: 200px;">
                                            {{ $submission->student->classes->first()->name ?? 'N/A' }}
                                            {{ $submission->student->classes->first()->opt ?? 'N/A' }}
                                        </td>

                                        <td style="min-width: 200px;">
                                            {{ $submission->type == 1 ? $submission->subject : $submission->project_name }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary">
                                                {{ $submission->type == 1 ? 'Mémoire' : 'Projet' }}
                                            </span>
                                        </td>
                                        <td style="min-width: 200px;">
                                            @if ($submission->type == 1)
                                                {{ $submission->directeur->name ?? 'N/A' }}
                                                {{ $submission->directeur->last_name ?? '' }}
                                            @else
                                                {{ $submission->encadreur->name ?? 'N/A' }}
                                                {{ $submission->encadreur->last_name ?? '' }}
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $submission->plagiarism_rate }}%</td>
                                        <td class="text-center">
                                            @php
                                                $statusClasses = [
                                                    'accepted' => 'badge bg-success',
                                                    'rejected' => 'badge bg-danger',
                                                    'pending' => 'badge bg-warning text-dark',
                                                ];
                                                $status = $submission->status ?? 'pending';
                                            @endphp
                                            <span class="{{ $statusClasses[$status] ?? 'badge bg-secondary' }}">
                                                {{ ucfirst($status) }}
                                            </span>
                                        </td>
                                        <td class="text-center" style="min-width: 200px;">
                                            {{ $submission->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="text-center" style="min-width: 200px;">
                                            <a href="{{ route('admin.theses.show', $submission->id) }}"
                                                class="btn btn-sm btn-info me-1" title="Voir">
                                                <i class="fas fa-eye"></i> Voir
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger delete-btn"
                                                data-id="{{ $submission->id }}"
                                                data-nom="{{ $submission->student->name ?? '' }} {{ $submission->student->last_name ?? '' }}"
                                                title="Supprimer">
                                                <i class="fas fa-trash-alt"></i> Supprimer
                                            </button>
                                            <form id="delete-form-{{ $submission->id }}"
                                                action="{{ route('admin.theses.destroy', $submission->id) }}"
                                                method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted py-4">
                                            <i class="fa-solid fa-folder-open fa-2x mb-2"></i><br>
                                            Aucune soumission trouvée.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>

                    <div class="card-footer d-flex justify-content-end bg-white border-0">
                        {{ $submissions->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>

    <style>
        .card {
            border-radius: 1.25rem;
        }

        .table-primary th {
            background-color: #cfe2ff !important;
            color: #084298 !important;
            font-weight: 600;
        }

        .btn-sm {
            font-weight: 500;
        }

        .badge {
            font-size: 0.9rem;
            padding: 0.4em 0.75em;
        }
    </style>

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-btn');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const nom = this.dataset.nom;
                    if (confirm(`Confirmer la suppression de la soumission de ${nom} ?`)) {
                        document.getElementById(`delete-form-${id}`).submit();
                    }
                });
            });
        });
    </script> --}}
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-btn').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const memoId = btn.getAttribute('data-id');
                    const nom = btn.getAttribute('data-nom');
                    Swal.fire({
                        title: 'Supprimer ?',
                        text: 'Voulez-vous vraiment supprimer ce mémoire de ' + nom + ' ?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Oui, supprimer',
                        cancelButtonText: 'Annuler'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('delete-form-' + memoId).submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
