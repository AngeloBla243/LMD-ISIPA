@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>{{ $header_title }}</h1>
        </section>
        <section class="content">
            <div class="card">
                <div class="card-header d-flex justify-content-end">
                    <a href="{{ route('admin.ue.add') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Ajouter une UE
                    </a>
                </div>
                <div class="card-body">
                    @include('_message')
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Nom</th>
                                <th>Crédits</th>
                                <th>Année académique</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($getRecord as $ue)
                                <tr>
                                    <td>{{ $ue->code }}</td>
                                    <td>{{ $ue->name }}</td>
                                    <td>{{ $ue->credits }}</td>
                                    <td>{{ $ue->academicYear->name ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('admin.ue.edit', $ue->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('admin.ue.delete', $ue->id) }}" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Supprimer cette UE ?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            @if ($getRecord->isEmpty())
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Aucune UE trouvée</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
@endsection
