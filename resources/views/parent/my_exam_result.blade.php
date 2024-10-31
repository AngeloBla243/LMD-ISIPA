@extends('layouts.app')
@section('style')

<style type="text/css">
.styled-table thead tr {
    background-color: #009879;
    color: #ffffff;
    text-align: left;
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
                        <h1>My Exam Result <span style="color: blue">({{ $getStudent->name }} {{ $getStudent->last_name }})</span></h1>
                    </div>

                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">


            <div class="container-fluid">
                <div class="row">

                    @foreach ($getRecord as $value)

                    <div class="col-md-12">

                        <!-- /.card -->

                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ $value['exam_name'] }}</h3>
                                <a class="btn btn-primary btn-sm" style="float: right;" target="_blank" href="{{ url('parent/my_exam_result/print?exam_id='.$value['exam_id'].'&student_id='.$getStudent->id) }}">Print</a>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body p-0" style="overflow: auto;">
                                <table class="table styled-table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Subject Name</th>
                                            {{-- <th>Class Work</th>
                                            <th>Test Work</th>
                                            <th>Home work</th>
                                            <th>Exam</th> --}}
                                            <th>Ponderation</th>
                                            <th>Note/20</th>
                                            {{-- <th>Passing Marks</th>
                                            <th>Full Marks</th> --}}
                                            {{-- <th>Result</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $Grandtotals_score = 0;
                                            $full_marks = 0;
                                            $fail_count = 0;
                                        @endphp
                                        @foreach ($value['subject'] as $exam)
                                            @php
                                                $Grandtotals_score = $Grandtotals_score + $exam['totals_score'];
                                                $full_marks = $full_marks + ($exam['full_marks'] * $exam['ponde']);
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
                                                        <span style="color: green; font-weight: bold;"><b>{{ $exam['total_score'] }}</b></span>
                                                    @else
                                                        <span style="color: red; font-weight: bold;"><b>{{ $exam['total_score'] }}</b></span>
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
                                                <b>Grand Total: {{ $Grandtotals_score }}/{{ $full_marks }}</b>
                                            </td>
                                            <td colspan="1">
                                                @php
                                                   $pourcentage = ($Grandtotals_score * 100) / $full_marks;
                                                   $getGrade = App\Models\MarksGradeModel::getGrade($pourcentage);
                                                @endphp
                                                <b>Pourcentage: {{ round($pourcentage) }}%</b>
                                                <br />
                                                <b>Grade : {{ $getGrade }}</b>

                                            </td>
                                            <td colspan="1"><b>Pourcentage: {{ round(($Grandtotals_score * 100) / $full_marks)}}%</b>
                                                <br /><b> Echecs: {{ $fail_count}}</b>
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
