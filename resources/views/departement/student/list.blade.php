@extends('layouts.app')

@section('style')
    <style type="text/css">
        .styled-table {
            border-collapse: collapse;
            margin: 25px 0;
            min-width: 400px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
            border-radius: 8px;
            overflow: hidden;
        }

        .styled-table thead tr {
            background-color: #009879;
            color: #ffffff;
            text-align: left;
        }

        .styled-table th,
        .styled-table td {
            padding: 12px 15px;
        }

        .styled-table tbody tr {
            border-bottom: 1px solid #dddddd;
        }

        .styled-table tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }

        .styled-table tbody tr:last-of-type {
            border-bottom: 2px solid #009879;
        }

        /* Effet survol (hover) */
        .styled-table tbody tr:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h1 class="h3 fw-bold text-primary">
                            Liste des étudiants du département <small class="text-muted">(Total :
                                {{ $getRecord->total() }})</small>
                        </h1>
                    </div>
                    <div class="col-sm-6 text-end">
                        <a href="{{ url('departement/student/add') }}" class="btn btn-info shadow-sm rounded-3">
                            <i class="fa-solid fa-file-circle-plus me-2"></i> Ajouter un étudiant
                        </a>
                        <!-- Ajoutez un bouton d’import si besoin -->
                    </div>
                </div>
            </div>
        </section>

        <section class="content pb-5">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">

                        <!-- Formulaire de recherche identique au votre, adapté si besoin -->

                        @include('_message')

                        <div class="card shadow-sm rounded-4 border-0">
                            <div
                                class="card-header bg-primary text-white rounded-top-4 d-flex justify-content-between align-items-center">
                                <h3 class="card-title mb-0">
                                    <i class="fa-solid fa-users me-2"></i>Liste des étudiants
                                </h3>
                                <form action="{{ url('departement/student/export_excel') }}" method="post"
                                    class="d-inline-block ms-auto">
                                    @csrf
                                    <!-- Ajoutez ici éventuellement hidden inputs pour exporter avec filtres -->
                                    <button class="btn btn-success">
                                        <i class="fa-solid fa-file-excel me-1"></i> Export Excel
                                    </button>
                                </form>
                            </div>
                            <div class="card-body p-0 table-responsive">
                                <table class="table table-hover table-bordered align-middle mb-0">
                                    <thead class="table-primary text-center text-uppercase small">
                                        <tr>
                                            <th>#</th>
                                            <th>Photo</th>
                                            <th>Nom</th>
                                            <th>Département</th>
                                            <th>Email</th>
                                            <th>N° Admission</th>
                                            <th>Classe</th>
                                            <th>Genre</th>
                                            <th>Date de naissance</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($getRecord as $value)
                                            <tr>
                                                <td class="text-center">
                                                    {{ ($getRecord->currentPage() - 1) * $getRecord->perPage() + $loop->iteration }}
                                                </td>
                                                <td class="text-center" style="min-width: 50px;">
                                                    @if (!empty($value->getProfileDirect()))
                                                        <img src="{{ $value->getProfileDirect() }}" alt="Photo"
                                                            class="rounded-circle"
                                                            style="width: 50px; height: 50px; object-fit: cover;">
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>
                                                <td style="min-width: 200px;">{{ $value->name }} {{ $value->last_name }}
                                                </td>
                                                <td style="min-width: 200px;">{{ $value->departement }}</td>
                                                <td style="min-width: 200px;">{{ $value->email }}</td>
                                                <td style="min-width: 200px;">{{ $value->admission_number }}</td>
                                                <td style="min-width: 300px;">
                                                    @if ($value->studentClasses->count())
                                                        @foreach ($value->studentClasses as $class)
                                                            {{ $class->name }} {{ $class->opt }}@if (!$loop->last)
                                                                ,
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>
                                                <td>{{ $value->gender }}</td>
                                                <td style="min-width: 200px;">
                                                    @if (!empty($value->date_of_birth))
                                                        {{ date('d-m-Y', strtotime($value->date_of_birth)) }}
                                                    @endif
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge {{ $value->status == 0 ? 'bg-success' : 'bg-secondary' }}">
                                                        {{ $value->status == 0 ? 'Actif' : 'Inactif' }}
                                                    </span>
                                                </td>
                                                <td style="min-width: 150px;">
                                                    <a href="{{ url('departement/student/edit/' . $value->id) }}"
                                                        class="btn btn-info btn-sm me-1" title="Modifier">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                    <a href="{{ url('departement/student/delete/' . $value->id) }}"
                                                        class="btn btn-danger btn-sm" title="Supprimer"
                                                        onclick="return confirm('Confirmer la suppression ?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="11" class="text-center text-muted py-4">Aucun étudiant
                                                        trouvé.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    @if ($getRecord->hasPages())
                                        <div class="mt-3 d-flex justify-content-end px-3">
                                            {!! $getRecord->appends(request()->except('page'))->links() !!}
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Ajoutez styles spécifiques ici si nécessaire -->

    @endsection
