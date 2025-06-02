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
        <!-- Content Header -->
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <div class="row mb-2 align-items-center">
                    <div class="col-sm-6">
                        <h1 class="h3 fw-bold text-primary">
                            Mon Emploi du Temps ({{ $getClass->name }} - {{ $getSubject->name }})
                        </h1>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content pb-5">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-md-8">

                        <div class="card shadow-sm rounded-4 border-0">
                            <div class="card-header bg-primary text-white rounded-top-4">
                                <h3 class="card-title mb-0">
                                    {{ $getClass->name }} - {{ $getSubject->name }}
                                </h3>
                            </div>
                            <div class="card-body p-0 table-responsive">
                                <table class="table table-bordered table-striped mb-0 styled-table">
                                    <thead class="table-primary text-center text-uppercase small">
                                        <tr>
                                            <th>Semaine</th>
                                            <th>Heure de début</th>
                                            <th>Heure de fin</th>
                                            <th>Numéro de salle</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($getRecord as $valueW)
                                            <tr>
                                                <td class="text-center">{{ $valueW['week_name'] }}</td>
                                                <td class="text-center">
                                                    {{ !empty($valueW['start_time']) ? date('h:i A', strtotime($valueW['start_time'])) : '-' }}
                                                </td>
                                                <td class="text-center">
                                                    {{ !empty($valueW['end_time']) ? date('h:i A', strtotime($valueW['end_time'])) : '-' }}
                                                </td>
                                                <td class="text-center">{{ $valueW['room_number'] ?? '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-4">
                                                    <i class="fa-solid fa-calendar-times fa-2x mb-2"></i><br>
                                                    Aucun emploi du temps disponible.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>

    <style>
        .card {
            border-radius: 1.5rem;
        }

        .card-header {
            border-radius: 1.5rem 1.5rem 0 0;
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
            font-size: 0.95rem;
        }

        .styled-table tbody tr:hover {
            background-color: #e9f0ff;
            transition: background-color 0.3s ease;
        }

        .text-center {
            text-align: center !important;
        }
    </style>
@endsection
