@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="container py-4">
            <h2 class="mb-4 text-primary">Mes soumissions - {{ $academicYear->name }}</h2>

            @if ($submissions->isEmpty())
                <div class="alert alert-info">
                    Aucune soumission trouvée pour cette année académique.
                </div>
            @else
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Type</th>
                                    <th>Titre</th>
                                    <th>Encadrant</th>
                                    <th>Taux de plagiat</th>
                                    <th>Statut plagiat</th>
                                    <th>Statut de validation</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($submissions as $sub)
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary">
                                                {{ $sub->type == 1 ? 'Mémoire' : 'Projet' }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $sub->type == 1 ? $sub->subject : $sub->project_name }}
                                        </td>
                                        <td>
                                            {{ $sub->type == 1 ? $sub->directeur->full_name : $sub->encadreur->full_name }}
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress flex-grow-1" style="height: 20px;">
                                                    <div class="progress-bar
                                            {{ $sub->plagiarism_rate > 20 ? 'bg-danger' : ($sub->plagiarism_rate > 10 ? 'bg-warning' : 'bg-success') }}"
                                                        role="progressbar" style="width: {{ $sub->plagiarism_rate }}%">
                                                    </div>
                                                </div>
                                                <span class="ms-2">{{ $sub->plagiarism_rate }}%</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span
                                                class="badge
                                    {{ $sub->plagiarism_rate > 20 ? 'bg-danger' : ($sub->plagiarism_rate > 10 ? 'bg-warning' : 'bg-success') }}">
                                                {{ $sub->plagiarism_rate <= 10 ? 'Accepté' : 'En revue' }}
                                            </span>
                                        </td>

                                        <td>
                                            <span
                                                class="badge
                    @if ($sub->status === 'accepted') bg-success
                    @elseif($sub->status === 'rejected') bg-danger
                    @else bg-secondary @endif">
                                                {{ ucfirst($sub->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $sub->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
