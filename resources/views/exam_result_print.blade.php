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
            --red: #e74c3c;
            --green: #27ae60;
            --yellow: #f1c40f;
            --ue-grey: #ededed;
        }

        body {
            /* font-family: Arial, sans-serif; */
            font-family: 'Roboto', Helvetica, Arial, sans-serif;

            margin: 25px;
            background-color: var(--main-bg-color);
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background-color: var(--container-bg-color);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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
            font-size: 0.95em;
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

        .school-logo {
            width: 100px;
            height: 100px;
            border-radius: 10px;
            object-fit: cover;
        }

        .badge {
            display: inline-block;
            padding: 0.25em 0.5em;
            font-size: 0.9em;
            font-weight: 600;
            color: #fff;
            border-radius: 5px;
        }

        .badge-adm {
            background-color: var(--green);
        }

        .badge-comp {
            background-color: var(--yellow);
            color: #333;
        }

        .badge-def {
            background-color: var(--red);
        }

        .ue-row {
            background: var(--ue-grey);
            color: #222;
            font-weight: bold;
        }

        .ec-row {
            background: #fff;
        }

        .ec-indent {
            text-align: left;
            padding-left: 25px;
        }

        .note-rouge {
            color: var(--red);
        }

        .note-verte {
            color: var(--green);
        }

        .ue-composee {
            color: var(--red);
            font-size: 0.90em;
            font-weight: bold;
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

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 0.95em;
            color: #333;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }

        .certification {
            margin-bottom: 10px;
        }

        .animated-message {
            opacity: 0;
            animation: fadeIn 1s forwards;
            margin-bottom: 20px;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }

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
            }
        }

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

            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            table,
            .summary-table {
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            .footer {
                border-top: none;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <header class="header">
            <button onclick="window.print()"><i class="fas fa-print"></i> Imprimer</button>
        </header>
        <div class="title-section" style="text-align:center;padding: 20px 0 30px 0; border-bottom: 2px solid #101c4d;">
            <img src="{{ $getSetting->getLogo() }}" alt="Logo de l'établissement" class="school-logo"
                style="width:90px; margin-bottom: 15px;"><br>
            <h2 style="margin-bottom: 3px; font-weight: bold; letter-spacing:2px;">RÉPUBLIQUE DÉMOCRATIQUE DU CONGO
            </h2>
            {{-- <h2 style="margin-bottom: 5px; font-size: 1.4em;">INSTITUT SUPERIEUR D'INFORMATIQUE <br>PROGRAMMATION ET
                ANALYSE</h2> --}}
            <h2 style="margin-bottom: 5px; font-size: 1.4em;">{{ $setting->school_name ?? '' }}</h2>
            <h2 style="margin: 0 0 12px 0; color: #0074c7; font-size: 2em;">I.S.I.P.A</h2>
            <hr style="border-top: 1px solid #929292; margin: 15px 0; width: 100%;">
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
        <p><strong>Étudiant : {{ $getStudent->name }} {{ $getStudent->last_name }}</strong></p>
        @php
            // Regroupement par UE et calculs
            $uesData = [];
            $subjectsWithoutUe = [];
            $totalCreditsObtenus = 0;
            $totalCreditsPossibles = 0;
            $moyenneGenerale = 0;
            $totalUeValidees = 0;
            $nombreEchecsLourds = 0;
            $nombreEchecsLegers = 0;
            $nbEC = 0;
            foreach ($getExamMark as $examData) {
                $subject = \App\Models\SubjectModel::with('ue')->find($examData['subject_id'] ?? null);
                $ueId = $subject->ue_id ?? null;
                $note = $examData['total_score'];
                $credit = $examData['ponde'] ?? 0;
                if ($ueId && $subject->ue) {
                    if (!isset($uesData[$ueId])) {
                        $uesData[$ueId] = [
                            'ue' => $subject->ue,
                            'subjects' => [],
                            'moyenne' => 0,
                            'credits_obtenus' => 0,
                            'is_compensated' => false,
                        ];
                    }
                    $uesData[$ueId]['subjects'][] = array_merge($examData, [
                        'note_finale' => $note,
                        'subject_obj' => $subject,
                    ]);
                } else {
                    $subjectsWithoutUe[] = array_merge($examData, [
                        'note_finale' => $note,
                        'subject_obj' => $subject,
                    ]);
                }
            }
            foreach ($uesData as $ueId => &$ueData) {
                $totalNotes = 0;
                $totalCoeff = 0;
                $ueCredits = $ueData['ue']->credits;
                foreach ($ueData['subjects'] as $subject) {
                    $coeff = $subject['ponde'] ?? 1;
                    $note = $subject['note_finale'];
                    $totalNotes += $note * $coeff;
                    $totalCoeff += $coeff;
                    $nbEC++;
                    if ($note < 8) {
                        $nombreEchecsLourds++;
                    } elseif ($note < 10) {
                        $nombreEchecsLegers++;
                    }
                }
                $moyenneUe = $totalCoeff > 0 ? $totalNotes / $totalCoeff : 0;
                $ueData['moyenne'] = round($moyenneUe, 2);
                if ($moyenneUe >= 10) {
                    $ueData['credits_obtenus'] = $ueCredits;
                    $totalCreditsObtenus += $ueCredits;
                    $totalUeValidees++;
                } elseif ($moyenneUe >= 8) {
                    $ueData['is_compensated'] = true;
                }
                $totalCreditsPossibles += $ueCredits;
                $moyenneGenerale += $ueData['moyenne'];
            }
            unset($ueData);
            foreach ($subjectsWithoutUe as $subject) {
                $credits = $subject['ponde'];
                $note = $subject['note_finale'];
                $nbEC++;
                if ($note >= 10) {
                    $totalCreditsObtenus += $credits;
                }
                $totalCreditsPossibles += $credits;
                $moyenneGenerale += $note;
                if ($note < 8) {
                    $nombreEchecsLourds++;
                } elseif ($note < 10) {
                    $nombreEchecsLegers++;
                }
            }
            $totalUes = count($uesData) + count($subjectsWithoutUe);
            // $moyenneGenerale = $totalUes > 0 ? round($moyenneGenerale / $totalUes, 2) : 0;
            // Initialisation des variables pour le calcul pondéré
            $totalNotePonderee = 0;
            $totalCreditsPourMoyenne = 0;

            // Calcul pour les UE
            foreach ($uesData as $ueData) {
                $creditsUE = $ueData['ue']->credits;
                $moyenneUE = $ueData['moyenne'];

                $totalNotePonderee += $moyenneUE * $creditsUE;
                $totalCreditsPourMoyenne += $creditsUE;
            }

            // Calcul pour les EC autonomes (considérés comme UE de 1 crédit)
            foreach ($subjectsWithoutUe as $ec) {
                $noteEC = $ec['note_finale'];
                $creditsEC = $ec['ponde']; // Utilisation de ponde comme crédits

                $totalNotePonderee += $noteEC * $creditsEC;
                $totalCreditsPourMoyenne += $creditsEC;
            }

            // Calcul final de la moyenne générale
            $moyenneGenerale =
                $totalCreditsPourMoyenne > 0 ? round($totalNotePonderee / $totalCreditsPourMoyenne, 2) : 0;

            //POURCENTAGE
            $pourcentageCredits =
                $totalCreditsPossibles > 0 ? ($totalCreditsObtenus / $totalCreditsPossibles) * 100 : 0;
            $decision = $pourcentageCredits >= 75 ? 'VAL' : 'NVL';
            $mention = 'Insuffisant';
            if ($moyenneGenerale >= 16) {
                $mention = 'Très Bien';
            } elseif ($moyenneGenerale >= 14) {
                $mention = 'Bien';
            } elseif ($moyenneGenerale >= 12) {
                $mention = 'Assez Bien';
            } elseif ($moyenneGenerale >= 10) {
                $mention = 'Passable';
            }
            // Message d'animation
$message = '';
$backgroundColor = '';
$icon = '';
if ($moyenneGenerale >= 18) {
    $message = 'Félicitations pour votre excellent travail !';
    $backgroundColor = '#27ae60';
    $icon = '🥇';
} elseif ($moyenneGenerale >= 16) {
    $message = 'Très bon travail, continuez ainsi !';
    $backgroundColor = '#2ecc71';
    $icon = '🏅';
} elseif ($moyenneGenerale >= 14) {
    $message = 'Bon travail, vous êtes sur la bonne voie !';
    $backgroundColor = '#f1c40f';
    $icon = '👍';
} elseif ($moyenneGenerale >= 12) {
    $message = 'Encouragements à persévérer !';
    $backgroundColor = '#e67e22';
    $icon = '💪';
} elseif ($moyenneGenerale >= 10) {
    $message = 'Vous avez atteint le niveau de passage.';
    $backgroundColor = '#e74c3c';
    $icon = '✔️';
} else {
    $message = 'Reprenez les matières nécessaires pour améliorer vos résultats.';
    $backgroundColor = '#c0392b';
    $icon = '⚠️';
            }
        @endphp
        <div class="animated-message"
            style="background:{{ $backgroundColor }};color:#fff;padding:12px 0;border-radius:6px;margin:15px 0 20px 0;text-align:center;font-weight:bold;transition:background 0.4s;">
            <span style="font-size:1.2em;margin-right:7px;">{!! $icon !!}</span> {!! $message !!}
        </div>
        <table>
            <thead>
                <tr>
                    <th rowspan="2" style="background:#fff;">Code UE</th>
                    <th rowspan="2" style="background:#fff;">Intitulé UE/EC</th>
                    <th colspan="2" style="background:#fff;">Crédit</th>
                    <th colspan="2" style="background:#fff;">Note / 20</th>
                    <th rowspan="2" style="background:#fff;">Décision</th>
                </tr>
                <tr>
                    {{-- <th style="background:#fff;"></th>
                    <th style="background:#fff;"></th> --}}
                    <th>EC</th>
                    <th>UE</th>
                    <th>EC</th>
                    <th>UE</th>
                    {{-- <th style="background:#fff;"></th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($uesData as $ueId => $ueData)
                    <tr class="ue-row">
                        <td><strong>{{ $ueData['ue']->code }}</strong></td>
                        <td>
                            <strong>{{ $ueData['ue']->name }}</strong>
                            @if (count($ueData['subjects']) > 1)
                                <div class="ue-composee"></div>
                            @endif
                        </td>
                        <td></td>
                        <td rowspan="{{ count($ueData['subjects']) + 1 }}">
                            <strong>{{ $ueData['ue']->credits }}</strong>
                        </td>
                        <td></td>
                        <td rowspan="{{ count($ueData['subjects']) + 1 }}">
                            {{-- <strong>{{ $ueData['moyenne'] }}</strong> --}}
                            <strong>{{ round($ueData['moyenne'], 0, PHP_ROUND_HALF_UP) }}</strong>
                        </td>
                        <td rowspan="{{ count($ueData['subjects']) + 1 }}" style="background:#fff;">
                            @if ($ueData['is_compensated'])
                                <span class="badge
                            badge-comp">Compensée</span>
                            @else
                                <span class="badge {{ $ueData['moyenne'] >= 10 ? 'badge-adm' : 'badge-def' }}">
                                    {{ $ueData['moyenne'] >= 10 ? 'VAL' : 'NVL' }}
                                </span>
                            @endif
                        </td>
                    </tr>
                    @foreach ($ueData['subjects'] as $subject)
                        <tr class="ec-row">
                            <td></td>
                            <td class="ec-indent">→ {{ $subject['subject_name'] }}</td>
                            <td>{{ $subject['ponde'] }}</td>
                            {{-- <td></td> --}}
                            <td class="{{ $subject['note_finale'] < 10 ? 'note-rouge' : 'note-verte' }}">
                                {{ $subject['note_finale'] }}
                            </td>

                        </tr>
                    @endforeach
                @endforeach
                @foreach ($subjectsWithoutUe as $subject)
                    <tr class="ue-row">
                        <td>{{ $subject['subject_code'] ?? 'AUTO' }}</td>
                        <td><strong>{{ $subject['subject_name'] }} (UE Autonome)</strong></td>
                        <td style="background:#fff;"></td>
                        <td>{{ $subject['ponde'] }}</td>
                        <td class="{{ $subject['note_finale'] < 10 ? 'note-rouge' : 'note-verte' }}"
                            style="background:#fff;">
                            {{-- {{ $subject['note_finale'] }} --}}
                        </td>
                        <td>{{ $subject['note_finale'] }}</td>
                        <td style="background:#fff;">
                            <span class="badge {{ $subject['note_finale'] >= 10 ? 'badge-adm' : 'badge-def' }}">
                                {{ $subject['note_finale'] >= 10 ? 'VAL' : 'NVL' }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="table-container">
            <table class="summary-table">
                <tr>
                    <th>Total crédits obtenus / {{ $totalCreditsPossibles }}</th>
                    <th>Moyenne / 20</th>
                    <th>Mention</th>
                    <th>Nb EC à reprendre</th>
                    <th>Décision</th>
                </tr>
                <tr>
                    <td>{{ $totalCreditsObtenus }}</td>
                    {{-- <td>{{ $moyenneGenerale }}</td> --}}
                    <td>{{ round($moyenneGenerale, 0, PHP_ROUND_HALF_UP) }}</td>
                    <td>{{ $mention }}</td>
                    <td>{{ $nombreEchecsLourds + $nombreEchecsLegers }}</td>
                    <td>
                        @if ($decision == 'VAL')
                            <span class="badge badge-adm">VAL</span>
                        @else
                            <span class="badge badge-def">NVL</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
        <footer class="footer">
            <div class="certification">
                <p><strong>Certification :</strong> Je certifie que les informations ci-dessus sont exactes.</p>
                {{-- <p>
                    Décision :
                    @if ($decision == 'VAL')
                        <span class="badge badge-adm">Admis</span>
                    @else
                        <span class="badge badge-def">Ajourné</span>
                    @endif
                </p> --}}
                <p style="margin-top: 10px;">Fait à Kinshasa, le {{ now()->format('d/m/Y') }}</p>
            </div>
        </footer>
    </div>
</body>

</html>
