@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h1 class="h3 fw-bold text-primary">Années Académiques</h1>
                    </div>
                    <div class="col-sm-6 text-end">
                        <a href="{{ route('admin.academic-years.create') }}" class="btn btn-primary shadow-sm rounded-3">
                            <i class="fa-solid fa-plus me-2"></i> Ajouter une année
                        </a>
                    </div>
                </div>
            </div>
        </section>

        @if (session('success'))
            <div class="container-fluid">
                <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            </div>
        @endif

        <section class="content">
            <div class="container-fluid">
                <div class="card shadow-sm rounded-4 border-0">
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-striped table-bordered align-middle mb-0">
                            <thead class="table-primary text-center text-uppercase small">
                                <tr>
                                    <th>Nom</th>
                                    <th>Début</th>
                                    <th>Fin</th>
                                    <th>Active</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($years as $year)
                                    <tr>
                                        <td>{{ $year->name }}</td>
                                        <td class="text-center">{{ $year->start_date->format('d/m/Y') }}</td>
                                        <td class="text-center">{{ $year->end_date->format('d/m/Y') }}</td>
                                        <td class="text-center">
                                            @if ($year->is_active)
                                                <span class="badge bg-success">Oui</span>
                                            @else
                                                <a href="{{ route('admin.academic-years.set-active', $year) }}"
                                                    class="btn btn-sm btn-outline-secondary">
                                                    Définir active
                                                </a>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.academic-years.edit', $year) }}"
                                                class="btn btn-sm btn-warning me-1" title="Modifier">
                                                <i class="fas fa-edit"></i> Modifier
                                            </a>
                                            <form action="{{ route('admin.academic-years.destroy', $year) }}" method="POST"
                                                style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Confirmer la suppression ?')"
                                                    title="Supprimer">
                                                    <i class="fas fa-trash-alt"></i> Supprimer
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($years->isEmpty())
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="fa-solid fa-folder-open fa-2x mb-2"></i><br>
                                            Aucune année académique trouvée.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    @if ($years instanceof \Illuminate\Pagination\AbstractPaginator && $years->hasPages())
                        <div class="card-footer d-flex justify-content-end bg-white border-0">
                            {!! $years->links() !!}
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>

    <style>
        .table-primary th {
            background-color: #cfe2ff !important;
            color: #084298 !important;
            font-weight: 600;
        }

        .btn-sm {
            font-weight: 500;
        }

        .alert {
            font-size: 1rem;
        }
    </style>
@endsection
