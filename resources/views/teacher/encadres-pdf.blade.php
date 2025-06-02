<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            background: #3498db;
            color: white;
            padding: 10px;
        }

        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>

<body>
    <h1>Liste des étudiants encadrés - {{ now()->format('d/m/Y') }}</h1>

    <table>
        <thead>
            <tr>
                <th>Étudiant</th>
                <th>Classe</th>
                <th>Type</th>
                <th>Titre</th>
                <th>Année</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($submissions as $sub)
                <tr>
                    <td>{{ $sub->student->name }} {{ $sub->student->last_name }}</td>
                    <td>{{ $sub->student->classes->first()->name ?? 'N/A' }}</td>
                    <td>{{ $sub->type == 1 ? 'Mémoire' : 'Projet' }}</td>
                    <td>{{ $sub->type == 1 ? $sub->subject : $sub->project_name }}</td>
                    <td>{{ $sub->academicYear->name ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
