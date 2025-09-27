@extends('layouts.app')

@section('style')
    <style>
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
            color: white;
            text-align: left;
        }

        .styled-table th,
        .styled-table td {
            padding: 12px 15px;
        }

        .styled-table tbody tr {
            border-bottom: 1px solid #ddd;
        }

        .styled-table tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }

        .styled-table tbody tr:last-of-type {
            border-bottom: 2px solid #009879;
        }

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
            background: #ccc !important;
            color: #888 !important;
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <h1 class="h3 fw-bold text-primary">Liste des Recours</h1>
            </div>
        </section>

        <section class="content pb-5">
            <div class="container-fluid">
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
                                    <a href="{{ url('departement/recours/list') }}" class="btn btn-success w-100">
                                        Réinitialiser
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                @include('_message')

                <div class="card shadow-sm rounded-4 border-0">
                    <div class="card-body p-0 table-responsive">
                        <table class="styled-table table table-hover table-bordered align-middle mb-0">
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
                                @forelse ($recours as $recour)
                                    <tr>
                                        <td class="text-center">{{ $recour->id }}</td>
                                        <td style="min-width: 200px;">{{ $recour->student->name }}
                                            {{ $recour->student->last_name }}</td>
                                        <td style="min-width: 200px;">{{ $recour->class->name }} {{ $recour->class->opt }}
                                        </td>
                                        <td style="min-width: 200px;">{{ $recour->subject->name }}</td>
                                        <td style="min-width: 250px;">{{ $recour->objet }}</td>
                                        <td style="min-width: 200px;">{{ $recour->session_year }}</td>
                                        <td class="text-center" style="min-width: 300px;">
                                            <form method="POST"
                                                action="{{ route('departement.recours.toggle_status', $recour->id) }}"
                                                style="display:inline;">
                                                @csrf
                                                <button type="submit"
                                                    class="btn {{ $recour->status ? 'btn-success' : 'btn-danger' }}"
                                                    title="{{ $recour->status ? 'Rejeter' : 'Valider' }}">
                                                    <i class="fas fa-thumbs-{{ $recour->status ? 'up' : 'down' }}"></i>
                                                </button>
                                            </form>
                                            <form method="POST"
                                                action="{{ route('departement.recours.delete', $recour->id) }}"
                                                style="display:inline;"
                                                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce recours ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger"><i
                                                        class="fas fa-trash-alt"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Aucun recours trouvé.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </section>
    </div>
@endsection
