@extends('layouts.app')

@section('content')
    <div class="content-wrapper py-4">
        <div class="container">
            <h2>Résultats pour l'examen : {{ $exam->title }}</h2>

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
                    <canvas id="resultPie" width="150" height="70"></canvas>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-body">
                    <h4>Questions difficiles (taux d'échec élevé)</h4>
                    <canvas id="questionFailChart" width="400" height="120"></canvas>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-body">
                    <h4>Résultats des étudiants</h4>
                    <table class="table table-hover">
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
                plugins: {
                    legend: {
                        position: 'bottom'
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
                indexAxis: 'y',
                scales: {
                    x: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    </script>
@endsection
