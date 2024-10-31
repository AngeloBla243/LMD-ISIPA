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
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Exam Schedule</h1>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>



        <!-- Main content -->
        <section class="content">


            <div class="container-fluid">
                <div class="row">

                    <!-- /.col -->
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Search Exam Schedule</h3>
                            </div>
                            <form method="get" action="">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label>Exam</label>
                                            <select class="form-control getClass" name="exam_id" required>
                                                <option value="">Select</option>
                                                @foreach ($getExam as $exam)
                                                    <option {{ Request::get('exam_id') == $exam->id ? 'selected' : '' }}
                                                        value="{{ $exam->id }}">{{ $exam->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>Class</label>
                                            <select class="form-control getClass" name="class_id" required>
                                                <option value="">Select</option>
                                                @foreach ($getClass as $class)
                                                    <option {{ Request::get('class_id') == $class->id ? 'selected' : '' }}
                                                        value="{{ $class->id }}">{{ $class->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <button class="btn btn-primary" type="submit"
                                                style="margin-top: 30px;"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
                                            <a href="{{ url('admin/examinations/exam_schedule') }}" class="btn btn-success"
                                                style="margin-top: 30px;">reset</a>
                                        </div>

                                    </div>
                                </div>
                            </form>
                        </div>


                        @include('_message')

                        <!-- /.card -->

                        @if (!empty($getRecord))
                            <form action="{{ url('admin/examinations/exam_schedule_insert') }}" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="exam_id" value="{{ Request::get('exam_id') }}">
                                <input type="hidden" name="class_id" value="{{ Request::get('class_id') }}">

                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Exam Schedule</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body p-0" style="overflow: auto;">
                                        <table class="table styled-table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Subject Name</th>
                                                    <th>Exam Date</th>
                                                    <th>Start Time</th>
                                                    <th>End Time</th>
                                                    <th>Room Number</th>
                                                    <th>Full Marks</th>
                                                    <th>Passing Marks</th>
                                                    <th>Ponderation</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $i = 1;
                                                @endphp
                                                @foreach ($getRecord as $value)
                                                    <tr>
                                                        <td style="min-width: 250px;">{{ $value['subject_name'] }}
                                                            <input type="hidden" class="form-control"
                                                                value="{{ $value['subject_id'] }}"
                                                                name="schedule[{{ $i }}][subject_id]">
                                                        </td>
                                                        <td>
                                                            <input type="date" class="form-control"
                                                                value="{{ $value['exam_date'] }}"
                                                                name="schedule[{{ $i }}][exam_date]">
                                                        </td>
                                                        <td>
                                                            <input type="time" class="form-control"
                                                                value="{{ $value['start_time'] }}"
                                                                name="schedule[{{ $i }}][start_time]">
                                                        </td>
                                                        <td>
                                                            <input type="time" class="form-control"
                                                                value="{{ $value['end_time'] }}"
                                                                name="schedule[{{ $i }}][end_time]">
                                                        </td>
                                                        <td>
                                                            <input type="text" style="width: 200px;"
                                                                value="{{ $value['room_number'] }}" class="form-control"
                                                                name="schedule[{{ $i }}][room_number]">
                                                        </td>
                                                        <td>
                                                            <input type="text" style="width: 200px;"
                                                                value="{{ $value['full_marks'] }}" class="form-control"
                                                                name="schedule[{{ $i }}][full_marks]">
                                                        </td>
                                                        <td>
                                                            <input type="text" style="width: 200px;"
                                                                value="{{ $value['passing_mark'] }}" class="form-control"
                                                                name="schedule[{{ $i }}][passing_mark]">
                                                        </td>
                                                        <td>
                                                            <input type="text" style="width: 200px;"
                                                                value="{{ $value['ponde'] }}" class="form-control"
                                                                name="schedule[{{ $i }}][ponde]">
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $i++;
                                                    @endphp
                                                @endforeach
                                            </tbody>
                                        </table>

                                        <div style="text-align: center; padding: 20px;">
                                            <button class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @endif

                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->

                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
