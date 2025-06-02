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

        .decision-val {
            color: #27ae60;
            /* vert */
            font-weight: bold;
        }

        .decision-nvl {
            color: #e74c3c;
            /* rouge */
            font-weight: bold;
        }


        .school-logo {
            width: 100px;
            height: 100px;
            border-radius: 10px;
            object-fit: cover;
        }

        .download-button {
            text-align: right;
            margin-bottom: 18px;
        }

        .download-btn {
            background: linear-gradient(90deg, #27ae60 0%, #219653 100%);
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 9px 22px;
            font-size: 1em;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(39, 174, 96, 0.12);
            transition: background 0.2s, box-shadow 0.2s, transform 0.1s;
            outline: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .download-btn:hover,
        .download-btn:focus {
            background: linear-gradient(90deg, #219653 0%, #27ae60 100%);
            box-shadow: 0 4px 16px rgba(39, 174, 96, 0.15);
            transform: translateY(-2px) scale(1.03);
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.08);
        }

        .table th,
        .table td {
            padding: 6px 4px;
            border: 1px solid #e0e0e0;
            text-align: center;
            vertical-align: middle;
            font-size: 10pt;
        }

        .table th {
            background-color: #ececec;
            color: #222;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .table tbody tr:nth-child(odd) {
            background-color: #f8f8f8;
        }

        .student-name {
            text-align: left;
            font-weight: bold;
        }

        .decision-val {
            color: #219653;
            font-weight: bold;
        }

        .decision-nvl {
            color: #e74c3c;
            font-weight: bold;
        }

        .note-success {
            color: #219653;
        }

        .note-fail {
            color: #e74c3c;
        }

        .note-nd {
            color: #b2bec3;
        }

        .ue-composee {
            color: #e74c3c;
            font-size: 0.9em;
            font-weight: bold;
        }

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
                background-color: #ececec !important;
                color: #222 !important;
            }

            .download-button {
                display: none !important;
            }
        }

        @media (max-width: 600px) {

            .table th,
            .table td {
                font-size: 8pt;
                padding: 3px 2px;
            }

            .container {
                padding: 2px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="title-section">
            <img src="{{ $getSetting->getLogo() }}" alt="Logo de l'établissement" class="school-logo"><br>
            <h2>RÉPUBLIQUE DÉMOCRATIQUE DU CONGO</h2>
            <h2>INSTITUT SUPERIEUR D'INFORMATIQUE <br>PROGRAMMATION ET ANALYSE</h2>
            <h2>I.S.I.P.A</h2>
            <h3>SECRÉTARIAT GÉNÉRAL ACADÉMIQUE</h3>
            <h3>Résultats de la Classe</h3>
            <h3>Classe: {{ $class->name }} ({{ $opt }})</h3>
            <h3>Session : {{ now()->year }}</h3>
        </div>
        <div class="download-button">
            <button class="download-btn" onclick="window.print()" type="button">
                <i class="fas fa-file-arrow-down"></i> Télécharger PDF / Imprimer
            </button>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th rowspan="2">#</th>
                        <th rowspan="2">Étudiant</th>
                        @foreach ($ues as $ue)
                            <th colspan="{{ count($ue['subjects']) }}">
                                {{ $ue['ue_name'] }}
                                <br>
                                <span style="font-size:0.8em;color:#555;">Code: {{ $ue['ue_code'] }} | Crédit UE:
                                    {{ $ue['ue_credits'] }}</span>
                                @if (count($ue['subjects']) > 1)
                                    <div class="ue-composee"></div>
                                @endif
                            </th>
                        @endforeach
                        @foreach ($subjectsWithoutUe as $subject)
                            <th>
                                {{ $subject->subject_name }}
                                <br>
                                <span style="font-size:0.8em;color:#555;">Code: {{ $subject->subject_code }} | Crédit:
                                    {{ $subject->ponde }}</span>
                            </th>
                        @endforeach
                        <th rowspan="2">Moyenne Générale</th>
                        <th rowspan="2">Crédits Obtenus</th>
                        <th rowspan="2">Décision</th>
                    </tr>
                    <tr>
                        @foreach ($ues as $ue)
                            @foreach ($ue['subjects'] as $subject)
                                <th>
                                    <span style="font-size:0.85em;">{{ $subject->subject_name }}</span>
                                    <br>
                                    <span style="font-size:0.8em;">Crédit: {{ $subject->ponde }}</span>
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
                            $moyennesUe = [];
                            $decision = 'NVL';
                        @endphp
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td class="student-name">{{ $student->name }} {{ $student->last_name }}</td>
                            {{-- Notes EC par UE --}}
                            @foreach ($ues as $ue)
                                @php
                                    $notes = [];
                                    $coeffs = [];
                                    foreach ($ue['subjects'] as $subject) {
                                        $result = $student->results->where('subject_id', $subject->subject_id)->first();
                                        $score = $result ? $result->class_work + $result->exam : null;
                                        $notes[] = $score;
                                        $coeffs[] = $subject->ponde;
                                        $totalCreditsPossible += $subject->ponde;
                                        if ($score !== null && $score >= 10) {
                                            $totalCreditsObtenus += $subject->ponde;
                                        }
                                    }
                                    // Moyenne pondérée UE
                                    $moyenneUe = null;
                                    if (array_sum($coeffs) > 0) {
                                        $moyenneUe = 0;
                                        foreach ($notes as $k => $note) {
                                            if ($note !== null) {
                                                $moyenneUe += $note * $coeffs[$k];
                                            }
                                        }
                                        $moyenneUe = $moyenneUe / array_sum($coeffs);
                                        // Ajout au calcul de la moyenne générale pondérée
                                        $creditsUE = $ue['ue_credits'];
                                        $totalNotePonderee += $moyenneUe * $creditsUE;
                                        $totalCreditsPourMoyenne += $creditsUE;
                                    }
                                    $moyennesUe[] = $moyenneUe;
                                @endphp
                                @foreach ($ue['subjects'] as $k => $subject)
                                    @php
                                        $result = $student->results->where('subject_id', $subject->subject_id)->first();
                                        $score = $result ? $result->class_work + $result->exam : null;
                                    @endphp
                                    <td
                                        class="{{ $score !== null ? ($score >= 10 ? 'note-success' : 'note-fail') : 'note-nd' }}">
                                        {{ $score !== null ? $score : 'ND' }}
                                    </td>
                                @endforeach
                            @endforeach
                            {{-- Notes EC sans UE --}}
                            @foreach ($subjectsWithoutUe as $subject)
                                @php
                                    $result = $student->results->where('subject_id', $subject->subject_id)->first();
                                    $score = $result ? $result->class_work + $result->exam : null;
                                @endphp
                                <td
                                    class="{{ $score !== null ? ($score >= 10 ? 'note-success' : 'note-fail') : 'note-nd' }}">
                                    {{ $score !== null ? $score : 'ND' }}
                                </td>
                                @php
                                    $totalCreditsPossible += $subject->ponde;
                                    if ($score !== null && $score >= 10) {
                                        $totalCreditsObtenus += $subject->ponde;
                                    }
                                    if ($score !== null) {
                                        $totalNotePonderee += $score * $subject->ponde;
                                        $totalCreditsPourMoyenne += $subject->ponde;
                                    }
                                @endphp
                            @endforeach
                            {{-- Moyenne générale pondérée et arrondie --}}
                            @php
                                $moyenneGenerale =
                                    $totalCreditsPourMoyenne > 0 ? $totalNotePonderee / $totalCreditsPourMoyenne : 0;
                                $moyenneGeneraleArrondie =
                                    $moyenneGenerale - floor($moyenneGenerale) >= 0.5
                                        ? ceil($moyenneGenerale)
                                        : floor($moyenneGenerale);
                            @endphp
                            <td>{{ $moyenneGeneraleArrondie }}</td>
                            <td>{{ $totalCreditsObtenus }}</td>
                            <td class="decision-{{ strtolower($decision) }}">
                                {{-- @php
                                    if (
                                        $totalCreditsPossible > 0 &&
                                        $totalCreditsObtenus / $totalCreditsPossible >= 0.75
                                    ) {
                                        $decision = 'VAL';
                                        $successCount++;
                                    } else {
                                        $decision = 'NVL';
                                    }
                                @endphp --}}
                                @php
                                    if (
                                        $totalCreditsPossible > 0 &&
                                        $totalCreditsObtenus / $totalCreditsPossible >= 0.75
                                    ) {
                                        $decision = 'VAL';
                                        $successCount++;
                                    } else {
                                        $decision = 'NVL';
                                    }
                                @endphp

                                <span class="decision-{{ strtolower($decision) }}">{{ $decision }}</span>

                                {{-- {{ $decision }} --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @php
            $successPercentage = $totalStudents > 0 ? round(($successCount / $totalStudents) * 100, 2) : 0;
        @endphp
        <div style="margin-bottom:18px;">
            <strong>Taux de réussite de la classe :</strong>
            <span style="color:#219653;">{{ $successPercentage }}%</span>
            ({{ $successCount }} étudiants sur {{ $totalStudents }})
        </div>
        <div class="footer">
            <div>Fait à Kinshasa, le {{ date('d/m/Y') }}</div>
            <div>Sceau de l'établissement</div>
            <div>Secrétaire Général Académique:</div>
            <div>Chef d'établissement:</div>
        </div>
    </div>
</body>

</html>
