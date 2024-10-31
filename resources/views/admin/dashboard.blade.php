@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h1 class="m-0">Dashboard</h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- Cartes de statistiques déjà existantes -->
                    <div class="col-lg-3 col-6">
                        <!-- Exemple d'une carte -->
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>${{ number_format($getTotalFees, 2) }}</h3>
                                <p>All Time Received Payment</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-bag"></i>
                            </div>
                            <a href="{{ url('admin/fees_collection/collect_fees_report') }}" class="small-box-footer">
                                More info <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>

                    {{-- <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>${{ number_format($getTotalTodayFees, 2) }}</h3>
                                <p>Today Received Payment</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-bag"></i>
                            </div>
                            <a href="{{ url('admin/fees_collection/collect_fees_report?start_created_date=' . date('Y-m-d') . '&end_created_date=' . date('Y-m-d') . '') }}"
                                class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div> --}}

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $TotalStudent }}</h3>

                                <p>Total Student</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add"></i>
                            </div>
                            <a href="{{ url('admin/student/list') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $TotalTeacher }}</h3>

                                <p>Total Teacher</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add"></i>
                            </div>
                            <a href="{{ url('admin/teacher/list') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>


                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3>{{ $TotalParent }}</h3>

                                <p>Total Parent</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add"></i>
                            </div>
                            <a href="{{ url('admin/parent/list') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ $TotalAdmin }}</h3>

                                <p>Total Admin</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add"></i>
                            </div>
                            <a href="{{ url('admin/admin/list') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $TotalExam }}</h3>

                                <p>Total Exam</p>
                            </div>
                            <div class="icon">
                                <i class="nav-icon fas fa-table"></i>
                            </div>
                            <a href="{{ url('admin/examinations/exam/list') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $TotalClass }}</h3>

                                <p>Total Class</p>
                            </div>
                            <div class="icon">
                                <i class="nav-icon fas fa-table"></i>
                            </div>
                            <a href="{{ url('admin/class/list') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $TotalSubject }}</h3>

                                <p>Total Subject</p>
                            </div>
                            <div class="icon">
                                <i class="nav-icon fas fa-table"></i>
                            </div>
                            <a href="{{ url('admin/subject/list') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <!-- Autres cartes ici... -->
                </div>

                <!-- Section des graphiques -->
                <div class="row">
                    <!-- Graphique des paiements -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Statistiques de Paiement</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="paymentChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Graphique des utilisateurs (Étudiants, Enseignants, etc.) -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Répartition des Utilisateurs</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="userChart" width="400" height="200"></canvas>
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
        /* Stylisation de la boîte de contenu */
        .small-box {
            border-radius: 0.25rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .small-box:hover {
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
            transform: translateY(-5px);
        }

        .small-box .icon i {
            font-size: 50px;
        }

        /* Styles des cartes contenant les graphiques */
        .card {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 0.25rem;
            background-color: #fff;
            padding: 15px;
            margin-bottom: 20px;
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            padding: 10px 15px;
            border-radius: 0.25rem 0.25rem 0 0;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #343a40;
        }

        .card-body {
            padding: 20px;
        }

        /* Graphiques - Ajustement pour la réactivité */
        canvas {
            width: 100% !important;
            height: auto !important;
        }

        /* Mise en page de la section des graphiques */
        .content-header h1 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }

        .content-wrapper {
            margin: 20px;
        }

        /* Responsivité pour les petits écrans */
        @media (max-width: 768px) {
            .content-header h1 {
                font-size: 1.5rem;
            }

            .small-box .icon i {
                font-size: 40px;
            }
        }

        /* Amélioration des petits boutons de navigation */
        .small-box-footer {
            color: #fff !important;
            background-color: rgba(0, 0, 0, 0.1);
            padding: 10px;
            border-radius: 0 0 0.25rem 0.25rem;
            transition: background-color 0.3s ease;
        }

        .small-box-footer:hover {
            background-color: rgba(0, 0, 0, 0.2);
            text-decoration: none;
        }
    </style>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Graphique des paiements
        var ctx = document.getElementById('paymentChart').getContext('2d');
        var paymentChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Paiements Totaux', 'Paiements Aujourd\'hui'],
                datasets: [{
                    label: 'Montant en $',
                    data: [{{ $getTotalFees }}, {{ $getTotalTodayFees }}],
                    backgroundColor: ['#17a2b8', '#00c0ef'],
                    borderColor: ['#17a2b8', '#00c0ef'],
                    borderWidth: 1
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

        // Graphique des utilisateurs
        var ctx2 = document.getElementById('userChart').getContext('2d');
        var userChart = new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['Étudiants', 'Enseignants', 'Parents', 'Admins', 'Examens', 'Classes', 'Matières'],
                datasets: [{
                    label: 'Nombre Total',
                    data: [{{ $TotalStudent }}, {{ $TotalTeacher }}, {{ $TotalParent }},
                        {{ $TotalAdmin }}, {{ $TotalExam }}, {{ $TotalClass }},
                        {{ $TotalSubject }}
                    ],
                    backgroundColor: ['#28a745', '#ffc107', '#007bff', '#dc3545', '#fd7e14', '#6610f2',
                        '#20c997'
                    ],
                    borderColor: ['#28a745', '#ffc107', '#007bff', '#dc3545', '#fd7e14', '#6610f2',
                        '#20c997'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
            }
        });
    </script>
@endsection
