<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de la Classe</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', Arial, sans-serif;
            background-color: #f5f6fa;
            font-size: 10pt;
        }

        .container {
            margin-top: 15px;
        }

        .title-section {
            text-align: center;
            margin-bottom: 15px;
        }

        .school-logo {
            width: 80px;
            height: 80px;
            border-radius: 6px;
            object-fit: cover;
            margin-bottom: 5px;
        }

        .download-button {
            text-align: right;
            margin-bottom: 10px;
        }

        .download-btn {
            background: #27ae60;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 6px 14px;
            font-size: 0.85em;
            font-weight: bold;
            cursor: pointer;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            background-color: #fff;
        }

        .table th,
        .table td {
            padding: 2px;
            border: 1px solid #999;
            text-align: center;
            vertical-align: middle;
            font-size: 8.5pt;
        }

        .table th {
            background-color: #ececec;
            font-weight: bold;
        }

        /* Texte vertical compact */
        .vertical-text {
            writing-mode: vertical-rl;
            transform: rotate(180deg);
            white-space: nowrap;
            font-size: 8pt;
            line-height: 1.1;
        }

        .note-success {
            color: #27ae60;
        }

        .note-fail {
            color: #e74c3c;
        }

        .note-nd {
            color: #7f8c8d;
        }

        .decision-val {
            color: #27ae60;
            font-weight: bold;
        }

        .decision-nvl {
            color: #e74c3c;
            font-weight: bold;
        }

        .footer {
            margin-top: 15px;
            text-align: left;
            font-size: 8pt;
            display: flex;
            justify-content: space-between;
        }

        @media print {
            .download-button {
                display: none;
            }

            body {
                zoom: 90%;
                background: #fff !important;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- HEADER -->
        <div class="title-section">
            <img src="{{ $getSetting->getLogo() }}" alt="Logo" class="school-logo">
            <h2>RÉPUBLIQUE DÉMOCRATIQUE DU CONGO</h2>
            <h3>INSTITUT SUPERIEUR D'INFORMATIQUE PROGRAMMATION ET ANALYSE</h3>
            <h2>I.S.I.P.A</h2>
            <h3>SECRÉTARIAT GÉNÉRAL ACADÉMIQUE</h3>
            <h4>Résultats de la Classe - {{ $class->name }} ({{ $opt }})</h4>
            <h5>Session : {{ now()->year }}</h5>
        </div>

        <div class="download-button">
            <button class="download-btn" onclick="window.print()" type="button">
                <i class="fas fa-file-arrow-down"></i> Imprimer / PDF
            </button>
        </div>

        <!-- TABLE -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th rowspan="2">#</th>
                        <th rowspan="2">Étudiant</th>
                        @foreach ($ues as $ue)
                            <th colspan="{{ count($ue['subjects']) }}">
                                <span class="vertical-text">
                                    {{ $ue['ue_name'] }}<br>
                                    [Code: {{ $ue['ue_code'] }}]<br>
                                    Crédit: {{ $ue['ue_credits'] }}
                                </span>
                            </th>
                        @endforeach
                        @foreach ($subjectsWithoutUe as $subject)
                            <th>
                                <span class="vertical-text">
                                    {{ $subject->subject_name }}<br>
                                    [{{ $subject->subject_code }}]<br>
                                    Crédit: {{ $subject->ponde }}
                                </span>
                            </th>
                        @endforeach
                        <th rowspan="2">Moy.</th>
                        <th rowspan="2" class="vertical-text">Crédits</th>
                        <th rowspan="2" class="vertical-text">Décision</th>
                    </tr>
                    <tr>
                        @foreach ($ues as $ue)
                            @foreach ($ue['subjects'] as $subject)
                                <th>
                                    <span class="vertical-text">
                                        {{ $subject->subject_name }}<br>
                                        Crédit: {{ $subject->ponde }}
                                    </span>
                                </th>
                            @endforeach
                        @endforeach
                        @foreach ($subjectsWithoutUe as $subject)
                            <th></th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                        $successCount = 0;
                        $totalStudents = count($students);
                    @endphp
                    @foreach ($students as $student)
                        @php
                            $totalCreditsObtenus = 0;
                            $totalCreditsPossible = 0;
                            $totalNotePonderee = 0;
                            $totalCreditsPourMoyenne = 0;
                            $decision = 'NVL';
                        @endphp
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td style="text-align:left;font-weight:bold;">{{ $student->name }}
                                {{ $student->last_name }}</td>
                            {{-- UE --}}
                            @foreach ($ues as $ue)
                                @foreach ($ue['subjects'] as $subject)
                                    @php
                                        $result = $student->results->where('subject_id', $subject->subject_id)->first();
                                        $score = $result ? $result->class_work + $result->exam : null;
                                        $totalCreditsPossible += $subject->ponde;
                                        if ($score !== null && $score >= 10) {
                                            $totalCreditsObtenus += $subject->ponde;
                                        }
                                        if ($score !== null) {
                                            $totalNotePonderee += $score * $subject->ponde;
                                            $totalCreditsPourMoyenne += $subject->ponde;
                                        }
                                    @endphp
                                    <td
                                        class="{{ $score !== null ? ($score >= 10 ? 'note-success' : 'note-fail') : 'note-nd' }}">
                                        {{ $score !== null ? $score : 'ND' }}
                                    </td>
                                @endforeach
                            @endforeach
                            {{-- Sans UE --}}
                            @foreach ($subjectsWithoutUe as $subject)
                                @php
                                    $result = $student->results->where('subject_id', $subject->subject_id)->first();
                                    $score = $result ? $result->class_work + $result->exam : null;
                                    $totalCreditsPossible += $subject->ponde;
                                    if ($score !== null && $score >= 10) {
                                        $totalCreditsObtenus += $subject->ponde;
                                    }
                                    if ($score !== null) {
                                        $totalNotePonderee += $score * $subject->ponde;
                                        $totalCreditsPourMoyenne += $subject->ponde;
                                    }
                                @endphp
                                <td
                                    class="{{ $score !== null ? ($score >= 10 ? 'note-success' : 'note-fail') : 'note-nd' }}">
                                    {{ $score !== null ? $score : 'ND' }}
                                </td>
                            @endforeach

                            {{-- Moyenne + Crédits + Décision --}}
                            @php
                                $moyenneGenerale =
                                    $totalCreditsPourMoyenne > 0 ? $totalNotePonderee / $totalCreditsPourMoyenne : 0;
                                $moyenneGeneraleArrondie =
                                    $moyenneGenerale - floor($moyenneGenerale) >= 0.5
                                        ? ceil($moyenneGenerale)
                                        : floor($moyenneGenerale);

                                if ($totalCreditsPossible > 0 && $totalCreditsObtenus / $totalCreditsPossible >= 0.75) {
                                    $decision = 'VAL';
                                    $successCount++;
                                } else {
                                    $decision = 'NVL';
                                }
                            @endphp
                            <td>{{ $moyenneGeneraleArrondie }}</td>
                            <td>{{ $totalCreditsObtenus }}</td>
                            <td class="decision-{{ strtolower($decision) }}">{{ $decision }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Taux de réussite --}}
        @php
            $successPercentage = $totalStudents > 0 ? round(($successCount / $totalStudents) * 100, 2) : 0;
        @endphp
        <div>
            <strong>Taux de réussite :</strong>
            <span style="color:#27ae60;">{{ $successPercentage }}%</span>
            ({{ $successCount }} sur {{ $totalStudents }})
        </div>

        <!-- FOOTER -->
        <div class="footer">
            <div>Kinshasa, le {{ date('d/m/Y') }}</div>
            <div>Sceau de l'établissement</div>
            <div>Secrétaire Général Académique</div>
            <div>Chef d'établissement</div>
        </div>
    </div>
</body>

</html>
