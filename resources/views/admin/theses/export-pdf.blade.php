<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .title-section {
            text-align: center;
            margin-bottom: 25px;
        }

        .school-logo {
            height: 80px;
            margin-bottom: 10px;
        }

        .class-header {
            background: #f8f9fa;
            padding: 8px;
            margin: 20px 0;
            border-left: 4px solid #007bff;
        }

        .type-section {
            margin-left: 20px;
        }

        .type-title {
            color: #2c3e50;
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background: #007bff;
            color: white;
            padding: 8px;
        }

        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
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

        <h2 style="margin-bottom: 3px; font-weight: bold; letter-spacing:2px;">RÉPUBLIQUE DÉMOCRATIQUE DU CONGO</h2>
        <h2 style="margin-bottom: 5px; font-size: 1.4em;">{{ $getSetting->school_name }}</h2>
        <h2 style="margin: 0 0 12px 0; color: #0074c7; font-size: 2em;">I.S.I.P.A</h2>
        <hr style="border-top: 1px solid #929292; margin: 15px 0; width: 100%;">
        <h3 style="margin-bottom: 1px; font-weight: 500;">SECRÉTARIAT GÉNÉRAL ACADÉMIQUE</h3>
        <h3 style="margin-bottom: 18px;">
            ANNÉE ACADÉMIQUE <span style="color:#101c4d; font-weight:700;">{{ $academicYear->name }}</span>
        </h3>
    </div>

    @foreach ($groupedSubmissions as $classData)
        <div class="class-header">
            <h3>{{ $classData['class']->name }} {{ $classData['class']->opt }}</h3>
        </div>

        @if ($classData['memoires']->isNotEmpty())
            <div class="type-section">
                <h4 class="type-title">Mémoires</h4>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Étudiant</th>
                            <th>Sujet</th>
                            <th>Encadrant</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($classData['memoires'] as $index => $sub)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $sub->student->name }} {{ $sub->student->last_name }}</td>
                                <td>{{ $sub->subject }}</td>
                                <td>{{ $sub->directeur->name ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @if ($classData['projets']->isNotEmpty())
            <div class="type-section">
                <h4 class="type-title">Projets</h4>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Étudiant</th>
                            <th>Nom du projet</th>
                            <th>Encadreur</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($classData['projets'] as $index => $sub)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $sub->student->name }} {{ $sub->student->last_name }}</td>
                                <td>{{ $sub->project_name }}</td>
                                <td>{{ $sub->encadreur->name ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @endforeach

</body>

</html>
