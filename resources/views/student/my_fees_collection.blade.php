@extends('layouts.app')

@section('style')
    <style>
        /* Table styling */
        .styled-table {
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(14, 74, 107, 0.07);
            background: #fff;
            min-width: 400px;
        }

        .styled-table thead tr {
            background-color: #2176bd;
            color: #fff;
            text-align: left;
            font-weight: 600;
        }

        .styled-table th,
        .styled-table td {
            padding: 13px 16px;
            vertical-align: middle !important;
            border: none;
        }

        .styled-table tbody tr {
            border-bottom: 1px solid #e9ecef;
            transition: background 0.13s;
        }

        .styled-table tbody tr:nth-of-type(even) {
            background-color: #f8fbff;
        }

        .styled-table tbody tr:last-of-type {
            border-bottom: 2px solid #2176bd;
        }

        .styled-table tbody tr:hover {
            background-color: #e6f1fb;
            cursor: pointer;
        }

        /* Header styling */
        .content-header {
            background: #e9f0fa;
            padding: 1.2em 0 1em 0;
            margin-bottom: 2em;
            border-radius: 14px;
            border: 1px solid #dae0e7;
        }

        /* Bouton custom */
        .btn-primary {
            background-color: #2176bd;
            border-color: #2176bd;
            border-radius: 18px;
            font-weight: 600;
            letter-spacing: .5px;
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background-color: #1162a4;
            border-color: #1162a4;
        }

        /* Modal custom */
        .modal-content {
            border-radius: 14px;
        }

        .modal-header {
            background: #2176bd;
            color: #fff;
            border-top-left-radius: 14px;
            border-top-right-radius: 14px;
        }

        .modal-title {
            font-weight: 600;
        }

        .form-control,
        textarea {
            border-radius: 10px;
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
        @media (max-width: 767px) {
            .content-header h1 {
                font-size: 1.25rem;
                margin-bottom: 1rem;
            }

            .btn-primary {
                margin-top: 1em;
                width: 100%;
            }

            .styled-table th,
            .styled-table td {
                padding: .75rem;
                font-size: 0.97em;
                word-break: break-word;
            }
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header mb-4">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h1 class="fw-bold text-primary">Paiements et frais d'écolage</h1>
                    </div>
                    <div class="col-sm-6 text-end">
                        <button type="button" class="btn btn-primary" id="AddFees">
                            <i class="fas fa-plus-circle me-1"></i> Ajouter des frais
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        @include('_message')
                        <div class="card bg-white shadow-sm">
                            <div class="card-header">
                                <h3 class="card-title mb-0"><i class="fas fa-credit-card me-2"></i>Détails des paiements
                                </h3>
                            </div>
                            <div class="card-body p-0" style="overflow: auto;">
                                <table class="table styled-table table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th>Classe</th>
                                            <th>Montant total</th>
                                            <th>Montant payé</th>
                                            <th>Reste à payer</th>
                                            <th>Type paiement</th>
                                            <th>Remarque</th>
                                            <th>Enregistré par</th>
                                            <th>Date d'ajout</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($getFees as $value)
                                            <tr>
                                                <td>{{ $value->class_name }}</td>
                                                <td class="text-success fw-bold">
                                                    ${{ number_format($value->total_amount, 2) }}</td>
                                                <td class="text-info">${{ number_format($value->paid_amount, 2) }}</td>
                                                <td class="text-danger fw-semibold">
                                                    ${{ number_format($value->remaning_amount, 2) }}</td>
                                                <td>
                                                    <span class="badge bg-primary">{{ $value->payment_type }}</span>
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
                                                <td colspan="100%" class="text-center text-muted py-4">
                                                    <i class="fas fa-info-circle me-2"></i>Aucun enregistrement trouvé.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- END Card -->
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="AddFeesModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel"><i class="fas fa-money-check-alt me-2"></i>Ajouter des frais
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <form action="" method="post">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="mb-2">
                            <label class="form-label">Classe :</label>
                            <div class="form-control bg-light border-0">{{ $getStudent->name }}</div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Montant total :</label>
                            <div class="form-control bg-light border-0">${{ number_format($getStudent->amount, 2) }}</div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Montant payé :</label>
                            <div class="form-control bg-light border-0">${{ number_format($paid_amount, 2) }}</div>
                        </div>
                        <div class="mb-2">
                            @php $RemaningAmount = $getStudent->amount - $paid_amount; @endphp
                            <label class="form-label">Reste à payer :</label>
                            <div class="form-control bg-light border-0 text-danger">
                                ${{ number_format($RemaningAmount, 2) }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Montant <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="amount" min="0.01" step="0.01"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Type de paiement <span class="text-danger">*</span></label>
                            <select class="form-select form-control" name="payment_type" required>
                                <option value="">Sélectionner...</option>
                                <option value="Paypal">Paypal</option>
                                <option value="Stripe">Stripe</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Remarque</label>
                            <textarea class="form-control" name="remark" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light rounded-bottom-3">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary">Valider</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('AddFees').addEventListener('click', function() {
            var modal = new bootstrap.Modal(document.getElementById('AddFeesModal'));
            modal.show();
        });
    </script>
@endsection
