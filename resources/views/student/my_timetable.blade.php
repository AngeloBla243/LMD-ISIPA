@extends('layouts.app')
@section('style')
    <style type="text/css">
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

        /* Effet survol (hover) */
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
                <div class="row align-items-center mb-3">
                    <div class="col-sm-6">
                        <h1 class="h3 fw-bold text-primary">Mon Emploi du Temps</h1>
                    </div>
                    <div class="col-sm-6 text-end">
                        @if (!$selectedAcademicYear->is_active)
                            <div class="alert alert-warning d-inline-flex align-items-center gap-2 py-2 px-3 mb-0 rounded shadow-sm"
                                style="font-size: 0.9rem;">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span>Vous consultez une année archivée</span>
                            </div>
                        @endif
                    </div>
                </div>
                {{-- Formulaire de sélection d'année académique (décommenter si besoin) --}}
                {{--
            <form method="GET" action="" class="d-flex justify-content-end mb-4">
                <select name="academic_year_id" class="form-select w-auto border-primary shadow-sm" onchange="this.form.submit()">
                    @foreach ($academicYears as $year)
                        <option value="{{ $year->id }}" {{ $selectedAcademicYear->id == $year->id ? 'selected' : '' }}>
                            {{ $year->name }} @if ($year->is_active) (Active) @endif
                        </option>
                    @endforeach
                </select>
            </form>
            --}}
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                @include('_message')

                <div class="row g-4">
                    @forelse ($getRecord as $value)
                        <div class="col-12">
                            <div class="card shadow-sm rounded-3 border-0">
                                <div class="card-header bg-primary text-white">
                                    <h3 class="card-title mb-0">{{ $value['name'] }}</h3>
                                </div>
                                <div class="card-body p-0 table-responsive">
                                    <table class="table table-striped table-bordered align-middle mb-0">
                                        <thead class="table-primary text-center text-uppercase small">
                                            <tr>
                                                <th>Semaine</th>
                                                <th>Heure début</th>
                                                <th>Heure fin</th>
                                                <th>Numéro de salle</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($value['week'] as $valueW)
                                                <tr>
                                                    <td class="fw-semibold text-center">{{ $valueW['week_name'] }}</td>
                                                    <td class="text-center">
                                                        {{ $valueW['start_time'] ? date('H:i A', strtotime($valueW['start_time'])) : '-' }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $valueW['end_time'] ? date('H:i A', strtotime($valueW['end_time'])) : '-' }}
                                                    </td>
                                                    <td class="text-center">{{ $valueW['room_number'] ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info text-center shadow-sm rounded-3">
                                Aucun emploi du temps disponible pour cette année académique.
                            </div>
                        </div>
                    @endforelse
                </div>

            </div>
        </section>
    </div>

@endsection


@section('script')
    <script type="text/javascript">
        $('.getClass').change(function() {
            var class_id = $(this).val();
            $.ajax({
                url: "{{ url('admin/class_timetable/get_subject') }}",
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
