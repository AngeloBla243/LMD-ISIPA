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
            <h1>My Student </h1>
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

            <!-- /.card -->




             <div class="card">
              <div class="card-header">
                <h3 class="card-title">My Student</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0" style="overflow: auto;">
                <table class="table styled-table table-bordered table-striped">
                  <thead>
                    <tr>

                      <th style="min-width: 200px;">Profile Pic</th>
                      <th style="min-width: 200px;">Student Name</th>
                      <th>Email</th>
                      <th style="min-width: 200px;">Admission Number</th>
                      <th style="min-width: 200px;">Roll Number</th>
                      <th>Class</th>


                      <th style="min-width: 200px;">Admission Date</th>
                      <th style="min-width: 200px;">Blood Group</th>
                      <th>Height</th>
                      <th>Weight</th>
                      <th style="min-width: 200px;">Created Date</th>
                      <th>Action</th>

                    </tr>
                  </thead>
                  <tbody>
                     @foreach($getRecord as $value)
                        <tr>


                          <td>
                            @if(!empty($value->getProfile()))
                            <img src="{{ $value->getProfile() }}" style="height: 50px; width:50px; border-radius: 50px;">
                            @endif
                          </td>

                          <td>{{ $value->name }} {{ $value->last_name }}</td>
                          <td>{{ $value->email }}</td>
                          <td>{{ $value->admission_number }}</td>
                          <td>{{ $value->roll_number }}</td>
                          <td>{{ $value->class_name }}</td>


                          <td>
                            @if(!empty($value->admission_date))
                              {{ date('d-m-Y', strtotime($value->admission_date)) }}
                              @endif
                          </td>
                          <td>{{ $value->blood_group }}</td>
                          <td>{{ $value->height }}</td>
                          <td>{{ $value->weight }}</td>

                          <td>{{ date('d-m-Y H:i A', strtotime($value->created_at)) }}</td>
                          <td style="min-width: 800px;">
                            <a  style="margin-bottom: 10px;" class="btn btn-info" href="{{ url('parent/my_student/subject/'.$value->id) }}"><i class="fas fa-book"></i> Subject</a>
                            <a style="margin-bottom: 10px;" class="btn btn-primary" href="{{ url('parent/my_student/exam_timetable/'.$value->id) }}"><i class="far fa-calendar-alt"></i> Exam Timetable</a>

                            <a style="margin-bottom: 10px;" class="btn btn-info" href="{{ url('parent/my_student/exam_result/'.$value->id) }}"><i class="fas fa-edit"></i> Exam Result</a>

                            <a style="margin-bottom: 10px;" class="btn btn-warning" href="{{ url('parent/my_student/calendar/'.$value->id) }}"><i class="far fa-calendar-alt"></i> Calendar</a>

                            <a style="margin-bottom: 10px;" class="btn btn-primary" href="{{ url('parent/my_student/attendance/'.$value->id) }}"><i class="far fa-clock"></i> Attendance</a>

                            <a style="margin-bottom: 10px;" class="btn btn-info" href="{{ url('parent/my_student/homewrok/'.$value->id) }}"><i class="fas fa-edit"></i> Homework</a>

                            <a style="margin-bottom: 10px;" class="btn btn-primary" href="{{ url('parent/my_student/submitted_homewrok/'.$value->id) }}"><i class="fas fa-download"></i> Submitted Homework</a>

                            <a style="margin-bottom: 10px;" class="btn btn-success" href="{{ url('parent/my_student/fees_collection/'.$value->id) }}"><i class="far fa-credit-card"></i> Fees Collection</a>


                            <a style="margin-bottom: 10px;" href="{{ url('chat?receiver_id='.base64_encode($value->id)) }}" class="btn btn-success btn-sm"><i class="fas fa-comments"></i> Send Message</a>

                          </td>

                        </tr>
                      @endforeach
                  </tbody>
                </table>
                <div style="padding: 10px; float: right;">

                </div>

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
