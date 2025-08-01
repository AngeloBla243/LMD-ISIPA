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
            padding: 14px 17px;
            vertical-align: middle !important;
            border: none;
            font-size: 1.05em;
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
            border-radius: 16px !important;
            font-weight: 600;
            padding: .53em 1.35em;
            transition: background .17s;
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

        .content-header,
        .card-header {
            background: #e9f0fa;
            padding: .9em 0 .9em 0;
            border-radius: 14px 14px 0 0 !important;
            border: 1px solid #dae0e7;
            margin-bottom: 0;
            font-weight: 700;
            color: #2176bd;
        }

        h1,
        h3 {
            color: #2176bd;
            font-weight: 800;
            letter-spacing: .9px;
        }

        .card {
            border-radius: 18px;
            border: none;
            box-shadow: 0 8px 24px rgba(14, 74, 107, 0.06);
            margin-bottom: 2.3em;
        }

        .alert-info {
            background: #eaf6ff;
            color: #2176bd;
            border-radius: 12px;
            margin-bottom: 1.3em;
        }

        /* Export button floating right and neat on mobile */
        .export-btn-form {
            float: right;
            margin-top: -7px;
        }

        @media (max-width: 900px) {

            .styled-table th,
            .styled-table td {
                padding: 10px 6px;
                font-size: .97em;
            }

            .btn,
            .btn-primary,
            .btn-success {
                padding: .44em 1.1em;
                font-size: .98em;
            }

            .export-btn-form {
                float: none;
                margin: 12px 0 0 0;
                display: flex;
                justify-content: flex-end;
            }
        }

        @media (max-width: 600px) {

            .content-header,
            .card-header {
                font-size: .98em;
            }

            .styled-table {
                min-width: unset;
            }

            h1 {
                font-size: 1.25em;
            }
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header mb-3">
            <div class="container-fluid">
                <div class="row mb-2 align-items-center">
                    <div class="col-sm-6">
                        <h1 class="fw-bold">
                            <i class="fas fa-chart-bar me-2"></i>
                            Collect Fees Report
                        </h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">

                        <!-- Search Card -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-search me-2"></i> Recherche dans le rapport de collecte des frais
                                </h3>
                            </div>
                            <form method="get" action="">
                                <div class="card-body">
                                    <div class="row gy-2">
                                        <div class="form-group col-md-2">
                                            <label>ID Étudiant</label>
                                            <input type="text" class="form-control" placeholder="ID"
                                                value="{{ Request::get('student_id') }}" name="student_id">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>Prénom</label>
                                            <input type="text" class="form-control" placeholder="Prénom"
                                                value="{{ Request::get('student_name') }}" name="student_name">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>Nom</label>
                                            <input type="text" class="form-control" placeholder="Nom"
                                                value="{{ Request::get('student_last_name') }}" name="student_last_name">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>Classe</label>
                                            <select class="form-control" name="class_id">
                                                <option value="">Sélectionner</option>
                                                @foreach ($getClass as $class)
                                                    <option {{ Request::get('class_id') == $class->id ? 'selected' : '' }}
                                                        value="{{ $class->id }}">{{ $class->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>Date création (début)</label>
                                            <input type="date" class="form-control"
                                                value="{{ Request::get('start_created_date') }}" name="start_created_date">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>Date création (fin)</label>
                                            <input type="date" class="form-control"
                                                value="{{ Request::get('end_created_date') }}" name="end_created_date">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>Type de paiement</label>
                                            <select class="form-control" name="payment_type">
                                                <option value="">Sélectionner</option>
                                                <option {{ Request::get('payment_type') == 'Cash' ? 'selected' : '' }}
                                                    value="Cash">Espèces</option>
                                                <option {{ Request::get('payment_type') == 'Cheque' ? 'selected' : '' }}
                                                    value="Cheque">Chèque</option>
                                                <option {{ Request::get('payment_type') == 'Paypal' ? 'selected' : '' }}
                                                    value="Paypal">Paypal</option>
                                                <option {{ Request::get('payment_type') == 'Stripe' ? 'selected' : '' }}
                                                    value="Stripe">Stripe</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3 d-flex align-items-end">
                                            <button class="btn btn-primary me-2" type="submit"><i
                                                    class="fa-solid fa-magnifying-glass"></i> Rechercher</button>
                                            <a href="{{ url('admin/fees_collection/collect_fees_report') }}"
                                                class="btn btn-success">Réinitialiser</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- End Search Card -->

                        @include('_message')

                        <!-- Report Table Card -->
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title"><i class="fas fa-file-invoice-dollar me-2"></i> Rapport de collecte
                                    des frais</h3>
                                <form class="export-btn-form" method="post"
                                    action="{{ url('admin/fees_collection/export_collect_fees_report') }}">
                                    {{ csrf_field() }}
                                    <input type="hidden" value="{{ Request::get('student_id') }}" name="student_id">
                                    <input type="hidden" value="{{ Request::get('student_name') }}" name="student_name">
                                    <input type="hidden" value="{{ Request::get('student_last_name') }}"
                                        name="student_last_name">
                                    <input type="hidden" value="{{ Request::get('class_id') }}" name="class_id">
                                    <input type="hidden" value="{{ Request::get('start_created_date') }}"
                                        name="start_created_date">
                                    <input type="hidden" value="{{ Request::get('end_created_date') }}"
                                        name="end_created_date">
                                    <input type="hidden" value="{{ Request::get('payment_type') }}" name="payment_type">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa-solid fa-file-excel me-2"></i> Exporter Excel
                                    </button>
                                </form>
                            </div>
                            <div class="card-body p-0" style="overflow: auto;">
                                <table class="table styled-table table-bordered table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th style="min-width: 250px;">ID Étudiant</th>
                                            <th style="min-width: 250px;">Nom de l'étudiant</th>
                                            <th style="min-width: 250px;">Classe</th>
                                            <th style="min-width: 250px;">Montant total</th>
                                            <th style="min-width: 250px;">Montant payé</th>
                                            <th style="min-width: 250px;">Reste à payer</th>
                                            <th style="min-width: 250px;">Type paiement</th>
                                            <th style="min-width: 250px;">Remarque</th>
                                            <th style="min-width: 250px;">Enregistré par</th>
                                            <th style="min-width: 250px;">Date création</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($getRecord as $value)
                                            <tr>
                                                <td>{{ $value->id }}</td>
                                                <td>{{ $value->student_id }}</td>
                                                <td>{{ $value->student_name_first }} {{ $value->student_name_last }}</td>
                                                <td>{{ $value->class_name }}</td>
                                                <td class="text-success fw-bold">
                                                    ${{ number_format($value->total_amount, 2) }}</td>
                                                <td class="text-info">${{ number_format($value->paid_amount, 2) }}</td>
                                                <td class="text-danger fw-semibold">
                                                    ${{ number_format($value->remaning_amount, 2) }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-primary">{{ $value->payment_type ?? 'Non payé' }}</span>
                                                </td>
                                                <td>{{ $value->remark }}</td>
                                                <td>
                                                    <span
                                                        class="badge rounded-pill bg-light text-dark border border-primary">
                                                        {{ $value->created_name }}
                                                    </span>
                                                </td>
                                                <td>{{ date('d-m-Y', strtotime($value->created_at)) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="11" class="text-center text-muted py-4">
                                                    <i class="fas fa-info-circle me-2"></i>Aucun enregistrement trouvé.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="p-3">
                                    {!! $getRecord->appends(Illuminate\Support\Facades\Request::except('page'))->links() !!}
                                </div>
                            </div>
                        </div>
                        <!-- End Card -->
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
