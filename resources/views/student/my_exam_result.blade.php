@extends('layouts.app')
@section('style')
    <style type="text/css">
        .styled-table thead tr {
            background-color: #009879;
            color: #ffffff;
            text-align: left;
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
        <section class="content-header">
            <div class="row mb-3">
                <div class="col-md-12">
                    <form method="GET" action="">
                        <div class="input-group mb-2">
                            {{-- <select name="academic_year_id" class="form-select" style="max-width:240px;"
                                onchange="this.form.submit()">
                                @foreach ($academicYears as $year)
                                    <option value="{{ $year->id }}"
                                        {{ $selectedAcademicYear && $selectedAcademicYear->id == $year->id ? 'selected' : '' }}>
                                        {{ $year->name }}{{ $year->is_active ? ' (Active)' : '' }}
                                    </option>
                                @endforeach
                            </select> --}}
                            @if ($selectedAcademicYear && !$selectedAcademicYear->is_active)
                                <span class="ms-3 align-items-center" style="color: #ffc107;">
                                    <i class="fas fa-exclamation-triangle"></i> Vous n'êtes pas dans l'année active !
                                </span>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            @if (!empty($getRecord))
                @foreach ($getRecord as $value)
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header bg-secondary text-white">
                            <strong>{{ $value['exam_name'] }}</strong>

                            <a class="btn btn-primary btn-sm" style="float: right;" target="_blank"
                                href="{{ url('student/my_exam_result/print?exam_id=' . $value['exam_id'] . '&student_id=' . Auth::user()->id) }}">Print</a>
                        </div>
                        <div class="card-body p-0">
                            @if (!empty($value['subject']))
                                <table class="table styled-table table-bordered table-striped m-0">
                                    <thead>
                                        <tr>
                                            <th>UE</th>
                                            <th>Crédit du Cours</th>
                                            <th>Note/20</th>
                                            <th>Décision</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $Grandtotals_score = 0;
                                            $credits_obtenus = 0;
                                            $full_marks = 0;
                                            $fail_count = 0;
                                        @endphp
                                        @foreach ($value['subject'] as $exam)
                                            @php
                                                $Grandtotals_score += $exam['totals_score'] ?? 0;
                                                $total_score = $exam['total_score'] ?? 0;
                                                $passing_mark = $exam['passing_mark'] ?? 0;
                                                $full_marks += $exam['ponde'] ?? 0;
                                                if ($total_score >= 10) {
                                                    $credits_obtenus += $exam['ponde'] ?? 0;
                                                }
                                            @endphp
                                            <tr>
                                                <td style="width: 200px">{{ $exam['subject_name'] ?? 'N/A' }}</td>
                                                <td>{{ $exam['ponde'] ?? 0 }}</td>
                                                <td>
                                                    @if (($exam['total_score'] ?? 0) == 0)
                                                        <span style="color: gray; font-weight: bold;">ND</span>
                                                    @else
                                                        @if ($exam['total_score'] >= $exam['passing_mark'])
                                                            <span
                                                                style="color: green; font-weight: bold;"><b>{{ $exam['total_score'] }}</b></span>
                                                        @else
                                                            <span
                                                                style="color: red; font-weight: bold;"><b>{{ $exam['total_score'] }}</b></span>
                                                            @php $fail_count++; @endphp
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (($exam['total_score'] ?? 0) >= 10)
                                                        <span style="color: green; font-weight: bold;"><b>VAL</b></span>
                                                    @else
                                                        <span style="color: red; font-weight: bold;"><b>NVL</b></span>
                                                        @php $fail_count++; @endphp
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        {{-- <tr>
                                            <td colspan="1">
                                                <canvas id="creditsChart" width="50" height="50"></canvas>
                                            </td>
                                            <td colspan="3">
                                                <b>Grand Total: {{ $credits_obtenus }}/{{ $full_marks }}</b>
                                            </td>
                                        </tr> --}}
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info m-3">
                                    <i class="fas fa-info-circle"></i> Aucun résultat d'examen enregistré pour cette
                                    session.
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="alert alert-warning mt-4">
                    <i class="fas fa-info-circle"></i> Aucun résultat d'examen disponible pour le moment.
                </div>
            @endif
        </section>
    </div>
@endsection


@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('creditsChart').getContext('2d');
            var creditsChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Crédits Obtenus', 'Crédits Restants'],
                    datasets: [{
                        data: [{{ $credits_obtenus }}, {{ $full_marks - $credits_obtenus }}],
                        backgroundColor: ['#27ae60', '#ddd'],
                    }]
                },
                options: {
                    cutoutPercentage: 70,
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false
                    }
                }
            });
        });
    </script> --}}
@endsection
