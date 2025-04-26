<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de la Classe</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            font-size: 11pt;
        }

        .container {
            margin-top: 20px;
        }

        .title-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
            text-align: center;
        }

        .school-logo {
            width: 100px;
            height: 100px;
            border-radius: 10px;
            object-fit: cover;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .table th,
        .table td {
            padding: 4px;
            border: 1px solid #dee2e6;
            text-align: center;
            vertical-align: middle;
            font-size: 9pt;
        }

        .table th {
            background-color: #007bff;
            color: #fff;
            text-transform: uppercase;
        }

        .table tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        .student-name {
            text-align: left;
            font-weight: bold;
        }

        .code-ue {
            font-weight: bold;
        }

        .decision-val {
            color: green;
            font-weight: bold;
        }

        .decision-nvl {
            color: red;
            font-weight: bold;
        }

        .download-button {
            margin-bottom: 20px;
            text-align: center;
        }

        /* Styles d'impression critiques */
        @media print {
            body {
                zoom: 90%;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                background-color: #fff !important;
                size: A4 landscape;
            }

            .table {
                border-collapse: collapse !important;
                width: 100% !important;
                font-size: 9pt !important;
            }

            .table th,
            .table td {
                padding: 4px !important;
                border: 1px solid #000 !important;
            }

            .table th {
                background-color: #007bff !important;
                color: #fff !important;
            }

            .download-button {
                display: none !important;
            }
        }

        /* Style pour les notes */
        .note-success {
            color: green;
        }

        .note-fail {
            color: red;
        }

        .note-nd {
            color: red;
        }

        /* Style pour les colonnes code cours et credit */
        .subject-code {
            font-size: 0.7em;
            font-weight: bold;
        }

        .subject-credit {
            font-size: 0.7em;
        }

        /* Style pour le pied de page */
        .footer {
            margin-top: 30px;
            text-align: left;
            font-size: 9pt;
            display: flex;
            justify-content: space-around;
        }

        .footer div {
            margin-bottom: 5px;
            width: 30%;
        }
    </style>
</head>

<body>
    <div class="container">
        {{-- <div class="header">
            {{-- <h2>Résultats de la Classe</h2>
            <div>Classe: {{ $class->name }} ({{ $opt }})</div>
        <div>Année Académique: {{ $getSetting->annee_academique }}</div>
    </div> --}}

        <div class="title-section">
            <img src="{{ $getSetting->getLogo() }}" alt="Logo de l'établissement" class="school-logo"> <br>
            <h2>RÉPUBLIQUE DÉMOCRATIQUE DU CONGO</h2>
            <h2>INSTITUT SUPERIEUR D'INFORMATIQUE <br>PROGRAMMATION ET ANALYSE</h2>
            <h2>I.S.I.P.A</h2>
            <h3>SECRÉTARIAT GÉNÉRAL ACADÉMIQUE</h3>
            <h3>Résultats de la Classe</h3>
            <h3>Classe: {{ $class->name }} ({{ $opt }})</h3>
            <h3>Session : {{ now()->year }}</h3>
        </div>

        <div class="download-button">
            <a href="#" class="btn btn-primary" onclick="window.print()">
                <i class="fas fa-download"></i> Télécharger en PDF
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Étudiant</th>
                        @foreach ($subjects as $subject)
                            <th>
                                {{ $subject->subject_name }}
                                <br>
                                Code: {{ $subject->subject_code }}
                                <br>
                                Crédit: {{ $subject->ponde }}
                            </th>
                        @endforeach
                        <th>Crédits Obtenus</th>
                        <th>Crédits Possibles</th>
                        <th>Échecs Légers</th>
                        <th>Échecs Graves</th>
                        <th>Décision</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $students = $students->sortBy('name');
                        $index = 1;
                        $totalStudents = count($students);
                        $successCount = 0;
                    @endphp
                    @foreach ($students as $student)
                        @php
                            $totalCreditsObtenus = 0;
                            $totalCreditsPossible = collect($subjects)->sum('ponde');
                            $echecsLegers = 0;
                            $echecsGraves = 0;
                            $decision = null;
                            $hasMissingData = false;
                        @endphp
                        <tr>
                            <td>{{ $index++ }}</td>
                            <td class="student-name">
                                {{ $student->name }}
                                {{ $student->last_name }}
                            </td>
                            @foreach ($subjects as $subject)
                                @php
                                    $result = $student->results->where('subject_id', $subject->subject_id)->first();
                                    $score = $result ? $result->class_work + $result->exam : null;

                                    if ($score === null) {
                                        $hasMissingData = true;
                                        $decision = 'ND';
                                    } elseif ($score < 8) {
                                        $echecsGraves++;
                                    } elseif ($score < 10) {
                                        $echecsLegers++;
                                    }
                                @endphp
                                <td
                                    class="{{ $score !== null ? ($score >= 10 ? 'note-success' : 'note-fail') : 'note-nd' }}">
                                    {{ $score !== null ? $score : 'ND' }}
                                </td>
                            @endforeach

                            @php
                                $totalCreditsObtenus = 0;
                                foreach ($subjects as $subject) {
                                    $result = $student->results->where('subject_id', $subject->subject_id)->first();
                                    $score = $result ? $result->class_work + $result->exam : null;
                                    if ($score >= 10) {
                                        $totalCreditsObtenus += $subject->ponde;
                                    }
                                }

                                if ($hasMissingData) {
                                    $decision = 'ND';
                                } elseif (
                                    $totalCreditsPossible > 0 &&
                                    $totalCreditsObtenus / $totalCreditsPossible >= 0.75
                                ) {
                                    $decision = 'VAL';
                                    $successCount++;
                                } else {
                                    $decision = 'NVL';
                                }
                            @endphp
                            <td>{{ $totalCreditsObtenus }}</td>
                            <td>{{ $totalCreditsPossible }}</td>
                            <td>{{ $echecsLegers }}</td>
                            <td>{{ $echecsGraves }}</td>
                            <td class="decision-{{ strtolower($decision) }}">
                                {{ $decision }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @php
            $successPercentage = $totalStudents > 0 ? round(($successCount / $totalStudents) * 100, 2) : 0;
        @endphp

        <div>
            Taux de réussite de la classe: {{ $successPercentage }}% ({{ $successCount }} étudiants sur
            {{ $totalStudents }})
        </div>
        <div class="footer">
            <div>Fait à Kinshasa, le {{ date('d/m/Y') }}</div>
            <div>Sceau de l'établissement</div>
            <div>Secrétaire Général Académique:</div>
            <div>Chef d'établissement:</div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
