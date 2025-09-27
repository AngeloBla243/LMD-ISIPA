@extends('layouts.app')

@section('style')
    <style>
        .styled-table {
            border-collapse: collapse;
            margin: 25px 0;
            min-width: 400px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
            border-radius: 8px;
            overflow: hidden;
        }

        .styled-table thead tr {
            background-color: #009879;
            color: #ffffff;
            text-align: left;
        }

        .styled-table th,
        .styled-table td {
            padding: 12px 15px;
        }

        .styled-table tbody tr {
            border-bottom: 1px solid #dddddd;
        }

        .styled-table tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }

        .styled-table tbody tr:last-of-type {
            border-bottom: 2px solid #009879;
        }

        .styled-table tbody tr:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Emploi du Temps des Classes (Département)</h1>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="container-fluid">
                <div class="row">

                    <div class="col-md-12">

                        @include('_message')

                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Filtrage Emploi du Temps</h3>
                            </div>

                            <form method="GET" action="{{ url()->current() }}" class="mb-3">
                                <div class="card-body">
                                    <div class="row g-3 align-items-end">

                                        <div class="col-md-3">
                                            <label for="academic_year_id" class="form-label">Année Académique</label>
                                            <select name="academic_year_id" id="academicYear" class="form-control" required>
                                                <option value="">-- Sélectionner une année --</option>
                                                @foreach ($academicYears as $year)
                                                    <option value="{{ $year->id }}"
                                                        {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>
                                                        {{ $year->name }}
                                                        ({{ \Carbon\Carbon::parse($year->start_date)->format('Y') }}
                                                        - {{ \Carbon\Carbon::parse($year->end_date)->format('Y') }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label for="class_id" class="form-label">Classe</label>
                                            <select name="class_id" id="classSelect" class="form-control getClass" required>
                                                <option value="">-- Sélectionner une classe --</option>
                                                @foreach ($getClass as $class)
                                                    <option value="{{ $class->id }}"
                                                        {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                                        {{ $class->name }} {{ $class->opt }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label for="subject_id" class="form-label">Matière</label>
                                            <select class="form-control getSubject" name="subject_id" required>
                                                <option value="">-- Sélectionner une matière --</option>
                                                @if (!empty($getSubject))
                                                    @foreach ($getSubject as $subject)
                                                        <option value="{{ $subject->subject_id }}"
                                                            {{ request('subject_id') == $subject->subject_id ? 'selected' : '' }}>
                                                            {{ $subject->subject_name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>

                                        <div class="col-md-3 d-flex gap-2">
                                            <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                                            <a href="{{ url()->current() }}"
                                                class="btn btn-secondary w-100">Réinitialiser</a>
                                        </div>

                                    </div>
                                </div>
                            </form>

                        </div>

                        @if (!empty(request('class_id')) && !empty(request('subject_id')))
                            <form action="{{ route('departement.class_timetable.insert_update') }}" method="POST">
                                @csrf
                                <input type="hidden" name="subject_id" value="{{ request('subject_id') }}">
                                <input type="hidden" name="class_id" value="{{ request('class_id') }}">

                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Emploi du Temps</h3>
                                    </div>

                                    <div class="card-body p-0" style="overflow-x:auto;">
                                        <table class="table styled-table table-bordered table-striped mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Semaine</th>
                                                    <th>Heure Début</th>
                                                    <th>Heure Fin</th>
                                                    <th>Salle</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $i = 1; @endphp
                                                @foreach ($week as $value)
                                                    <tr>
                                                        <th>
                                                            <input type="hidden"
                                                                name="timetable[{{ $i }}][week_id]"
                                                                value="{{ $value['week_id'] }}">
                                                            {{ $value['week_name'] }}
                                                        </th>
                                                        <td>
                                                            <input type="time"
                                                                name="timetable[{{ $i }}][start_time]"
                                                                class="form-control" value="{{ $value['start_time'] }}">
                                                        </td>
                                                        <td>
                                                            <input type="time"
                                                                name="timetable[{{ $i }}][end_time]"
                                                                class="form-control" value="{{ $value['end_time'] }}">
                                                        </td>
                                                        <td>
                                                            <input type="text"
                                                                name="timetable[{{ $i }}][room_number]"
                                                                class="form-control" style="width: 200px;"
                                                                value="{{ $value['room_number'] }}">
                                                        </td>
                                                    </tr>
                                                    @php $i++; @endphp
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="card-footer text-center">
                                        <button type="submit" class="btn btn-primary px-4 py-2">Enregistrer</button>
                                    </div>
                                </div>
                            </form>
                        @endif

                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('script')
    <script>
        document.getElementById('academicYear').addEventListener('change', function() {
            const yearId = this.value;
            const classSelect = document.getElementById('classSelect');

            classSelect.innerHTML = '<option>Chargement...</option>';
            classSelect.disabled = true;

            if (!yearId) {
                classSelect.innerHTML = '<option value="">-- Sélectionnez d\'abord une année --</option>';
                classSelect.disabled = true;
                return;
            }

            fetch("{{ url('departement/class_timetable/get-classes-by-year') }}/" + yearId)
                .then(resp => resp.json())
                .then(result => {
                    if (!result.success) throw new Error('Erreur côté serveur');
                    const data = result.data;
                    classSelect.innerHTML = '<option value="">-- Sélectionnez une classe --</option>';
                    data.forEach(c => {
                        classSelect.innerHTML += `<option value="${c.id}">${c.name}</option>`;
                    });
                    classSelect.disabled = false;
                })
                .catch(error => {
                    classSelect.innerHTML =
                        `<option value="">Erreur lors du chargement (${error.message})</option>`;
                    classSelect.disabled = true;
                    console.error('Erreur fetch classes:', error);
                });
        });

        // Chargement dynamique des matières selon classe
        $('.getClass').change(function() {
            var class_id = $(this).val();
            $.ajax({
                url: "{{ url('departement/class_timetable/get-subject') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    class_id: class_id,
                },
                dataType: "json",
                success: function(response) {
                    $('.getSubject').html(response.html);
                },
            });
        });
    </script>
@endsection
