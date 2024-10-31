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

.present {
    color: white;
    background-color: #28a745;
    padding: 5px 10px;
    border-radius: 5px;
}

.absent {
    color: white;
    background-color: #dc3545;
    padding: 5px 10px;
    border-radius: 5px;
}

.half {
    color: white;
    background-color: #282aa7;
    padding: 5px 10px;
    border-radius: 5px;

}

.late {
    color: white;
    background-color: #dc9435;
    padding: 5px 10px;
    border-radius: 5px;
}

</style>

@endsection
@section('content')
<div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>My Attendance <span style="color:blue">( Total : {{ $getRecord->total() }} )</span></h1>
          </div>
        </div>
      </div>
    </section>
    <section class="content">
      <div class="container-fluid">
        <div class="row">



         <div class="col-md-12">


              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Search My Attendance</h3>
                </div>
                <form method="get" action="">
                  <div class="card-body">
                    <div class="row">
                    <div class="form-group col-md-2">
                      <label>Class</label>
                      <select class="form-control" name="class_id" >
                          <option value="">Select</option>
                          @foreach($getClass as $class)
                            <option {{ (Request::get('class_id') == $class->class_id) ? 'selected' : '' }} value="{{ $class->class_id }}">{{ $class->class_name }}</option>
                          @endforeach
                      </select>
                    </div>

                    <div class="form-group col-md-2">
                      <label>Attendance Type</label>
                      <select class="form-control" name="attendance_type">
                          <option value="">Select</option>
                          <option {{ (Request::get('attendance_type') == 1) ? 'selected' : '' }} value="1">Present</option>
                          <option {{ (Request::get('attendance_type') == 2) ? 'selected' : '' }} value="2">Late</option>
                          <option {{ (Request::get('attendance_type') == 3) ? 'selected' : '' }} value="3">Absent</option>
                          <option {{ (Request::get('attendance_type') == 4) ? 'selected' : '' }} value="4">Half Day</option>
                      </select>
                    </div>



                    <div class="form-group col-md-2">
                      <label style="min-width: 300px;">Start Attendance Date</label>
                      <input type="date" class="form-control"  value="{{ Request::get('start_attendance_date') }}" name="start_attendance_date">
                    </div>

                     <div class="form-group col-md-2">
                      <label style="min-width: 300px;">End Attendance Date</label>
                      <input type="date" class="form-control"  value="{{ Request::get('end_attendance_date') }}" name="end_attendance_date">
                    </div>



                    <div class="form-group col-md-3">
                      <button class="btn btn-primary" type="submit" style="margin-top: 30px;"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
                      <a href="{{ url('student/my_attendance') }}" class="btn btn-success" style="margin-top: 30px;">Reset</a>
                    </div>
                    </div>
                  </div>
                </form>
              </div>


                 <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">My Attendance</h3>
                    </div>

                    <div class="card-body p-0" style="overflow: auto;">
                        <table class="table styled-table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>Class Name</th>
                            <th>Attendance Type</th>
                            <th>Attendance Date</th>
                            <th>Created Date</th>
                          </tr>
                        </thead>

                        <tbody>
                          @forelse($getRecord as $value)
                              <tr>
                                <td>{{ $value->class_name }}</td>
                                <td>
                                    @if($value->attendance_type == 1)
                                     <b class="present">P</b>
                                    @elseif($value->attendance_type == 2)
                                      <b class="late">L</b>
                                    @elseif($value->attendance_type == 3)
                                      <b class="absent">A</b>
                                    @elseif($value->attendance_type == 4)
                                      <b class="half">H</b>
                                    @endif
                                </td>
                                <td> {{ date('d-m-Y', strtotime($value->attendance_date)) }} </td>
                                <td> {{ date('d-m-Y H:i A', strtotime($value->created_at)) }} </td>
                              </tr>
                          @empty
                            <tr>
                              <td colspan="100%">Record not found</td>
                            </tr>
                          @endforelse
                            <tr>
                                <td colspan="1"></td>
                                <td colspan="2">
                                    @if(isset($attendanceRate))
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-green" role="progressbar" aria-valuenow="{{ $attendanceRate }}%" aria-valuemin="0" aria-valuemax="100" style="width: 47%">
                                            </div>
                                        </div>
                                        <small>
                                            <b>{{ number_format($attendanceRate, 2) }}% de presence</b>
                                            {{-- <b>{{ number_format($attendanceRate1, 2) }}% de Abscence</b> --}}
                                        </small>
                                    @endif
                                </td>
                                <td colspan="1"></td>
                            </tr>
                        </tbody>
                      </table>

                          <div style="padding: 10px; float: right;">
                            {!! $getRecord->appends(Illuminate\Support\Facades\Request::except('page'))->links() !!}
                          </div>

                    </div>
                  </div>
          </div>
        </div>
      </div>
    </section>
  </div>

@endsection

@section('script').



@endsection
