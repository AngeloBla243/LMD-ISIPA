<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Résultats Académiques</title>
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

        .badge {
            transition: transform 0.2s;
            display: inline-block;
            padding: 0.25em 0.5em;
            font-size: 0.9em;
            font-weight: 600;
            color: #fff;
            border-radius: 5px;
        }

        .badge:hover {
            transform: scale(1.1);
        }

        .badge-adm {
            background-color: #27ae60;
        }

        .badge-comp {
            background-color: #f1c40f;
        }

        .badge-def {
            background-color: #e74c3c;
        }

        .badge-aj {
            background-color: #e67e22;
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

            /* Forcer les couleurs d'impression */
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
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
            <button onclick="window.print()"><i class="fas fa-print"></i> Imprimer</button>
        </header>

        <!-- Title section with logo and school details -->
        <div class="title-section" style="text-align:center;padding: 20px 0 30px 0; border-bottom: 2px solid #101c4d;">
            <img src="{{ $getSetting->getLogo() }}" alt="Logo de l'établissement" class="school-logo"
                style="width:90px; margin-bottom: 15px;"><br>

            <h2 style="margin-bottom: 3px; font-weight: bold; letter-spacing:2px;">RÉPUBLIQUE DÉMOCRATIQUE DU CONGO</h2>
            <h2 style="margin-bottom: 5px; font-size: 1.4em;">INSTITUT SUPERIEUR D'INFORMATIQUE <br>PROGRAMMATION ET
                ANALYSE</h2>
            <h2 style="margin: 0 0 12px 0; color: #0074c7; font-size: 2em;">I.S.I.P.A</h2>

            <hr style="border-top: 1px solid #929292; margin: 15px 0; width: 45%;">

            <h3 style="margin-bottom: 1px; font-weight: 500;">SECRÉTARIAT GÉNÉRAL ACADÉMIQUE</h3>
            <h3 style="margin-bottom: 2px;">{{ $getStudent->departement ?? 'Département inconnu' }}</h3>
            <h3 style="margin-bottom: 2px;">Classe : {{ $getClass->class_name ?? 'Inconnue' }}
                {{ $getClass->class_opt ?? 'Inconnue' }}</h3>
            <h3 style="margin-bottom: 18px;">
                ANNÉE ACADÉMIQUE
                <span style="color:#101c4d; font-weight:700;">
                    {{ $getClass->academic_year_name ?? 'N/A' }}
                </span>
            </h3>
            <h2 style="margin-top: 20px;">Examen : {{ $getExam->name ?? 'Non renseigné' }}</h2>
        </div>


        @php
            $zeroScoreFound = false;
            $moyenne = 0;

            // Calcul de la moyenne et vérification des scores nuls
            foreach ($getExamMark as $exam) {
                $total_score = $exam['total_score'] ?? 0;

                if ($total_score === 0) {
                    $zeroScoreFound = true;
                }

                if ($total_score >= 0 && $total_score <= 20) {
                    $moyenne += $total_score;
                }
            }

            // Calcul de la moyenne en prenant en compte uniquement les scores valides
            $moyenne = $zeroScoreFound ? 0 : min(round($moyenne / count($getExamMark), 2), 20);

        @endphp

        <p><strong>Étudiant : {{ $getStudent->name }} {{ $getStudent->last_name }}</strong></p>

        @php
            if (!$zeroScoreFound) {
                $message = '';
                $backgroundColor = '';

                if ($moyenne >= 18) {
                    $message = 'Félicitations pour votre excellent travail !';
                    $backgroundColor = '#27ae60'; // vert foncé
                } elseif ($moyenne >= 16) {
                    $message = 'Très bon travail, continuez ainsi !';
                    $backgroundColor = '#2ecc71'; // vert
                } elseif ($moyenne >= 14) {
                    $message = 'Bon travail, vous êtes sur la bonne voie !';
                    $backgroundColor = '#f1c40f'; // jaune
                } elseif ($moyenne >= 12) {
                    $message = 'Encouragements à persévérer !';
                    $backgroundColor = '#e67e22'; // orange
                } elseif ($moyenne >= 10) {
                    $message = 'Vous avez atteint le niveau de passage.';
                    $backgroundColor = '#e74c3c'; // rouge clair
                } else {
                    $message = 'Reprenez les matières nécessaires pour améliorer vos résultats.';
                    $backgroundColor = '#c0392b'; // rouge foncé
                }

                echo "<div style='background-color: $backgroundColor; color: #fff; padding: 10px; border-radius: 5px; margin-top: 10px; text-align: center; font-weight: bold;'>$message</div>";
            }
        @endphp

        <!-- Academic results table -->
        <table>
            <thead>
                <tr>
                    <th>Code UE</th>
                    <th>UE</th>
                    <th>Crédit UC</th>
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
                        <td>{{ $exam['subject_code'] }}</td>
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
                        @php
                            if ($zeroScoreFound) {
                                echo 'DEF'; // Manque de note
                            } elseif ($credits_obtenus == $full_marks) {
                                echo 'ADM'; // Admis avec capitalisation définitive des crédits
                            } elseif ($credits_obtenus >= 0.75 * $full_marks) {
                                echo 'COMP'; // Admis avec compensation des notes
                            } else {
                                echo 'AJ'; // Ajourné ou non admis
                            }
                        @endphp
                    </td>
                </tr>
            </table>
        </div>

        <footer class="footer">
            <div class="certification">
                <p><strong>Certification :</strong> Je certifie que les informations ci-dessus sont exactes.</p>
                <p>
                    Décision :
                    @php
                        if ($zeroScoreFound) {
                            $decision = 'DEF';
                            $icon = '<i class="fa fa-times-circle" style="color: #e74c3c;"></i>'; // Icône pour défaillant
                            $badgeColor = 'badge-def';
                            $badgeText = 'Défaillant';
                        } elseif ($credits_obtenus == $full_marks) {
                            $decision = 'ADM';
                            $icon = '<i class="fa fa-check-circle" style="color: #27ae60;"></i>'; // Icône pour admis avec capitalisation
                            $badgeColor = 'badge-adm';
                            $badgeText = 'Admis avec Capitalisation';
                        } elseif ($credits_obtenus >= 0.75 * $full_marks) {
                            $decision = 'COMP';
                            $icon = '<i class="fa fa-balance-scale" style="color: #f1c40f;"></i>'; // Icône pour admis avec compensation
                            $badgeColor = 'badge-comp';
                            $badgeText = 'Admis avec Compensation';
                        } else {
                            $decision = 'AJ';
                            $icon = '<i class="fa fa-exclamation-circle" style="color: #e67e22;"></i>'; // Icône pour ajourné
                            $badgeColor = 'badge-aj';
                            $badgeText = 'Ajourné';
                        }
                        echo $icon . " <span class='badge $badgeColor'>$decision - $badgeText</span>";
                    @endphp
                </p>
                <!-- Affichage des crédits accumulés pour le cycle -->
                <p>Crédits accumulés pour le cycle en cours (Licence {{ $getClass->class_name }}) :
                    <b>{{ $credits_obtenus }}/{{ $full_marks }}</b>
                </p>

                <p>Fait à {{ now()->format('d/m/Y') }}</p>

                <!-- Zone pour le saut de l'établissement -->
                <p>Saut de l'établissement : ______________________</p>
            </div>
        </footer>
    </div>
</body>

</html>
