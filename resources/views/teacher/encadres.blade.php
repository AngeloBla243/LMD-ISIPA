@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="container py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-primary">Mes étudiants encadrés</h2>
                <a href="{{ route('teacher.encadres.export') }}" class="btn btn-primary">
                    <i class="fas fa-download me-2"></i> Télécharger la liste
                </a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Étudiant</th>
                                <th>Classe</th>
                                <th>Type</th>
                                <th>Titre</th>
                                <th>Année académique</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($submissions as $sub)
                                <tr>
                                    <td>{{ $sub->student->name }} {{ $sub->student->last_name }}</td>
                                    <td>
                                        {{ $sub->student->classes->first()->name ?? 'N/A' }}
                                        {{ $sub->student->classes->first()->opt ?? '' }}
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ $sub->type == 1 ? 'Mémoire' : 'Projet' }}
                                        </span>
                                    </td>
                                    <td>{{ $sub->type == 1 ? $sub->subject : $sub->project_name }}</td>
                                    <td>{{ $sub->academicYear->name ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
