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

        .btn-success {
            background-color: #28a745 !important;
            border-color: #28a745 !important;
        }

        .btn-danger {
            background-color: #dc3545 !important;
            border-color: #dc3545 !important;
        }

        a[disabled] {
            pointer-events: none;
            opacity: .5;
            background: #cccccc !important;
            color: #888 !important;
        }
    </style>
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="h3 fw-bold text-primary">Liste des Recours</h1>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content pb-5">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">

                        <!-- Search Card -->
                        <div class="card shadow-sm rounded-4 border-0 mb-4">
                            <div class="card-header bg-primary text-white rounded-top-4">
                                <h3 class="card-title mb-0">
                                    <i class="fa-solid fa-filter me-2"></i>Filtrer les Recours
                                </h3>
                            </div>
                            <form method="get" action="">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Année Académique</label>
                                            <select name="academic_year_id" class="form-select form-control"
                                                onchange="this.form.submit()">
                                                <option value="">Sélectionner</option>
                                                @foreach ($academicYears as $year)
                                                    <option value="{{ $year->id }}"
                                                        {{ $selectedAcademicYearId == $year->id ? 'selected' : '' }}>
                                                        {{ $year->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Classe</label>
                                            <select name="class_id" class="form-select form-control"
                                                {{ empty($filteredClasses) ? 'disabled' : '' }}>
                                                <option value="">Sélectionner</option>
                                                @foreach ($filteredClasses as $class)
                                                    <option value="{{ $class->id }}"
                                                        {{ $selectedClassId == $class->id ? 'selected' : '' }}>
                                                        {{ $class->name }} {{ $class->opt }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 d-flex align-items-end gap-2">
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="fa-solid fa-filter me-1"></i> Filtrer
                                            </button>
                                            <a href="{{ url('admin/recours/list') }}" class="btn btn-success w-100">
                                                Réinitialiser
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        @include('_message')

                        <!-- List Card -->
                        <div class="card shadow-sm rounded-4 border-0">
                            <div class="card-body p-0 table-responsive">
                                <table class="table table-hover table-bordered align-middle mb-0">
                                    <thead class="table-primary text-center text-uppercase small">
                                        <tr>
                                            <th>ID</th>
                                            <th style="min-width: 250px;">Nom de l'Étudiant</th>
                                            <th>Classe</th>
                                            <th>Matière</th>
                                            <th>Objet</th>
                                            <th>Session</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recours as $recour)
                                            <tr>
                                                <td class="text-center">{{ $recour->id }}</td>
                                                <td style="min-width: 200px;">{{ $recour->student->name }}
                                                    {{ $recour->student->last_name }}</td>
                                                <td style="min-width: 200px;">{{ $recour->class->name }}
                                                    {{ $recour->class->opt }}</td>
                                                <td style="min-width: 200px;">{{ $recour->subject->name }}</td>
                                                <td style="min-width: 250px;">{{ $recour->objet }}</td>
                                                <td style="min-width: 200px;">{{ $recour->session_year }}</td>
                                                <td class="text-center" style="min-width: 300px;">
                                                    <!-- Bouton de validation : pouce bleu ou rouge selon $recour->status -->
                                                    <form method="POST"
                                                        action="{{ route('admin.recours.toggle_status', $recour->id) }}"
                                                        style="display:inline;">
                                                        @csrf
                                                        <button type="submit"
                                                            class="btn {{ $recour->status ? 'btn-success' : 'btn-danger' }}"
                                                            title="{{ $recour->status ? 'Rejeter' : 'Valider' }}">
                                                            <i
                                                                class="fas fa-thumbs-{{ $recour->status ? 'up' : 'down' }}"></i>
                                                        </button>
                                                    </form>
                                                    @if ($recour->status && $recour->exam_id && $recour->student_id)
                                                        <button class="btn btn-info open-mark-modal"
                                                            data-url="{{ route('admin.recours.mark_register_modal') }}?class_id={{ $recour->class_id }}&exam_id={{ $recour->exam_id }}&student_id={{ $recour->student_id }}&subject_id={{ $recour->subject_id }}&academic_year_id={{ $recour->academic_year_id }}">
                                                            <i class="fas fa-pencil-alt"></i> Traité
                                                        </button>
                                                    @else
                                                        <button class="btn btn-info" disabled style="opacity:.5;">
                                                            <i class="fas fa-pencil-alt"></i> Traité
                                                        </button>
                                                    @endif

                                                    <!-- Nouveau bouton Supprimer -->
                                                    <form method="POST"
                                                        action="{{ route('admin.recours.delete', $recour->id) }}"
                                                        style="display:inline;"
                                                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce recours ?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="mt-3 d-flex justify-content-end px-3">
                                    {{-- {!! $getRecord->appends(Illuminate\Support\Facades\Request::except('page'))->links() !!} --}}
                                </div>
                            </div>
                        </div>

                        <!-- Modal pour édition des notes -->
                        <div class="modal fade" id="markModal" tabindex="-1" role="dialog"
                            aria-labelledby="markModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content" id="mark-modal-content">
                                    <!-- Contenu chargé via AJAX ici -->
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
        }

        .btn-info,
        .btn-danger,
        .btn-success,
        .btn-primary {
            font-weight: 500;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .badge {
            font-size: 0.9rem;
            padding: 0.4em 0.75em;
        }
    </style>
@endsection


@section('script')
    <script>
        $(document).on('click', '.open-mark-modal', function() {
            let url = $(this).data('url');
            $('#mark-modal-content').html(
                '<div class="text-center p-5"><i class="fa fa-spin fa-spinner"></i></div>');

            // Affiche le modal
            $('#markModal').modal('show');

            // Charge la vue partielle via AJAX
            $.get(url, function(data) {
                $('#mark-modal-content').html(data);
            }).fail(function() {
                $('#mark-modal-content').html(
                    '<div class="alert alert-danger">Erreur de chargement du formulaire.</div>');
            });
        });
    </script>
@endsection
