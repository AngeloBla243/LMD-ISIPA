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

        button.download-btn {
            background-color: #007BFF;
            /* Couleur de base */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
        }

        button.download-btn i {
            font-size: 18px;
            /* Taille de l'icône */
        }



        button.download-btn:hover {
            background-color: #0056b3;
            box-shadow: 0px 8px 12px rgba(0, 0, 0, 0.2);
            transform: translateY(-3px);
        }

        button.download-btn:active {
            transform: translateY(1px);
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h1 class="h3 fw-bold text-primary">
                            <i class="fa-solid fa-file-circle-check me-2"></i>Mes Devoirs Soumis
                        </h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="content pb-4">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">

                        <!-- Recherche (placeholder, à compléter si besoin) -->
                        <div class="card shadow-sm rounded-4 border-0 mb-4">
                            <div class="card-header bg-primary text-white rounded-top-4">
                                <h3 class="card-title mb-0">
                                    <i class="fa-solid fa-magnifying-glass me-2"></i>Rechercher un devoir soumis
                                </h3>
                            </div>
                            <!-- Ajoute ici ton formulaire de recherche si besoin -->
                        </div>

                        @include('_message')

                        <div class="card shadow-sm rounded-4 border-0">
                            <div class="card-header bg-primary text-white rounded-top-4">
                                <h3 class="card-title mb-0">
                                    <i class="fa-solid fa-list-check me-2"></i>Liste des devoirs soumis
                                </h3>
                            </div>
                            <div class="card-body p-0 table-responsive">
                                <table class="table table-hover table-bordered align-middle mb-0">
                                    <thead class="table-primary text-center text-uppercase small">
                                        <tr>
                                            <th>#</th>
                                            <th>Classe</th>
                                            <th>Matière</th>
                                            <th>Date du devoir</th>
                                            <th>Date de remise</th>
                                            <th>Document</th>
                                            <th>Description</th>
                                            <th>Créé le</th>
                                            <th>Document soumis</th>
                                            <th>Description soumise</th>
                                            <th>Date de soumission</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($getRecord as $value)
                                            <tr>
                                                <td class="text-center">{{ $value->id }}</td>
                                                <td style="min-width: 200px;">{{ $value->getHomework->class->name }}
                                                    {{ $value->getHomework->class->opt }}</td>
                                                <td style="min-width: 200px;">
                                                    <i class="fa-solid fa-book text-info me-1"></i>
                                                    {{ $value->getHomework->subject->name }}
                                                </td style="min-width: 200px;">
                                                <td style="min-width: 200px;">
                                                    {{ date('d-m-Y', strtotime($value->getHomework->homework_date)) }}</td>
                                                <td style="min-width: 200px;">
                                                    {{ date('d-m-Y', strtotime($value->getHomework->submission_date)) }}
                                                </td>
                                                <td class="text-center">
                                                    @if (!empty($value->getHomework->getDocument()))
                                                        <a href="{{ $value->getHomework->getDocument() }}"
                                                            class="btn btn-outline-primary btn-sm" download
                                                            title="Télécharger">
                                                            <i class="fas fa-download me-1"></i> Télécharger
                                                        </a>
                                                    @endif
                                                </td>
                                                <td>{!! $value->getHomework->description !!}</td>
                                                <td style="min-width: 200px;">
                                                    {{ date('d-m-Y', strtotime($value->getHomework->created_at)) }}</td>
                                                <td class="text-center" style="min-width: 200px;">
                                                    @if (!empty($value->getDocument()))
                                                        <a href="{{ $value->getDocument() }}"
                                                            class="btn btn-outline-success btn-sm" download
                                                            title="Télécharger">
                                                            <i class="fas fa-download me-1"></i> Télécharger
                                                        </a>
                                                    @endif
                                                </td>
                                                <td>{!! $value->description !!}</td>
                                                <td style="min-width: 200px;">
                                                    {{ date('d-m-Y', strtotime($value->created_at)) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="11" class="text-center text-muted py-4">
                                                    <i class="fa-solid fa-face-frown-open fa-2x mb-2"></i><br>
                                                    Aucun devoir soumis trouvé.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                @if ($getRecord instanceof \Illuminate\Pagination\AbstractPaginator && $getRecord->hasPages())
                                    <div class="mt-3 d-flex justify-content-end px-3">
                                        {!! $getRecord->appends(Request::except('page'))->links() !!}
                                    </div>
                                @endif
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

        .btn-outline-primary,
        .btn-outline-success {
            border-width: 2px;
            font-weight: 500;
        }

        .fa-book {
            font-size: 1.1em;
        }

        .fa-download {
            font-size: 1em;
        }

        .fa-face-frown-open {
            color: #adb5bd;
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

    <script type="text/javascript">
        document.getElementById('downloadBt').addEventListener('click', function() {
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
