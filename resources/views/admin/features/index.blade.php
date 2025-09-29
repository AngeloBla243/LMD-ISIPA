@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="container py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 fw-bold text-primary">Gestion des fonctionnalités</h1>
                <a href="{{ route('admin.features.create') }}" class="btn btn-success">
                    <i class="fa fa-plus"></i> Nouvelle fonctionnalité
                </a>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Nom de la fonction</th>
                                <th>Activée</th>
                                <th>Date début</th>
                                <th>Date fin</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($features as $feature)
                                <tr>
                                    <td>{{ $feature->feature_name }}</td>
                                    <td>
                                        @if ($feature->enabled)
                                            <span class="badge bg-success">Oui</span>
                                        @else
                                            <span class="badge bg-danger">Non</span>
                                        @endif
                                    </td>
                                    <td>{{ $feature->start_date ?? '-' }}</td>
                                    <td>{{ $feature->end_date ?? '-' }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.features.edit', $feature->id) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="fa fa-edit"></i> Modifier
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        Aucune fonctionnalité disponible.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
