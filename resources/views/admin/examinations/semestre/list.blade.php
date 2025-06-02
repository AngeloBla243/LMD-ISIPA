@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <h1 class="h3 fw-bold text-primary">{{ $header_title }}</h1>
                <div class="col-sm-6" style="text-align: right;">
                    <a href="{{ route('admin.semester.create.form') }}" class="btn btn-info">
                        <i class="fa-solid fa-file-circle-plus"></i> Créer un Semestre
                    </a>
                </div>
            </div>
        </section>
        <section class="content pb-5">
            <div class="container-fluid">
                <div class="card shadow-sm rounded-4 border-0">
                    <div class="card-header bg-primary text-white rounded-top-4">
                        <h3 class="card-title mb-0">
                            <i class="fa-solid fa-list-ul me-2"></i>Liste des Semestres
                        </h3>
                    </div>
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-hover table-bordered align-middle mb-0">
                            <thead class="table-primary text-center text-uppercase small">
                                <tr>
                                    <th>#</th>
                                    <th>Nom du semestre</th>
                                    <th>Année académique</th>
                                    <th>Date de création</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($getRecord as $semestre)
                                    <tr>
                                        <td>{{ $semestre->id }}</td>
                                        <td>{{ $semestre->name }}</td>
                                        <td>{{ $semestre->academicYear->name ?? 'N/A' }}</td>
                                        <td>{{ $semestre->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            {{-- Ajoute ici les boutons d’édition/suppression si besoin --}}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Aucun semestre trouvé</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
