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
        <!-- En-tête de contenu (Header de la page) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Liste de Mes Étudiants</h1>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Contenu principal -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">

                        @include('_message')

                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Liste de Mes Étudiants</h3>
                            </div>
                            <div class="card-body p-0" style="overflow: auto;">
                                <table class="table styled-table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th style="min-width: 200px;">Photo de Profil</th>
                                            <th style="min-width: 200px;">Nom de l'Étudiant</th>
                                            <th>Email</th>
                                            <th style="min-width: 200px;">Numéro d'Admission</th>
                                            <th style="min-width: 200px;">Numéro de Rôle</th>
                                            <th>Classe</th>
                                            <th>Genre</th>
                                            <th style="min-width: 200px;">Date de Naissance </th>
                                            <th>Caste </th>
                                            <th>Religion</th>
                                            <th style="min-width: 200px;">Numéro de Téléphone</th>
                                            <th style="min-width: 200px;">Date d'Admission</th>
                                            <th style="min-width: 200px;">Groupe Sanguin</th>
                                            <th>Taille</th>
                                            <th>Poids</th>
                                            <th style="min-width: 200px;">Date de Création</th>
                                            <th style="min-width: 200px;">Taux de présence</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($getRecord as $value)
                                            <tr>
                                                <td style="min-width: 150px;">
                                                    {{ ($getRecord->currentPage() - 1) * $getRecord->perPage() + $loop->iteration }}
                                                </td>
                                                <td style="min-width: 150px;">
                                                    @if (!empty($value->getProfile()))
                                                        <img src="{{ $value->getProfile() }}"
                                                            style="height: 50px; width:50px; border-radius: 50px;">
                                                    @endif
                                                </td>

                                                <td style="min-width: 150px;">{{ $value->name }} {{ $value->last_name }}
                                                </td>
                                                <td style="min-width: 150px;">{{ $value->email }}</td>
                                                <td style="min-width: 150px;">{{ $value->admission_number }}</td>
                                                <td style="min-width: 150px;">{{ $value->roll_number }}</td>
                                                <td style="min-width: 150px;">{{ $value->class_name }}</td>
                                                <td style="min-width: 150px;">{{ $value->gender }}</td>
                                                <td style="min-width: 150px;">
                                                    @if (!empty($value->date_of_birth))
                                                        {{ date('d-m-Y', strtotime($value->date_of_birth)) }}
                                                    @endif
                                                </td>
                                                <td style="min-width: 150px;">{{ $value->caste }}</td>
                                                <td style="min-width: 150px;">{{ $value->religion }}</td>
                                                <td style="min-width: 150px;">{{ $value->mobile_number }}</td>
                                                <td style="min-width: 150px;">
                                                    @if (!empty($value->admission_date))
                                                        {{ date('d-m-Y', strtotime($value->admission_date)) }}
                                                    @endif
                                                </td>
                                                <td style="min-width: 150px;">{{ $value->blood_group }}</td>
                                                <td style="min-width: 150px;">{{ $value->height }}</td>
                                                <td style="min-width: 150px;">{{ $value->weight }}</td>
                                                <td style="min-width: 150px;">
                                                    {{ date('d-m-Y H:i A', strtotime($value->created_at)) }}</td>
                                                <td style="min-width: 150px;">
                                                    @if (isset($value->attendanceRate))
                                                        <div class="progress progress-sm">
                                                            <div class="progress-bar bg-green" role="progressbar"
                                                                aria-valuenow="{{ $value->attendanceRate }}"
                                                                aria-valuemin="0" aria-valuemax="100"
                                                                style="width: {{ $value->attendanceRate }}%;">
                                                            </div>
                                                        </div>
                                                        <small><b>{{ number_format($value->attendanceRate, 2) }}% de
                                                                présence</b></small>
                                                    @else
                                                        <small>Aucune donnée</small>
                                                    @endif
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div style="padding: 10px; float: right;">
                                    {!! $getRecord->appends(Illuminate\Support\Facades\Request::except('page'))->links() !!}
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    </div>
@endsection
