@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <div class="row mb-2 align-items-center">
                    <div class="col-sm-6">
                        <h1 class="h3 fw-bold text-primary"> Mon Emploi du Temps des Examens</h1>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content pb-5">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-10">

                        @include('_message')

                        @foreach ($getRecord as $value)
                            <h2 class="mb-3 fs-3">
                                Classe : <span class="text-primary">{{ $value['class_name'] }}
                                    {{ $value['class_opt'] }}</span>
                            </h2>
                            <div class="card shadow-sm rounded-4 border-0 mb-5">
                                <div class="card-header bg-primary text-white rounded-top-4">
                                    <h3 class="card-title mb-0">
                                        <i class="fa-solid fa-calendar-days me-2"></i> Détails de l'examen
                                    </h3>
                                </div>
                                <div class="card-body p-0 table-responsive">
                                    <table class="table table-bordered table-striped mb-0 styled-table">
                                        <thead class="table-primary text-center text-uppercase small">
                                            <tr>
                                                <th style="min-width: 280px;">Nom de la matière</th>
                                                <th>Jour</th>
                                                <th style="min-width: 150px;">Date de l'examen</th>
                                                <th style="min-width: 150px;">Heure de début</th>
                                                <th style="min-width: 150px;">Heure de fin</th>
                                                <th style="min-width: 150px;">Numéro de salle</th>
                                                <th style="min-width: 120px;">Note maximale</th>
                                                <th style="min-width: 120px;">Note de passage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($value['exam'] as $exam)
                                                @foreach ($exam['subjects'] as $valueS)
                                                    <tr>
                                                        <td>{{ $valueS['subject_name'] }}</td>
                                                        <td class="text-center">
                                                            {{ \Carbon\Carbon::parse($valueS['exam_date'])->locale('fr')->isoFormat('dddd') }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{ \Carbon\Carbon::parse($valueS['exam_date'])->format('d-m-Y') }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{ \Carbon\Carbon::parse($valueS['start_time'])->format('h:i A') }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{ \Carbon\Carbon::parse($valueS['end_time'])->format('h:i A') }}
                                                        </td>
                                                        <td class="text-center">{{ $valueS['room_number'] ?? '-' }}</td>
                                                        <td class="text-center">{{ $valueS['full_marks'] }}</td>
                                                        <td class="text-center">{{ $valueS['passing_mark'] }}</td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach

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
