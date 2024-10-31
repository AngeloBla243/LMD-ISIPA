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
          <div class="col-sm-12">
            <h1>Collect Fees</h1>
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
                <h3 class="card-title">Search Collect Fees Student</h3>
              </div>
              <form method="get" action="">
                <div class="card-body">
                  <div class="row">


                  <div class="form-group col-md-2">
                    <label>Class</label>
                    <select class="form-control" name="class_id">
                        <option value="">Select Class</option>
                        @foreach($getClass as $class)
                        <option {{ (Request::get('class_id') == $class->id) ? 'selected' : '' }} value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                  </div>


                   <div class="form-group col-md-2">
                    <label>Student ID</label>
                    <input type="text" class="form-control" value="{{ Request::get('student_id') }}" name="student_id"  placeholder="Student ID">
                  </div>


                  <div class="form-group col-md-3">
                    <label>Student First Name</label>
                    <input type="text" class="form-control" value="{{ Request::get('first_name') }}" name="first_name"  placeholder="Student First Name">
                  </div>


                  <div class="form-group col-md-3">
                    <label>Student Last Name</label>
                    <input type="text" class="form-control" value="{{ Request::get('last_name') }}" name="last_name"  placeholder="Student Last Name">
                  </div>


                  <div class="form-group col-md-3">
                    <button class="btn btn-primary" type="submit" style="margin-top: 30px;"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
                    <a href="{{ url('admin/fees_collection/collect_fees') }}" class="btn btn-success" style="margin-top: 30px;">Reset</a>

                  </div>

                  </div>
                </div>
              </form>
            </div>



            @include('_message')

            <!-- /.card -->

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Student List</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0" style="overflow: auto;">
                <table class="table styled-table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th style="min-width: 180px;">Student ID</th>
                      <th style="min-width: 180px;">Student Name</th>
                      <th style="min-width: 180px;">Class Name</th>
                      <th style="min-width: 180px;">Total Amount</th>
                      <th style="min-width: 180px;">Paid Amount</th>
                      <th style="min-width: 180px;">Remaning Amount</th>
                      <th style="min-width: 180px;">Created Date</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                      @if(!empty($getRecord))
                          @forelse($getRecord as $value)
                              @php
                                $paid_amount = $value->getPaidAmount($value->id, $value->class_id);

                                 $RemaningAmount = $value->amount - $paid_amount;
                              @endphp
                            <tr>
                              <td style="min-width: 90px;">{{ $value->id }}</td>
                              <td style="min-width: 200px;">{{ $value->name }} {{ $value->last_name }}</td>
                              <td style="min-width: 300px;">{{ $value->class_name }}</td>
                              <td style="min-width: 100px;">${{ number_format($value->amount, 2) }}</td>
                              <td style="min-width: 100px;">${{ number_format($paid_amount, 2) }}</td>
                              <td style="min-width: 100px;">${{ number_format($RemaningAmount, 2) }}</td>
                              <td style="min-width: 100px;">{{ date('d-m-Y', strtotime($value->created_at)) }}</td>
                              <td style="min-width: 180px;">
                                  <a href="{{ url('admin/fees_collection/collect_fees/add_fees/'.$value->id) }}" class="btn btn-success"><i class="far fa-credit-card"></i> Collect Fees</a>
                              </td>
                            </tr>
                          @empty
                            <tr>
                              <td colspan="100%">Record not found</td>
                            </tr>
                          @endforelse
                      @else
                        <tr>
                          <td colspan="100%">Record not found</td>
                        </tr>
                      @endif
                  </tbody>
                </table>
                <div style="padding: 10px; float: right;">
                   @if(!empty($getRecord))
                     {!! $getRecord->appends(Illuminate\Support\Facades\Request::except('page'))->links() !!}
                   @endif
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
