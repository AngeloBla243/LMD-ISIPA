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
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>My Exam Result</h1>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">

                    @foreach ($getRecord as $value)
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">{{ $value['exam_name'] }}</h3>
                                    <a class="btn btn-primary btn-sm" style="float: right;" target="_blank"
                                        href="{{ url('student/my_exam_result/print?exam_id=' . $value['exam_id'] . '&student_id=' . Auth::user()->id) }}">Print</a>
                                </div>
                                <div class="card-body p-0" style="overflow: auto;">
                                    <table class="table styled-table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>UE</th>
                                                {{-- <th>Class Work</th>
                            <th>Test Work</th>
                            <th>Home work</th>
                            <th>Exam</th> --}}
                                                <th>Crédit du Cours</th>
                                                <th>Note/20</th>
                                                {{-- <th>Passing Marks</th>
                            <th>Full Marks</th> --}}
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
                                                    $Grandtotals_score = $Grandtotals_score + $exam['totals_score'];
                                                    $total_score = $exam['total_score'] ?? 0;
                                                    $passing_mark = $exam['passing_mark']?? 0;
                                                    $full_marks += $exam['ponde'];

                                                    if ($total_score >= 10) {
                                                        $credits_obtenus += $exam['ponde'] ?? 0;
                                                    }

                                                @endphp
                                                <tr>
                                                    <td style="width: 200px">{{ $exam['subject_name'] }}</td>
                                                    {{-- <td>{{ $exam['class_work'] }}</td>
                                <td>{{ $exam['test_work'] }}</td>
                                <td>{{ $exam['home_work'] }}</td>
                                <td>{{ $exam['exam'] }}</td> --}}
                                                    <td>{{ $exam['ponde'] }}</td>
                                                    <td>
                                                        @if ($exam['total_score'] >= $exam['passing_mark'])
                                                            <span
                                                                style="color: green; font-weight: bold;"><b>{{ $exam['total_score'] }}</b></span>
                                                        @else
                                                            <span
                                                                style="color: red; font-weight: bold;"><b>{{ $exam['total_score'] }}</b></span>
                                                            @php
                                                                $fail_count++;
                                                            @endphp
                                                        @endif
                                                    </td>

                                                    <td>
                                                        @if ($exam['total_score'] >= 10)
                                                            <span style="color: green; font-weight: bold;"><b>VAL</b></span>
                                                        @else
                                                            <span style="color: red; font-weight: bold;"><b>NVL</b></span>
                                                            @php
                                                                $fail_count++;
                                                            @endphp
                                                        @endif
                                                    </td>




                                                    {{-- <td><b>{{ $exam['total_score'] }}</b></td> --}}
                                                    {{-- <td>{{ $exam['passing_marks'] }}</td>
                                <td><b>{{ $exam['full_marks'] }}</b></td> --}}

                                                </tr>
                                            @endforeach

                                            <tr>
                                                <td colspan="1">
                                                    {{-- <b>Grand Total: {{ $Grandtotals_score }}/{{ $full_marks }}</b> --}}
                                                </td>
                                                <td colspan="3">
                                                    <b>Grand Total: {{ $credits_obtenus }}/{{ $full_marks }}</b>

                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach


                </div>
                <!-- /.row -->

                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
