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

        .table-primary th {
            background-color: #cfe2ff;
            color: #084298;
        }

        .fw-semibold {
            font-weight: 600;
        }

        .alert-info {
            background-color: #e7f1ff;
            color: #084298;
            border-color: #b6d4fe;
        }
    </style>
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h1 class="h3 fw-bold text-primary">Mon Emploi du Temps des Examens</h1>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                @include('_message')

                <div class="row g-4">
                    @forelse ($getRecord as $value)
                        <div class="col-12">
                            <div class="card shadow-sm rounded-3 border-0">
                                <div class="card-header bg-primary text-white">
                                    <h3 class="card-title mb-0">{{ $value['name'] }}</h3>
                                </div>
                                <div class="card-body p-0 table-responsive">
                                    <table class="table table-striped table-bordered align-middle mb-0">
                                        <thead class="table-primary text-center text-uppercase small">
                                            <tr>
                                                <th style="min-width: 250px;">Nom du sujet</th>
                                                <th>Jour</th>
                                                <th style="min-width: 150px;">Date d'examen</th>
                                                <th style="min-width: 120px;">Heure début</th>
                                                <th style="min-width: 120px;">Heure fin</th>
                                                <th style="min-width: 150px;">Numéro de salle</th>
                                                <th style="min-width: 120px;">Note maximale</th>
                                                <th style="min-width: 120px;">Note de passage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($value['exam'] as $valueS)
                                                <tr>
                                                    <td class="fw-semibold" style="min-width: 250px;">
                                                        {{ $valueS['subject_name'] }}</td>
                                                    <td class="text-center">
                                                        {{ \Carbon\Carbon::parse($valueS['exam_date'])->translatedFormat('l') }}
                                                    </td>
                                                    <td class="text-center" style="min-width: 150px;">
                                                        {{ \Carbon\Carbon::parse($valueS['exam_date'])->format('d-m-Y') }}
                                                    </td>
                                                    <td class="text-center" style="min-width: 120px;">
                                                        {{ \Carbon\Carbon::parse($valueS['start_time'])->format('h:i A') }}
                                                    </td>
                                                    <td class="text-center" style="min-width: 120px;">
                                                        {{ \Carbon\Carbon::parse($valueS['end_time'])->format('h:i A') }}
                                                    </td>
                                                    <td class="text-center" style="min-width: 150px;">
                                                        {{ $valueS['room_number'] ?? '-' }}</td>
                                                    <td class="text-center" style="min-width: 120px;">
                                                        {{ $valueS['full_marks'] ?? '-' }}</td>
                                                    <td class="text-center" style="min-width: 120px;">
                                                        {{ $valueS['passing_mark'] ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info text-center shadow-sm rounded-3">
                                Aucun emploi du temps d'examen disponible pour cette année académique.
                            </div>
                        </div>
                    @endforelse
                </div>

            </div>
        </section>
    </div>

@endsection
