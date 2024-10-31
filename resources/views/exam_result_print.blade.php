<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats Académiques</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --main-bg-color: #f9f9f9;
            --container-bg-color: #fff;
            --header-bg-color: #f4f4f4;
            --button-bg-color: #27ae60;
            --button-hover-bg-color: #219653;
            --red: red;
            --green: green;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: var(--main-bg-color);
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background-color: var(--container-bg-color);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            page-break-inside: avoid;
        }

        h1,
        h2,
        h3 {
            margin: 0;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 0.9em;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: var(--header-bg-color);
        }

        .header {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-bottom: 20px;
        }

        .header button {
            background-color: var(--button-bg-color);
            color: white;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .header button:hover {
            background-color: var(--button-hover-bg-color);
        }

        .note-rouge {
            color: var(--red);
        }

        .note-verte {
            color: var(--green);
        }

        .school-logo {
            width: 100px;
            height: 100px;
            border-radius: 10px;
            object-fit: cover;
        }

        .title-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
            text-align: center;
        }

        .table-container {
            width: 100%;
            overflow-x: auto;
        }


        .summary-table {
            width: 100%;
            border: 1px solid #ddd;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .summary-table th,
        .summary-table td {
            padding: 8px;
            text-align: center;
            background-color: var(--header-bg-color);
            min-width: 100px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            body {
                font-size: 0.9em;
            }

            .school-logo {
                width: 80px;
                height: 80px;
            }
        }

        @media (max-width: 480px) {
            body {
                font-size: 0.8em;
            }

            .header button {
                font-size: 12px;
            }

            .table-container {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                /* Améliore le défilement sur mobile */
            }

        }

        /* Print styling */
        @media print {

            .header,
            .button {
                display: none;
            }


            .container {
                max-width: 100%;
                padding: 0;
                box-shadow: none;
            }

            .table-container {
                overflow: visible !important;
            }


            /* Forcing print to multiple pages */
            table,
            .summary-table {
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
        }
    </style>



</head>

<body>
    <div class="container">
        <!-- Header with print button -->
        <header class="header">
            <button onclick="window.print()"><i class="fa-solid fa-file-pdf"></i> Imprimer</button>
        </header>

        <!-- Title section with logo and school details -->
        <div class="title-section">
            <img src="{{ $getSetting->getLogo() }}" alt="Logo de l'établissement" class="school-logo">
            <h2>INSTITUT SUPERIEUR D'INFORMATIQUE PROGRAMMATION ET ANALYSE</h2>
            <h3>SECRÉTARIAT GÉNÉRAL ACADÉMIQUE</h3>
            <h3>SCIENCES INFORMATIQUES</h3>
            <h3>DEPT/OPTION: GENIE LOGICIEL</h3>
            <h3>{{ $getClass->class_name }}, ANNÉE ACADÉMIQUE {{ now()->year }}</h3>
        </div>

        <p><strong>Étudiant : {{ $getStudent->name }} {{ $getStudent->last_name }}</strong></p>

        <!-- Academic results table -->
        <table>
            <thead>
                <tr>
                    <th>UE</th>
                    <th>Crédit</th>
                    <th>Note / 20</th>
                    <th>Décision</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $Grandtotals_score = 0;
                    $full_marks = 0;
                    $credits_obtenus = 0;
                    $echefsLourd = 0;
                    $echefsLeger = 0;
                    $hasFailed = false;
                    $zeroScoreFound = false;
                    $total_scores = 0;
                    $valid_scores_count = 0;
                @endphp

                @foreach ($getExamMark as $exam)
                    @php
                        $total_score = $exam['total_score'] ?? 0;
                        $Grandtotals_score += $exam['totals_score'] ?? 0;
                        $full_marks += $exam['ponde'];

                        if ($total_score === 0) {
                            $zeroScoreFound = true;
                        }

                        if ($total_score < 10) {
                            $echefsLourd++;
                            $hasFailed = true;
                        }

                        if ($total_score >= 10) {
                            $credits_obtenus += $exam['ponde'] ?? 0;
                        }

                        if ($total_score >= 0 && $total_score <= 20) {
                            $total_scores += $total_score;
                            $valid_scores_count++;
                        }
                    @endphp
                    <tr>
                        <td>{{ $exam['subject_name'] }}</td>
                        <td>{{ $exam['ponde'] }}</td>
                        <td
                            class="{{ $total_score === 0 ? 'note-rouge' : ($total_score < 10 ? 'note-rouge' : 'note-verte') }}">
                            {{ $total_score === 0 ? 'ND' : $total_score }}
                        </td>
                        <td class="{{ $total_score < 10 ? 'note-rouge' : 'note-verte' }}">
                            {{ $total_score < 10 ? 'NVL' : 'VAL' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary table for credits and decision -->
        <div class="table-container">
            <table class="summary-table">
                <tr>
                    <th>Total des crédits obtenu / {{ $full_marks }}</th>
                    <th>Moyenne / 20</th>
                    <th>Mention</th>
                    <th>Nombre d'echec(s)</th>
                    <th>Décision</th>
                </tr>
                <tr>
                    <td>{{ $credits_obtenus > 0 ? $credits_obtenus : 'ND' }}</td>
                    <td>
                        @php
                            if (!$zeroScoreFound) {
                                $moyenne = min(round($total_scores / $valid_scores_count, 2), 20);
                                echo $moyenne;
                            } else {
                                echo 'ND';
                            }
                        @endphp
                    </td>
                    <td>
                        @if (!$zeroScoreFound)
                            @php
                                if ($moyenne >= 18) {
                                    echo 'Excellent';
                                } elseif ($moyenne >= 16) {
                                    echo 'Très Bien';
                                } elseif ($moyenne >= 14) {
                                    echo 'Bien';
                                } elseif ($moyenne >= 12) {
                                    echo 'Assez Bien';
                                } elseif ($moyenne >= 10) {
                                    echo 'Passable';
                                } elseif ($moyenne >= 8) {
                                    echo 'Insuffisant';
                                } else {
                                    echo 'Insatisfaisant';
                                }
                            @endphp
                        @else
                            ND
                        @endif
                    </td>
                    <td>{{ $echefsLourd }}</td>
                    <td>
                        {{ !$zeroScoreFound && $credits_obtenus >= 36 && $echefsLourd < 1 && $echefsLeger < 4 ? 'Admis' : 'Ajourné' }}
                    </td>
                </tr>
            </table>
        </div>

        <footer class="footer">
            <div class="certification">
                <p><strong>Certification :</strong> Je certifie que les informations ci-dessus sont exactes.</p>
                <p>Fait à {{ now()->format('d/m/Y') }}</p>
                <p>Signature : ______________________</p>
            </div>
        </footer>
    </div>
</body>

</html>
