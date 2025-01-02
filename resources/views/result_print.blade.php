<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats Classe</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
            color: #333;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }

        .header-left img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
        }

        .header-center {
            text-align: center;
        }

        .header-center h1 {
            font-size: 24px;
            margin: 0;
            color: #007bff;
        }

        .header-center h2 {
            font-size: 16px;
            margin: 5px 0;
        }

        .header-right img {
            width: 80px;
            height: 80px;
            border-radius: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 12px;
            text-align: center;
            font-size: 14px;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f1f8ff;
        }

        td {
            font-size: 13px;
        }

        button {
            display: block;
            margin: 20px auto;
            padding: 12px 24px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        button:hover {
            background-color: #0056b3;
        }

        @media print {

            /* Masquer le bouton lors de l'impression */
            button {
                display: none;
            }

            /* Forcer les couleurs d'impression */
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            /* Supprimer les marges pour une meilleure utilisation de l'espace */
            body {
                margin: 0;
            }
        }

        @media (max-width: 768px) {

            table,
            th,
            td {
                font-size: 12px;
            }

            button {
                padding: 10px 20px;
                font-size: 12px;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="header-left">
            <img src="{{ $getSetting->getLogo() }}" alt="Logo de l'établissement" class="school-logo"> <br>
        </div>
        <div class="header-center">
            <h1>RÉPUBLIQUE DÉMOCRATIQUE DU CONGO
                INSTITUT SUPERIEUR D'INFORMATIQUE
                PROGRAMMATION ET ANALYSE
            </h1>
            <h1>ISIPA</h1>
            <h2>Grille de déliberation</h2>
            <h2>Classe : {{ $class->name }}</h2>
            <h2>Examen : {{ $exam_id }}</h2>
        </div>
        {{-- <div class="header-right">
            <img src="photo_etudiant.png" alt="Photo Étudiant">
        </div> --}}
    </header>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Étudiant</th>
                @foreach ($subjects as $subject)
                    <th>{{ $subject->name }}</th>
                @endforeach
                <th>Credit</th>
                <th>Échecs</th>
                <th>Moyenne</th>
                <th>Décision</th>
            </tr>
        </thead>

        <tbody>
            @php $index = 1; @endphp
            @foreach ($students as $student)
                <tr>
                    <td>{{ $index++ }}</td>
                    <td>{{ $student->name }} {{ $student->last_name }}</td>
                    @foreach ($subjects as $subject)
                        @php
                            $result = $student->results->firstWhere('subject_id', $subject->id);
                        @endphp
                        <td>
                            @if ($result && isset($result->class_work) && isset($result->exam))
                                {{ $result->class_work + $result->exam }}
                            @else
                                N/A
                            @endif
                        </td>
                    @endforeach
                    <td>{{ $student->total ?? 'N/A' }}</td>
                    <td>{{ $student->light_failures ?? 'N/A' }}</td>
                    <td>{{ $student->percentage ?? 'N/A' }}%</td>
                    <td>{{ $student->jury_decision ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <button onclick="window.print()">Imprimer</button>
</body>

</html>
