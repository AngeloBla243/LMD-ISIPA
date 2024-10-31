@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0">Tableau de Bord</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <!-- Carte pour les Étudiants -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-gradient-primary">
                        <div class="inner">
                            <h3>{{ $TotalStudent }}</h3>
                            <p>Total Étudiants</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="{{ url('teacher/my_student') }}" class="small-box-footer">Plus d'infos <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <!-- Carte pour les Classes -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-gradient-success">
                        <div class="inner">
                            <h3>{{ $TotalClass }}</h3>
                            <p>Total Classes</p>
                        </div>
                        <div class="icon">
                            <i class="nav-icon fas fa-table"></i>
                        </div>
                        <a href="{{ url('teacher/my_class_subject') }}" class="small-box-footer">Plus d'infos <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <!-- Carte pour les Matières -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-gradient-warning">
                        <div class="inner">
                            <h3>{{ $TotalSubject }}</h3>
                            <p>Total Matières</p>
                        </div>
                        <div class="icon">
                            <i class="nav-icon fas fa-book"></i>
                        </div>
                        <a href="{{ url('teacher/my_class_subject') }}" class="small-box-footer">Plus d'infos <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <!-- Carte pour le Tableau d'Affichage -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-gradient-info">
                        <div class="inner">
                            <h3>{{ $TotalNoticeBoard }}</h3>
                            <p>Total Tableau d'Affichage</p>
                        </div>
                        <div class="icon">
                            <i class="nav-icon fas fa-bullhorn"></i>
                        </div>
                        <a href="{{ url('teacher/my_notice_board') }}" class="small-box-footer">Plus d'infos <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Section des Graphiques -->
            <div class="row">
                <!-- Exemple de graphique en barres -->
                <div class="col-lg-6 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Statistiques des Visiteurs</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="visitorsChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Exemple de graphique en ligne -->
                <div class="col-lg-6 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Répartition des Classes et Matières</h3>
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
<style type="text/css">
    .small-box {
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .small-box h3 {
        font-size: 30px;
        font-weight: bold;
    }

    .small-box-footer {
        background-color: rgba(0, 0, 0, 0.1);
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
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
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background-color: #f7f7f7;
        font-weight: bold;
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
