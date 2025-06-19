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

        .present {
            color: white;
            background-color: #28a745;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .absent {
            color: white;
            background-color: #dc3545;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .half {
            color: white;
            background-color: #282aa7;
            padding: 5px 10px;
            border-radius: 5px;

        }

        .late {
            color: white;
            background-color: #dc9435;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .table-primary th {
            background-color: #cfe2ff;
            color: #084298;
        }

        .badge {
            font-size: 0.9rem;
            padding: 0.4em 0.75em;
        }

        .progress-bar {
            border-radius: 12px;
        }

        .alert-warning {
            font-size: 1rem;
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
                        <h1 class="h3 fw-bold text-primary">
                            Mes Présences
                            <small class="text-muted" style="font-weight: 500;">
                                (Total : <span class="text-info">{{ $getRecord->total() }}</span>)
                            </small>
                        </h1>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-md-12">

                        <div class="card shadow-sm rounded-3 border-0">
                            <div class="card-header bg-primary text-white">
                                <h3 class="card-title mb-0">Détails des présences</h3>
                            </div>

                            <div class="card-body p-0 table-responsive">
                                @if ($getRecord->count() > 0)
                                    <table class="table table-striped table-bordered align-middle mb-0">
                                        <thead class="table-primary text-center text-uppercase small">
                                            <tr>
                                                <th>Classe</th>
                                                <th>Type</th>
                                                <th>Date</th>
                                                <th style="min-width: 180px;">Créé le</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($getRecord as $value)
                                                <tr>
                                                    <td class="fw-semibold text-center" style="min-width: 180px;">
                                                        {{ $value->class_name }} {{ $value->class_opt }}
                                                    </td>
                                                    <td class="text-center">
                                                        @switch($value->attendance_type)
                                                            @case(1)
                                                                <span class="badge bg-success">Présent</span>
                                                            @break

                                                            @case(2)
                                                                <span class="badge bg-warning text-dark">Retard</span>
                                                            @break

                                                            @case(3)
                                                                <span class="badge bg-danger">Absent</span>
                                                            @break

                                                            @case(4)
                                                                <span class="badge bg-info text-dark">Demi-journée</span>
                                                            @break

                                                            @default
                                                                <span class="badge bg-secondary">Inconnu</span>
                                                        @endswitch
                                                    </td>
                                                    <td class="text-center" style="min-width: 180px;">
                                                        {{ \Carbon\Carbon::parse($value->attendance_date)->format('d-m-Y') }}
                                                    </td>
                                                    <td class="text-center" style="min-width: 180px;">
                                                        {{ \Carbon\Carbon::parse($value->created_at)->format('d-m-Y H:i') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div
                                        class="alert alert-warning m-4 d-flex align-items-center gap-2 rounded-3 shadow-sm">
                                        <i class="fas fa-exclamation-triangle fs-4"></i>
                                        <span class="fs-6">Aucune présence trouvée pour cette période.</span>
                                    </div>
                                @endif
                            </div>

                            @if ($getRecord->count() > 0)
                                <div class="card-footer bg-white">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <div class="progress" style="height: 25px; border-radius: 12px;">
                                                <div class="progress-bar bg-success fw-bold" role="progressbar"
                                                    style="width: {{ $attendanceRate }}%; font-size: 1rem; line-height: 25px;"
                                                    aria-valuenow="{{ $attendanceRate }}" aria-valuemin="0"
                                                    aria-valuemax="100">
                                                    {{ $attendanceRate }}% Présence
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            {!! $getRecord->appends(request()->except('page'))->links() !!}
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>

                    </div>
                </div>

            </div>
        </section>
    </div>

@endsection



@section('script')
    .
@endsection
