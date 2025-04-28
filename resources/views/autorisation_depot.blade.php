<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Autorisation de dépôt</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            font-size: 15px;
            margin: 40px;
        }

        .title-section {
            text-align: center;
            margin-bottom: 35px;
        }

        .school-logo {
            max-height: 80px;
            margin-bottom: 12px;
        }

        .content-section {
            margin: 30px 0;
        }

        .green {
            color: green;
            font-weight: bold;
        }

        .signature {
            margin-top: 80px;
            text-align: right;
        }

        footer {
            position: fixed;
            left: 0;
            bottom: 20px;
            width: 100%;
            text-align: right;
            font-size: 12px;
            color: #666;
        }

        hr {
            margin: 28px 0;
        }
    </style>
</head>

<body>
    <div class="title-section">
        @php
            $logoPath = $getSetting->getLogoLocalPath();
            $logoBase64 = '';
            if ($logoPath) {
                $type = pathinfo($logoPath, PATHINFO_EXTENSION);
                $data = file_get_contents($logoPath);
                $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }

        @endphp


        @if ($logoBase64)
            <img src="{{ $logoBase64 }}" alt="Logo de l'établissement" class="school-logo">
        @else
            <b>Logo non disponible</b>
        @endif



        <h2>RÉPUBLIQUE DÉMOCRATIQUE DU CONGO</h2>
        <h2>INSTITUT SUPÉRIEUR D'INFORMATIQUE PROGRAMMATION ET ANALYSE</h2>
        <h2>I.S.I.P.A</h2>
        <h3>SECRÉTARIAT GÉNÉRAL ACADÉMIQUE</h3>
        <h3>Autorisation de dépôt</h3>
        <h3>
            Classe : {{ $class ? $class->name : '---' }}{{ $opt ? ' (' . $opt . ')' : '' }}
        </h3>
        <h3>Session : {{ now()->year }}</h3>
    </div>
    <hr>
    <div class="content-section">
        <p>Le soussigné, Directeur Général de l’ISIPA, donne par la présente l’autorisation à :</p>
        <ul>
            <li><strong>Nom de l’étudiant :</strong> {{ $nom }}</li>
            <li><strong>Classe :</strong> {{ $class ? $class->name : '---' }}{{ $opt ? ' (' . $opt . ')' : '' }}</li>
            <li><strong>Taux de plagiat :</strong> <span class="green">{{ $plagiarism_rate }}%</span></li>
        </ul>
        <div style="margin-top: 25px;">
            <strong>Félicitations !</strong> Votre mémoire est autorisé au dépôt physique auprès du secrétariat.
        </div>
    </div>
    <div class="signature" style="margin-top: 80px; text-align: right;">
        Signature du Directeur Général <br>
        (Nom et cachet)
    </div>
    <footer>
        Date : {{ $date }}
    </footer>
</body>

</html>
