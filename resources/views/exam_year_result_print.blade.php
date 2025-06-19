<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relevé Annuel - {{ $student->name }} {{ $student->last_name }}</title>
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
            font-family: 'Roboto', Helvetica, Arial, sans-serif;
            margin: 25px;
            background-color: var(--main-bg-color);
        }

        .container {
            max-width: 950px;
            margin: 0 auto;
            padding: 20px;
            background-color: var(--container-bg-color);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.09);
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

        .semester-name {
            border: 2px solid #888888;
            /* gris foncé */
            padding: 8px 15px;
            font-weight: 700;
            color: #444444;
            /* gris foncé */
            width: 100%;
            /* prend toute la largeur */
            margin-top: 30px;
            margin-bottom: 10px;
            border-radius: 5px;
            font-size: 1.1em;
            text-align: center;
            /* centre le texte */
            background-color: #f0f0f0;
            /* gris clair */
            box-sizing: border-box;
            /* pour inclure padding dans la largeur */
        }



        .header button:hover {
            background-color: var(--button-hover-bg-color);
        }

        .school-logo {
            width: 90px;
            height: 90px;
            border-radius: 10px;
            object-fit: cover;
        }

        .title-section {
            text-align: center;
            padding: 20px 0 30px 0;
            border-bottom: 2px solid #101c4d;
        }

        h2,
        h3 {
            margin: 0;
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

        .note-rouge,
        .note-nvl {
            color: var(--red);
            font-weight: bold;
        }

        .note-verte,
        .note-val {
            color: var(--green);
            font-weight: bold;
        }

        .session2 {
            font-size: 0.85em;
            color: #f39c12;
            font-style: italic;
        }

        .ue-composee {
            color: var(--red);
            font-size: 0.95em;
            font-weight: bold;
        }

        .animated-message {
            opacity: 0;
            animation: fadeIn 1s forwards;
            margin-bottom: 20px;
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

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }

        .annual-summary {
            margin-top: 30px;
            border-top: 2px solid #000;
            padding-top: 15px;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 0.95em;
            color: #333;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }

        @media (max-width: 768px) {
            body {
                font-size: 0.9em;
            }

            .school-logo {
                width: 70px;
                height: 70px;
            }
        }

        @media (max-width: 480px) {
            body {
                font-size: 0.8em;
            }

            .header button {
                font-size: 12px;
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

            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <header class="header">
            <button onclick="window.print()"><i class="fas fa-print"></i> Imprimer</button>
        </header>
        <div class="title-section">
            <img src="{{ $setting->getLogo() }}" alt="Logo de l'établissement" class="school-logo"><br>
            <h2 style="margin-bottom: 3px; font-weight: bold; letter-spacing:2px;">RÉPUBLIQUE DÉMOCRATIQUE DU CONGO</h2>
            <h2 style="margin-bottom: 5px; font-size: 1.4em;">{{ $setting->school_name ?? '' }}</h2>
            <h2 style="margin: 0 0 12px 0; color: #0074c7; font-size: 2em;">I.S.I.P.A</h2>
            <hr style="border-top: 1px solid #929292; margin: 15px 0; width: 100%;">
            <h3 style="margin-bottom: 1px; font-weight: 500;">SECRÉTARIAT GÉNÉRAL ACADÉMIQUE</h3>
            <h3 style="margin-bottom: 2px;">{{ $class->name ?? 'Classe inconnue' }}
                {{ $class->opt ?? 'Classe inconnue' }}</h3>
            <h3 style="margin-bottom: 18px;">
                ANNÉE ACADÉMIQUE <span style="color:#101c4d; font-weight:700;">{{ $academicYear }}</span>
            </h3>
        </div>

        <div style="margin-top: 18px; text-align: center; font-family: Arial, sans-serif; color: #333;">
            <p style="margin: 4px 0; font-size: 14px;">
                <strong>Étudiant :</strong> {{ $student->name }} {{ $student->last_name }}
                {{-- <img src="{{ asset($student->profile_pic) }}" alt="Photo de profil de l'étudiant" class="profile-pic"
                    style="width:100px; height:auto; border-radius:50%; margin-top:10px;" /> --}}
            </p>
            <p style="margin: 4px 0; font-size: 14px;">
                <strong>Département :</strong> {{ $student->departement }}
            </p>
            <p style="margin: 4px 0; font-size: 14px;">
                <strong>Matricule :</strong> {{ $student->admission_number ?? 'N/A' }}
            </p>
        </div>


        {{-- Message d'encouragement --}}

        @php
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



        @php
            function getMention($note)
            {
                if ($note >= 18) {
                    return 'Excellent';
                }
                if ($note >= 16) {
                    return 'Très Bien';
                }
                if ($note >= 14) {
                    return 'Bien';
                }
                if ($note >= 12) {
                    return 'Assez Bien';
                }
                if ($note >= 10) {
                    return 'Passable';
                }
                if ($note >= 8) {
                    return 'Insuffisant';
                }
                return 'Insatisfaisant';
            }

            // Calcul de la mention annuelle
            $mentionAnnuelle = getMention($moyenneGenerale);
        @endphp


        @foreach ($semesters as $semesterName => $semester)
            @php
                // Calcul du nombre d'échecs (EC ou UE avec note < 10)
$nombreEchecs = 0;
foreach ($semester['ues'] as $ue) {
    if ($ue['moyenne'] < 10) {
        $nombreEchecs++;
    }
    foreach ($ue['ecs'] as $ec) {
        if ($ec['score'] < 10) {
            $nombreEchecs++;
        }
    }
}
foreach ($semester['ecsAutonomes'] as $ec) {
    if ($ec['score'] < 10) {
        $nombreEchecs++;
    }
}
$mentionSemestre = getMention($semester['moyenne_semestre']);
            @endphp

            <h3 class="semester-name">
                {{ $semesterName }}
            </h3>
            <div class="table-container">
                <table class="summary-table">
                    <thead>
                        <tr>
                            <th rowspan="2" style="background:#fff;">Code UE</th>
                            <th rowspan="2" style="background:#fff;">Intitulé UE/EC</th>
                            <th colspan="2" style="background:#fff;">Crédit</th>
                            <th colspan="2" style="background:#fff;">Note / 20</th>
                            <th rowspan="2" style="background:#fff;">Décision</th>
                        </tr>
                        <tr>
                            <th>EC</th>
                            <th>UE</th>
                            <th>EC</th>
                            <th>UE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($semester['ues'] as $ue)
                            <tr class="ue-row">
                                <td><strong>{{ $ue['ue']->code }}</strong></td>
                                <td>
                                    <strong>{{ $ue['ue']->name }}</strong>
                                    @if (count($ue['ecs']) > 1)
                                        <div class="ue-composee" style="color:#e74c3c; font-weight:bold;">UE composée
                                        </div>
                                    @endif
                                </td>
                                <td></td>
                                <td rowspan="{{ count($ue['ecs']) + 1 }}"><strong>{{ $ue['ue']->credits }}</strong>
                                </td>
                                <td></td>
                                <td rowspan="{{ count($ue['ecs']) + 1 }}">
                                    <strong>{{ number_format($ue['moyenne'], 0, PHP_ROUND_HALF_UP) }}</strong>
                                </td>
                                <td rowspan="{{ count($ue['ecs']) + 1 }}"
                                    class="{{ $ue['moyenne'] >= 10 ? 'note-val' : 'note-nvl' }}">
                                    {{ $ue['moyenne'] >= 10 ? 'VAL' : 'NVL' }}
                                </td>
                            </tr>
                            @foreach ($ue['ecs'] as $ec)
                                <tr class="ec-row">
                                    <td></td>
                                    <td class="ec-indent" style="text-align:left; padding-left: 25px;">→
                                        {{ $ec['subject']->name }}</td>
                                    <td>{{ $ec['ponde'] }}</td>
                                    <td class="{{ $ec['score'] >= 10 ? 'note-val' : 'note-nvl' }}">
                                        {{ $ec['score'] }}
                                        @if ($ec['is_session2'])
                                            <span class="session2"
                                                style="font-size:0.85em; color:#f39c12; font-style:italic;">(2e
                                                session)</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach

                        @foreach ($semester['ecsAutonomes'] as $ec)
                            <tr class="ue-row">
                                <td>{{ $ec['subject']->code }}</td>
                                <td><strong>{{ $ec['subject']->name }} (EC autonome)</strong></td>
                                <td>{{ $ec['ponde'] }}</td>
                                <td>{{ $ec['ponde'] }}</td>
                                <td class="{{ $ec['score'] >= 10 ? 'note-val' : 'note-nvl' }}">
                                    {{ $ec['score'] }}
                                    @if ($ec['is_session2'])
                                        <span class="session2"
                                            style="font-size:0.85em; color:#f39c12; font-style:italic;">(2e
                                            session)</span>
                                    @endif
                                </td>
                                <td>{{ $ec['score'] }}</td>
                                <td class="{{ $ec['score'] >= 10 ? 'note-val' : 'note-nvl' }}">
                                    {{ $ec['score'] >= 10 ? 'VAL' : 'NVL' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="decision-box table-container" style="margin-bottom:30px;">
                <table class="summary-table" style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <th style="background: #f0f0f0;">Crédits obtenus / {{ $semester['credits_possibles'] }}</th>
                        <th style="background: #f0f0f0;">Moyenne / 20</th>
                        <th style="background: #f0f0f0;">Mention</th>
                        <th style="background: #f0f0f0;">Nombre d’échecs</th>
                        <th style="background: #f0f0f0;">Décision</th>
                    </tr>
                    <tr>
                        <td>{{ $semester['credits_obtenus'] }}</td>
                        <td>{{ number_format($semester['moyenne_semestre'], 0, PHP_ROUND_HALF_UP) }} </td>
                        <td>{{ $mentionSemestre }}</td>
                        <td>{{ $nombreEchecs }}</td>
                        <td
                            class="{{ $semester['credits_obtenus'] >= $semester['credits_possibles'] * 0.75 ? 'note-val' : 'note-nvl' }}">
                            {{ $semester['credits_obtenus'] >= $semester['credits_possibles'] * 0.75 ? 'VALIDE' : 'NON VALIDE' }}
                        </td>
                    </tr>
                </table>
            </div>
        @endforeach

        <div class="annual-summary table-container" style="margin-top: 40px;">
            <h3 class="semester-name">SYNTHÈSE ANNUELLE</h3>
            <table class="summary-table">
                <tr>
                    <th>Crédits capitalisés / {{ $totalCreditsPossibles }}</th>
                    <th>Moyenne générale / 20</th>
                    <th>Mention</th>
                    <th>Décision finale</th>
                </tr>
                <tr>
                    <td>{{ $totalCreditsObtenus }}</td>
                    <td>{{ round($moyenneGenerale, 0, PHP_ROUND_HALF_UP) }}</td>
                    <td><em>{{ $mentionAnnuelle }}</em></td>
                    <td class="{{ $decision === 'ADMIS EN ANNÉE SUPÉRIEURE' ? 'note-val' : 'note-nvl' }}">
                        {{ $decision }}
                    </td>
                </tr>
            </table>
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
