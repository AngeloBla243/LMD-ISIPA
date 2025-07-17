@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="container py-4">
            <h2 class="mb-4 text-center fw-bold text-primary">Mes soumissions - {{ $academicYear->name }}</h2>

            @if ($submissions->isEmpty())
                <div class="alert alert-info fs-5 text-center d-flex justify-content-center align-items-center gap-2">
                    <i class="fas fa-info-circle fs-3"></i>
                    Aucun soumission trouvée pour cette année académique.
                </div>
            @else
                <div class="card shadow-sm rounded-4 border-0">
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-primary text-center text-uppercase small">
                                <tr>
                                    <th>Type</th>
                                    <th>Titre</th>
                                    <th>Encadrant</th>
                                    <th style="min-width: 200px;">Taux de plagiat</th>
                                    <th style="min-width: 180px;">Statut plagiat</th>
                                    <th style="min-width: 180px;">Statut de validation</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($submissions as $sub)
                                    <tr>
                                        <td class="text-center">
                                            <span class="badge bg-info px-3 py-2 shadow-sm" title="Type de soumission">
                                                {{ $sub->type == 1 ? 'Mémoire' : 'Projet' }}
                                            </span>
                                        </td>
                                        <td>{{ $sub->type == 1 ? $sub->subject : $sub->project_name }}</td>
                                        <td>{{ $sub->type == 1 ? $sub->directeur->full_name : $sub->encadreur->full_name }}
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center" style="gap:10px;">
                                                <div class="progress flex-grow-1"
                                                    style="height: 22px; border-radius:15px; overflow:hidden; box-shadow: inset 0 1px 3px rgba(0,0,0,0.2);">
                                                    <div class="progress-bar
                                                    @if ($sub->plagiarism_rate > 20) bg-danger
                                                    @elseif($sub->plagiarism_rate > 10) bg-warning
                                                    @else bg-success @endif"
                                                        role="progressbar" style="width: {{ $sub->plagiarism_rate }}%;">
                                                    </div>
                                                </div>
                                                <span class="fw-semibold fs-6" style="min-width:40px; text-align:right;">
                                                    {{ $sub->plagiarism_rate }}%
                                                </span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge
                                            @if ($sub->plagiarism_rate <= 10) bg-success
                                            @else bg-warning text-dark @endif px-3 py-2 shadow-sm"
                                                title="Statut plagiat">
                                                {{ $sub->plagiarism_rate <= 10 ? 'Accepté' : 'En revue' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $statusClass =
                                                    $sub->status === 'accepted'
                                                        ? 'bg-success'
                                                        : ($sub->status === 'rejected'
                                                            ? 'bg-danger'
                                                            : 'bg-secondary');
                                                $statusLabel = ucfirst($sub->status);
                                            @endphp
                                            <span class="badge {{ $statusClass }} px-3 py-2 shadow-sm"
                                                title="Statut de validation">
                                                {{ $statusLabel }}
                                            </span>
                                        </td>
                                        <td class="text-center">{{ $sub->created_at->format('d/m/Y H:i') }}</td>
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
