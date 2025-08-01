@extends('layouts.app')

@section('style')
    <style>
        /* Conteneur général */
        .content-wrapper {
            padding: 2.5rem 0;
            min-height: 100vh;
            background: #f8fbff;
            font-family: 'Montserrat', sans-serif;
            color: #222;
        }

        h2 {
            text-align: center;
            color: #2176bd;
            font-weight: 700;
            margin-bottom: 2rem;
            font-size: 2rem;
        }

        /* Cards */
        .card {
            border-radius: 18px;
            box-shadow: 0 8px 24px rgba(14, 74, 107, 0.07);
            border: none;
            margin-bottom: 2rem;
            background: #fff;
        }

        .card-body h4 {
            color: #2176bd;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        ul {
            list-style-type: disc;
            padding-left: 1.5rem;
            font-size: 1rem;
            margin-bottom: 0;
        }

        ul li {
            margin-bottom: 0.6rem;
        }

        /* Responsive container for charts */
        .chart-container {
            position: relative;
            width: 100%;
            max-width: 580px;
            margin: 0 auto;
        }

        /* Table styling */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            font-size: 1rem;
            color: #333;
        }

        .table thead tr {
            background-color: #2176bd;
            color: white;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.07em;
        }

        .table th,
        .table td {
            padding: 12px 18px;
            border: 1px solid #dce7f1;
            vertical-align: middle;
            text-align: center;
            white-space: nowrap;
        }

        .table tbody tr:hover {
            background-color: #e6f1fb;
            cursor: pointer;
        }

        /* Responsive fixes */
        @media (max-width: 768px) {
            h2 {
                font-size: 1.6rem;
                padding: 0 1rem;
            }

            .card-body h4 {
                font-size: 1.25rem;
            }

            ul li {
                font-size: 0.95rem;
            }

            .chart-container {
                max-width: 100%;
                padding: 0 1rem;
            }

            .table thead tr {
                font-size: 0.75rem;
            }

            .table th,
            .table td {
                padding: 8px 10px;
                font-size: 0.85rem;
                white-space: normal;
                /* allow wrapping on small screens */
            }
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="container">
            <h2>Résultats pour l'examen : <span class="text-primary">{{ $exam->title }}</span></h2>

            <div class="card shadow mb-4">
                <div class="card-body">
                    <h4>Statistiques</h4>
                    <ul>
                        <li>Score maximal possible : <b>{{ $maxScore }}</b></li>
                        <li>Note minimale pour réussir : <b>{{ $minScore }}</b></li>
                        <li>Moyenne des scores : <b>{{ round($average, 2) }}</b></li>
                        <li>Note maximale obtenue : <b>{{ $max }}</b></li>
                        <li>Note minimale obtenue : <b>{{ $min }}</b></li>
                        <li>Taux de réussite : <b>{{ $successRate }} %</b></li>
                        <li>Taux d'échec : <b>{{ $failRate }} %</b></li>
                    </ul>
                    <div class="chart-container mt-4">
                        <canvas id="resultPie"></canvas>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-body">
                    <h4>Questions difficiles (taux d'échec élevé)</h4>
                    <div class="chart-container mt-3">
                        <canvas id="questionFailChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-body">
                    <h4>Résultats des étudiants</h4>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Étudiant</th>
                                    <th>Note</th>
                                    <th>Soumis le</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($results as $result)
                                    <tr>
                                        <td>{{ $result->name }}</td>
                                        <td>{{ $result->score }} / {{ $maxScore }}</td>
                                        <td>{{ \Carbon\Carbon::parse($result->submitted_at)->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Pie chart : taux de réussite/échec
        const ctxPie = document.getElementById('resultPie').getContext('2d');
        new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: ['Réussite', 'Échec'],
                datasets: [{
                    data: [{{ $successRate }}, {{ $failRate }}],
                    backgroundColor: ['#28a745', '#dc3545'],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 1.8,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            boxWidth: 20
                        }
                    }
                }
            }
        });

        // Bar chart : taux d'échec par question
        const ctxBar = document.getElementById('questionFailChart').getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: {!! json_encode(collect($questionStats)->pluck('text')) !!},
                datasets: [{
                    label: 'Taux d\'échec (%)',
                    data: {!! json_encode(collect($questionStats)->pluck('failRate')) !!},
                    backgroundColor: '#ffc107'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 3,
                indexAxis: 'y',
                scales: {
                    x: {
                        beginAtZero: true,
                        max: 100
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
@endsection
