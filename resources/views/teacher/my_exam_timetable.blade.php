
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
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>My Exam Timetable</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    @include('_message')

                    @foreach($getRecord as $value)
                        <h2 style="font-size: 32px;margin-bottom: 15px;">Class: <span style="color: blue">{{ $value['class_name'] }}</span></h2>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Exam Details</h3>
                            </div>
                            <div class="card-body p-0" style="overflow: auto;">
                                <table class="table styled-table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Subject Name</th>
                                            <th>Day</th>
                                            <th style="min-width: 150px;">Exam Date</th>
                                            <th style="min-width: 150px;">Start Time</th>
                                            <th style="min-width: 150px;">End Time</th>
                                            <th style="min-width: 150px;">Room Number</th>
                                            <th style="min-width: 150px;">Full Marks</th>
                                            <th style="min-width: 150px;">Passing Marks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($value['exam'] as $exam)
                                            @foreach($exam['subjects'] as $valueS)
                                                <tr>
                                                    <td style="min-width: 300px;">{{ $valueS['subject_name'] }}</td>
                                                    <td>{{ date('l', strtotime($valueS['exam_date'])) }}</td>
                                                    <td>{{ date('d-m-Y', strtotime($valueS['exam_date'])) }}</td>
                                                    <td>{{ date('h:i A', strtotime($valueS['start_time'])) }}</td>
                                                    <td>{{ date('h:i A', strtotime($valueS['end_time'])) }}</td>
                                                    <td>{{ $valueS['room_number'] }}</td>
                                                    <td>{{ $valueS['full_marks'] }}</td>
                                                    <td>{{ $valueS['passing_mark'] }}</td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
