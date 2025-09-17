@extends('layouts.app'){{-- Utiliser la layout AdminLTE principale --}}

@section('content')
    <div class="content-header">
        <h1>{{ $header_title ?? 'Liste des départements' }}</h1>
        <a href="{{ route('admin.departementName.add') }}" class="btn btn-primary mb-3">
            <i class="fa fa-plus"></i> Ajouter un département
        </a>
    </div>

    <div class="card">
        <div class="card-body table-responsive p-0" style="max-height: 450px;">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nom du Département</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($departments as $dept)
                        <tr>
                            <td>{{ $loop->iteration + ($departments->currentPage() - 1) * $departments->perPage() }}</td>
                            <td>{{ $dept->name }}</td>
                            <td>
                                <a href="{{ route('admin.departementName.edit', $dept->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.departementName.delete', $dept->id) }}"
                                    onclick="return confirm('Confirmer la suppression ?');" class="btn btn-sm btn-danger">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">Aucun département trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer clearfix">
            {{ $departments->links() }}
        </div>
    </div>
@endsection
