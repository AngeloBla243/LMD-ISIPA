@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <div class="row mb-2 align-items-center">
                    <div class="col-sm-6">
                        <h1 class="h3 fw-bold text-primary">Devoirs Soumis</h1>
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
                                    <i class="fa-solid fa-magnifying-glass me-2"></i> Rechercher les devoirs soumis
                                </h3>
                            </div>
                            <form method="get" action="">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label for="first_name" class="form-label fw-semibold">Prénom de
                                                l'étudiant</label>
                                            <input type="text" id="first_name" name="first_name" class="form-control"
                                                placeholder="Prénom" value="{{ Request::get('first_name') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="last_name" class="form-label fw-semibold">Nom de l'étudiant</label>
                                            <input type="text" id="last_name" name="last_name" class="form-control"
                                                placeholder="Nom" value="{{ Request::get('last_name') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="from_date" class="form-label fw-semibold">Date de création
                                                (de)</label>
                                            <input type="date" id="from_date" name="from_created_date"
                                                class="form-control" value="{{ Request::get('from_created_date') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="to_date" class="form-label fw-semibold">Date de création
                                                (à)</label>
                                            <input type="date" id="to_date" name="to_created_date" class="form-control"
                                                value="{{ Request::get('to_created_date') }}">
                                        </div>
                                        <div class="col-md-3 d-flex align-items-end gap-2">
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="fa-solid fa-magnifying-glass me-1"></i> Rechercher
                                            </button>
                                            <a href="{{ url('teacher/homework/homework/submitted/' . $homework_id) }}"
                                                class="btn btn-success w-100">
                                                Réinitialiser
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        @include('_message')

                        <!-- Submitted Homework List -->
                        <div class="card shadow-sm rounded-4 border-0">
                            <div class="card-header bg-primary text-white rounded-top-4">
                                <h3 class="card-title mb-0">
                                    <i class="fa-solid fa-list-ul me-2"></i>Liste des devoirs soumis
                                </h3>
                            </div>
                            <div class="card-body p-0 table-responsive">
                                <table class="table table-hover table-bordered align-middle mb-0 styled-table">
                                    <thead class="table-primary text-center text-uppercase small">
                                        <tr>
                                            <th style="width: 60px;">#</th>
                                            <th>Nom de l'étudiant</th>
                                            <th style="min-width: 140px;">Document</th>
                                            <th>Description</th>
                                            <th style="min-width: 140px;">Date de création</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($getRecord as $value)
                                            <tr>
                                                <td class="text-center">{{ $value->id }}</td>
                                                <td>{{ $value->first_name }} {{ $value->last_name }}</td>
                                                <td class="text-center">
                                                    @if (!empty($value->getDocument()))
                                                        <a href="{{ $value->getDocument() }}" class="btn btn-primary btn-sm"
                                                            download>
                                                            <i class="fas fa-download me-1"></i>Télécharger
                                                        </a>
                                                    @else
                                                        <span class="text-muted">Aucun document</span>
                                                    @endif
                                                </td>
                                                <td>{!! $value->description !!}</td>
                                                <td class="text-center">{{ date('d-m-Y', strtotime($value->created_at)) }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-4">
                                                    <i class="fa-solid fa-folder-open fa-2x mb-2"></i><br>
                                                    Aucun enregistrement trouvé
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="mt-3 d-flex justify-content-end px-3">
                                    {!! $getRecord->appends(Illuminate\Support\Facades\Request::except('page'))->links() !!}
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

        .btn-primary {
            font-weight: 500;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .styled-table tbody tr:hover {
            background-color: #e9f0ff;
            transition: background-color 0.3s ease;
        }
    </style>
@endsection

@section('script')
    .
    <script type="text/javascript">
        document.getElementById('downloadBtn').addEventListener('click', function() {
            const button = this;
            const icon = button.querySelector('i');

            // Simuler le téléchargement (changer l'icône après clic)
            setTimeout(() => {
                icon.classList.remove('fa-download'); // Supprimer l'icône de téléchargement
                icon.classList.add('fa-check-circle'); // Ajouter l'icône de confirmation
                button.innerHTML = '<i class="fas fa-check-circle"></i> Downloaded'; // Changer le texte
                button.style.backgroundColor = '#28a745'; // Changer la couleur du bouton (vert)
            }, 1000); // Simule un temps de téléchargement de 1 seconde
        });
    </script>
@endsection
