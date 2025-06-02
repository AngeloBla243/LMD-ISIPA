@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h1 class="h3 fw-bold text-primary">Tableau de Bord</h1>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content pb-5">
            <div class="container-fluid">
                <div class="row g-4">

                    <!-- Small boxes -->
                    <div class="col-lg-3 col-md-6 col-12">
                        <div
                            class="small-box bg-gradient-primary text-white shadow-sm rounded-3 position-relative overflow-hidden">
                            <div class="inner">
                                <h3 class="display-5 fw-bold">{{ $TotalStudent }}</h3>
                                <p class="fs-5">Total Étudiants</p>
                            </div>
                            <div class="icon position-absolute top-0 end-0 opacity-25 fs-1 p-3">
                                <i class="ion ion-person-add"></i>
                            </div>
                            <a href="{{ url('teacher/my_student') }}"
                                class="small-box-footer text-white fw-semibold d-block py-2 px-3 rounded-bottom bg-black bg-opacity-10 text-decoration-none">
                                Plus d'infos <i class="fas fa-arrow-circle-right ms-1"></i>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-12">
                        <div
                            class="small-box bg-gradient-success text-white shadow-sm rounded-3 position-relative overflow-hidden">
                            <div class="inner">
                                <h3 class="display-5 fw-bold">{{ $TotalClass }}</h3>
                                <p class="fs-5">Total Classes</p>
                            </div>
                            <div class="icon position-absolute top-0 end-0 opacity-25 fs-1 p-3">
                                <i class="nav-icon fas fa-table"></i>
                            </div>
                            <a href="{{ url('teacher/my_class_subject') }}"
                                class="small-box-footer text-white fw-semibold d-block py-2 px-3 rounded-bottom bg-black bg-opacity-10 text-decoration-none">
                                Plus d'infos <i class="fas fa-arrow-circle-right ms-1"></i>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-12">
                        <div
                            class="small-box bg-gradient-warning text-white shadow-sm rounded-3 position-relative overflow-hidden">
                            <div class="inner">
                                <h3 class="display-5 fw-bold">{{ $TotalSubject }}</h3>
                                <p class="fs-5">Total Matières</p>
                            </div>
                            <div class="icon position-absolute top-0 end-0 opacity-25 fs-1 p-3">
                                <i class="nav-icon fas fa-book"></i>
                            </div>
                            <a href="{{ url('teacher/my_class_subject') }}"
                                class="small-box-footer text-white fw-semibold d-block py-2 px-3 rounded-bottom bg-black bg-opacity-10 text-decoration-none">
                                Plus d'infos <i class="fas fa-arrow-circle-right ms-1"></i>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-12">
                        <div
                            class="small-box bg-gradient-info text-white shadow-sm rounded-3 position-relative overflow-hidden">
                            <div class="inner">
                                <h3 class="display-5 fw-bold">{{ $TotalNoticeBoard }}</h3>
                                <p class="fs-5">Total Tableau d'Affichage</p>
                            </div>
                            <div class="icon position-absolute top-0 end-0 opacity-25 fs-1 p-3">
                                <i class="nav-icon fas fa-bullhorn"></i>
                            </div>
                            <a href="{{ url('teacher/my_notice_board') }}"
                                class="small-box-footer text-white fw-semibold d-block py-2 px-3 rounded-bottom bg-black bg-opacity-10 text-decoration-none">
                                Plus d'infos <i class="fas fa-arrow-circle-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Graphiques -->
                <div class="row mt-4 g-4">
                    <div class="col-lg-6 col-md-12">
                        <div class="card shadow-sm rounded-4 border-0">
                            <div class="card-header bg-white border-bottom">
                                <h3 class="card-title fw-semibold">Statistiques des Visiteurs</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="visitorsChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-12">
                        <div class="card shadow-sm rounded-4 border-0">
                            <div class="card-header bg-white border-bottom">
                                <h3 class="card-title fw-semibold">Répartition des Classes et Matières</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="classSubjectChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('style')
    <style>
        .small-box {
            border-radius: 1rem;
            overflow: hidden;
            position: relative;
            color: #fff;
        }

        .small-box .inner h3 {
            font-size: 2.5rem;
            font-weight: 700;
        }

        .small-box .inner p {
            font-size: 1.1rem;
            margin-bottom: 0;
        }

        .small-box .icon {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 3rem;
            opacity: 0.15;
        }

        .small-box-footer {
            background-color: rgba(0, 0, 0, 0.15);
            color: #fff !important;
            display: block;
            padding: 0.75rem 1rem;
            text-align: center;
            font-weight: 600;
            border-radius: 0 0 1rem 1rem;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }

        .small-box-footer:hover {
            background-color: rgba(0, 0, 0, 0.25);
            text-decoration: none;
            color: #fff !important;
        }

        .bg-gradient-primary {
            background: linear-gradient(45deg, #007bff, #00c6ff);
        }

        .bg-gradient-success {
            background: linear-gradient(45deg, #28a745, #a0e62e);
        }

        .bg-gradient-warning {
            background: linear-gradient(45deg, #ffc107, #f37b1d);
        }

        .bg-gradient-info {
            background: linear-gradient(45deg, #17a2b8, #00d4ff);
        }

        .card {
            border-radius: 1rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #f7f7f7;
            font-weight: 600;
            border-bottom: 1px solid #dee2e6;
        }
    </style>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Graphique des visiteurs
        var ctx = document.getElementById('visitorsChart').getContext('2d');
        var visitorsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Total Subject', 'Total Class'],
                datasets: [{
                    label: 'Nombre de Visiteurs',
                    data: [{{ $TotalSubject }}, {{ $TotalClass }}],
                    backgroundColor: ['#f39c12', '#00c0ef'],
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Graphique des classes et matières
        var ctx2 = document.getElementById('classSubjectChart').getContext('2d');
        var classSubjectChart = new Chart(ctx2, {
            type: 'line',
            data: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
                datasets: [{
                    label: 'Classes',
                    data: [12, 15, 10, 20, 18, 25],
                    borderColor: '#17a2b8',
                    fill: false
                }, {
                    label: 'Matières',
                    data: [30, 28, 35, 33, 40, 38],
                    borderColor: '#28a745',
                    fill: false
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
