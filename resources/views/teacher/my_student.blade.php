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
        <!-- En-tête de contenu -->
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="h3 fw-bold text-primary">Liste de Mes Étudiants</h1>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contenu principal -->
        <section class="content pb-5">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">

                        @include('_message')

                        <div class="card shadow-sm rounded-4 border-0">
                            <div class="card-header bg-primary text-white rounded-top-4">
                                <h3 class="card-title mb-0">
                                    <i class="fa-solid fa-users me-2"></i>Liste de Mes Étudiants
                                </h3>
                            </div>
                            <div class="card-body p-0 table-responsive">
                                <table class="table table-hover table-bordered align-middle mb-0">
                                    <thead class="table-primary text-center text-uppercase small">
                                        <tr>
                                            <th>#</th>
                                            <th style="min-width: 120px;">Photo</th>
                                            <th style="min-width: 180px;">Nom de l'Étudiant</th>
                                            <th>Email</th>
                                            <th style="min-width: 150px;">N° Admission</th>
                                            <th style="min-width: 130px;">N° Rôle</th>
                                            <th style="min-width: 120px;">Classe</th>
                                            <th>Genre</th>
                                            <th style="min-width: 200px;">Date de Naissance</th>
                                            <th>Caste</th>
                                            <th>Religion</th>
                                            <th style="min-width: 150px;">Téléphone</th>
                                            <th style="min-width: 140px;">Date Admission</th>
                                            <th style="min-width: 200px;">Groupe Sanguin</th>
                                            <th>Taille</th>
                                            <th>Poids</th>
                                            <th style="min-width: 160px;">Créé le</th>
                                            <th style="min-width: 170px;">Taux de présence</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($getRecord as $value)
                                            <tr>
                                                <td class="text-center">
                                                    {{ ($getRecord->currentPage() - 1) * $getRecord->perPage() + $loop->iteration }}
                                                </td>
                                                <td class="text-center">
                                                    @if (!empty($value->getProfile()))
                                                        <img src="{{ $value->getProfile() }}"
                                                            style="height: 48px; width:48px; object-fit:cover; border-radius: 50%;">
                                                    @endif
                                                </td>
                                                <td>{{ $value->name }} {{ $value->last_name }}</td>
                                                <td>{{ $value->email }}</td>
                                                <td>{{ $value->admission_number }}</td>
                                                <td>{{ $value->roll_number }}</td>
                                                <td style="min-width: 200px;">{{ $value->class_name }}
                                                    {{ $value->class_opt }}</td>
                                                <td>{{ $value->gender }}</td>
                                                <td>
                                                    @if (!empty($value->date_of_birth))
                                                        {{ date('d-m-Y', strtotime($value->date_of_birth)) }}
                                                    @endif
                                                </td>
                                                <td>{{ $value->caste }}</td>
                                                <td>{{ $value->religion }}</td>
                                                <td>{{ $value->mobile_number }}</td>
                                                <td>
                                                    @if (!empty($value->admission_date))
                                                        {{ date('d-m-Y', strtotime($value->admission_date)) }}
                                                    @endif
                                                </td>
                                                <td>{{ $value->blood_group }}</td>
                                                <td>{{ $value->height }}</td>
                                                <td>{{ $value->weight }}</td>
                                                <td>
                                                    {{ date('d-m-Y H:i A', strtotime($value->created_at)) }}
                                                </td>
                                                <td>
                                                    @if (isset($value->attendanceRate))
                                                        <div class="progress" style="height: 22px;">
                                                            <div class="progress-bar bg-success" role="progressbar"
                                                                style="width: {{ $value->attendanceRate }}%"
                                                                aria-valuenow="{{ $value->attendanceRate }}"
                                                                aria-valuemin="0" aria-valuemax="100">
                                                                {{ round($value->attendanceRate, 2) }}%
                                                            </div>
                                                        </div>
                                                        <small class="fw-semibold text-success">
                                                            {{ number_format($value->attendanceRate, 2) }}% de présence
                                                        </small>
                                                    @else
                                                        <small class="text-muted">Aucune donnée</small>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="mt-3 d-flex justify-content-end px-3">
                                    @if ($getRecord instanceof \Illuminate\Pagination\AbstractPaginator)
                                        {!! $getRecord->appends(Illuminate\Support\Facades\Request::except('page'))->links() !!}
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>

    <style>
        .card {
            border-radius: 1.25rem;
        }

        .card-header {
            border-radius: 1.25rem 1.25rem 0 0;
        }

        .table-primary th {
            background-color: #cfe2ff !important;
            color: #084298 !important;
            font-weight: 600;
            vertical-align: middle;
        }

        .table td,
        .table th {
            vertical-align: middle;
            font-size: 0.96rem;
        }

        .progress {
            background-color: #e9ecef;
            border-radius: 0.75rem;
            height: 22px;
        }

        .progress-bar {
            font-weight: 600;
            font-size: 1em;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        img[style*="border-radius: 50%"] {
            border: 2px solid #0d6efd;
            box-shadow: 0 2px 8px rgba(13, 110, 253, 0.08);
        }
    </style>
@endsection
