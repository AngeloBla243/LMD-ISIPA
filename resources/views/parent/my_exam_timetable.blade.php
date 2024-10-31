@extends('layouts.app')
@section('style')

<style type="text/css">
.styled-table {
    border-collapse: collapse;
    margin: 25px 0;
    min-width: 400px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
    border-radius: 8px;
    overflow: hidden;
}
.styled-table thead tr {
    background-color: #009879;
    color: #ffffff;
    text-align: left;
}
.styled-table th,
.styled-table td {
    padding: 12px 15px;
}
.styled-table tbody tr {
    border-bottom: 1px solid #dddddd;
}

.styled-table tbody tr:nth-of-type(even) {
    background-color: #f3f3f3;
}

.styled-table tbody tr:last-of-type {
    border-bottom: 2px solid #009879;
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
            <h1>Exam Timetable <span style="color: blue;">({{ $getStudent->name }} {{ $getStudent->last_name }})</span></h1>
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

             @include('_message')

             @foreach($getRecord as $value)
              <div class="card">
              <div class="card-header">
                <h3 class="card-title">{{ $value['name'] }}</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0" style="overflow: auto;">
                <table class="table styled-table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Subject Name</th>
                      <th>Day</th>
                      <th>Exam Date</th>
                      <th>Start Time </th>
                      <th>End Time </th>
                      <th>Room Number</th>
                      <th>Full Marks </th>
                      <th>Passing Marks </th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($value['exam'] as $valueS)
                      <tr>
                          <td>{{ $valueS['subject_name'] }}</td>
                          <td>{{ date('l', strtotime($valueS['exam_date'])) }}</td>
                          <td>{{ date('d-m-Y', strtotime($valueS['exam_date'])) }}</td>
                          <td>{{ date('h:i A', strtotime($valueS['start_time'])) }}</td>
                          <td>{{ date('h:i A', strtotime($valueS['end_time'])) }}</td>
                          <td>{{ $valueS['room_number'] }}</td>
                          <td>{{ $valueS['full_marks'] }}</td>
                          <td>{{ $valueS['passing_mark'] }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
            @endforeach

              </div>

              <!-- /.card-body -->
            </div>
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


