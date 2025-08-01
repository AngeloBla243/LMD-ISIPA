@extends('layouts.app')

@section('style')
    <style>
        .styled-table {
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(14, 74, 107, 0.07);
            background: #fff;
            min-width: 400px;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        .styled-table thead tr {
            background: #2176bd;
            color: #fff;
            text-align: left;
            font-weight: 600;
        }

        .styled-table th,
        .styled-table td {
            padding: 15px 18px;
            vertical-align: middle !important;
            border: none;
            font-size: 1.04em;
        }

        .styled-table tbody tr {
            border-bottom: 1px solid #e9ecef;
            transition: background 0.12s;
        }

        .styled-table tbody tr:nth-of-type(even) {
            background: #f8fbff;
        }

        .styled-table tbody tr:last-of-type {
            border-bottom: 2px solid #2176bd;
        }

        .styled-table tbody tr:hover {
            background: #e6f1fb;
            cursor: pointer;
        }

        .btn-primary,
        .btn-success {
            border-radius: 18px;
            font-weight: 600;
            letter-spacing: .3px;
            padding: .55em 1.35em;
            transition: background .19s;
            font-size: 1em;
        }

        .btn-primary {
            background: #2176bd !important;
            border-color: #2176bd !important;
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background: #145894 !important;
            border-color: #145894 !important;
        }

        .btn-success {
            background: #27c381;
            border-color: #27c381;
        }

        .btn-success:hover,
        .btn-success:focus {
            background: #22b074;
            border-color: #22b074;
        }

        .form-control {
            border-radius: 11px;
            border: 1.5px solid #dde6f7;
            box-shadow: 0 1px 4px rgba(33, 118, 189, 0.03);
            transition: border-color 0.12s;
        }

        .form-control:focus {
            border-color: #2176bd;
            box-shadow: 0 2px 8px rgba(33, 118, 189, 0.12);
        }

        .content-header {
            background: #e9f0fa;
            padding: 1em 0 1.1em 0;
            margin-bottom: 2em;
            border-radius: 14px;
            border: 1px solid #dae0e7;
        }

        h1,
        h3 {
            color: #2176bd;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .card {
            border-radius: 18px;
            border: none;
            box-shadow: 0 8px 24px rgba(14, 74, 107, 0.06);
        }

        .card-header {
            background: #f5faff;
            border-bottom: 1px solid #e6eaf0;
            font-weight: 600;
            color: #2176bd;
            border-radius: 18px 18px 0 0 !important;
        }

        .alert-info {
            background: #eaf6ff;
            color: #2176bd;
            border-radius: 12px;
        }

        /* Responsive */
        @media (max-width: 900px) {

            .styled-table th,
            .styled-table td {
                padding: 9px 6px;
                font-size: .99em;
            }

            h1,
            h3 {
                font-size: 1.35rem;
            }

            .btn,
            .btn-primary,
            .btn-success {
                padding: .43em 1.2em;
                font-size: .98em;
            }
        }

        @media (max-width: 600px) {

            .content-header,
            .card-header {
                font-size: .97em;
            }

            .styled-table {
                min-width: unset;
            }
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">

        <!-- Content Header -->
        <section class="content-header mb-4">
            <div class="container-fluid">
                <div class="row mb-2 align-items-center">
                    <div class="col-sm-6">
                        <h1 class="fw-bold">
                            <i class="fas fa-money-check-dollar me-2"></i>
                            Collect Fees
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

                        <!-- Search Card -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fa-solid fa-magnifying-glass me-2"></i>Recherche d'étudiant
                                </h3>
                            </div>
                            <form method="get" action="">
                                <div class="card-body">
                                    <div class="row g-3">

                                        <div class="form-group col-md-2">
                                            <label>Classe</label>
                                            <select class="form-control" name="class_id">
                                                <option value="">Sélectionnez la classe</option>
                                                @foreach ($getClass as $class)
                                                    <option {{ Request::get('class_id') == $class->id ? 'selected' : '' }}
                                                        value="{{ $class->id }}">{{ $class->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label>ID Étudiant</label>
                                            <input type="text" class="form-control"
                                                value="{{ Request::get('student_id') }}" name="student_id" placeholder="ID">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>Prénom</label>
                                            <input type="text" class="form-control"
                                                value="{{ Request::get('first_name') }}" name="first_name"
                                                placeholder="Prénom">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>Nom</label>
                                            <input type="text" class="form-control"
                                                value="{{ Request::get('last_name') }}" name="last_name" placeholder="Nom">
                                        </div>

                                        <div class="form-group col-md-2 d-flex align-items-end">
                                            <button class="btn btn-primary w-100 me-2" type="submit">
                                                <i class="fa-solid fa-magnifying-glass"></i> Rechercher
                                            </button>
                                            {{-- <a href="{{ url('admin/fees_collection/collect_fees') }}"
                                                class="btn btn-success w-100">Réinitialiser</a> --}}
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- End Search Card -->

                        @include('_message')

                        <!-- Students Table -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-users me-2"></i>Liste des étudiants</h3>
                            </div>
                            <div class="card-body p-0" style="overflow-x: auto;">
                                <table class="table styled-table table-bordered table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th style="min-width: 250px;">ID Étudiant</th>
                                            <th style="min-width: 250px;">Nom de l'étudiant</th>
                                            <th style="min-width: 250px;">Classe</th>
                                            <th style="min-width: 250px;">Montant total</th>
                                            <th style="min-width: 250px;">Montant payé</th>
                                            <th style="min-width: 250px;">Reste à payer</th>
                                            <th style="min-width: 250px;">Date d'inscription</th>
                                            <th style="min-width: 250px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($getRecord))
                                            @forelse($getRecord as $value)
                                                @php
                                                    $paid_amount = $value->getPaidAmount($value->id, $value->class_id);
                                                    $RemaningAmount = $value->amount - $paid_amount;
                                                @endphp
                                                <tr>
                                                    <td>{{ $value->id }}</td>
                                                    <td>{{ $value->name }} {{ $value->last_name }}</td>
                                                    <td>{{ $value->class_name }}</td>
                                                    <td class="text-success fw-bold">
                                                        ${{ number_format($value->amount, 2) }}</td>
                                                    <td class="text-info">${{ number_format($paid_amount, 2) }}</td>
                                                    <td class="text-danger fw-semibold">
                                                        ${{ number_format($RemaningAmount, 2) }}</td>
                                                    <td>
                                                        {{ date('d-m-Y', strtotime($value->created_at)) }}
                                                    </td>
                                                    <td>
                                                        <a href="{{ url('admin/fees_collection/collect_fees/add_fees/' . $value->id) }}"
                                                            class="btn btn-success btn-sm rounded-5 px-3">
                                                            <i class="far fa-credit-card me-1"></i>Collecter
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center text-muted py-4">
                                                        <i class="fas fa-info-circle me-2"></i>Aucun étudiant trouvé.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        @else
                                            <tr>
                                                <td colspan="8" class="text-center text-muted py-4">
                                                    <i class="fas fa-info-circle me-2"></i>Aucun étudiant trouvé.
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                                <div class="p-3">
                                    @if (!empty($getRecord))
                                        {!! $getRecord->appends(Illuminate\Support\Facades\Request::except('page'))->links() !!}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!-- End Students Table -->
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
