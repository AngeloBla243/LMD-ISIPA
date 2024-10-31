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
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Collect Fees Report </h1>
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
                <h3 class="card-title">Search Collect Fees Report</h3>
              </div>
              <form method="get" action="">
                <div class="card-body">
                  <div class="row">

                  <div class="form-group col-md-2">
                    <label>Student ID</label>
                    <input type="text" class="form-control" placeholder="Student ID" value="{{ Request::get('student_id') }}" name="student_id">
                  </div>


                   <div class="form-group col-md-2">
                    <label>Student Name</label>
                    <input type="text" class="form-control" placeholder="Student Name" value="{{ Request::get('student_name') }}" name="student_name">
                  </div>

                  <div class="form-group col-md-2">
                    <label>Student Last Name</label>
                    <input type="text" class="form-control" placeholder="Student Last Name" value="{{ Request::get('student_last_name') }}" name="student_last_name">
                  </div>



                  <div class="form-group col-md-2">
                    <label>Class</label>
                    <select class="form-control" name="class_id" >
                        <option value="">Select</option>
                        @foreach($getClass as $class)
                          <option {{ (Request::get('class_id') == $class->id) ? 'selected' : '' }} value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                  </div>

                   <div class="form-group col-md-2">
                    <label>Start Created Date</label>
                    <input type="date" class="form-control"  value="{{ Request::get('start_created_date') }}" name="start_created_date">
                  </div>

                   <div class="form-group col-md-2">
                    <label>End Created Date</label>
                    <input type="date" class="form-control"  value="{{ Request::get('end_created_date') }}" name="end_created_date">
                  </div>


                  <div class="form-group col-md-2">
                    <label>Payment Type</label>
                    <select class="form-control" name="payment_type">
                        <option value="">Select</option>
                        <option {{ (Request::get('payment_type') == 'Cash') ? 'selected' : '' }} value="Cash">Cash</option>
                        <option {{ (Request::get('payment_type') == 'Cheque') ? 'selected' : '' }} value="Cheque">Cheque</option>
                        <option {{ (Request::get('payment_type') == 'Paypal') ? 'selected' : '' }} value="Paypal">Paypal</option>
                        <option {{ (Request::get('payment_type') == 'Stripe') ? 'selected' : '' }} value="Stripe">Stripe</option>
                    </select>
                  </div>


                  <div class="form-group col-md-3">
                    <button class="btn btn-primary" type="submit" style="margin-top: 30px;"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
                    <a href="{{ url('admin/fees_collection/collect_fees_report') }}" class="btn btn-success" style="margin-top: 30px;">Reset</a>

                  </div>

                  </div>
                </div>
              </form>
            </div>





             @include('_message')
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Collect Fees Report</h3>
                <form style="float: right;" method="post" action="{{ url('admin/fees_collection/export_collect_fees_report') }}">
                   {{ csrf_field() }}
                  <input type="hidden" value="{{ Request::get('student_id') }}" name="student_id">
                  <input type="hidden" value="{{ Request::get('student_name') }}" name="student_name">
                  <input type="hidden" value="{{ Request::get('student_last_name') }}" name="student_last_name">
                  <input type="hidden" value="{{ Request::get('class_id') }}" name="class_id">
                  <input type="hidden" value="{{ Request::get('start_created_date') }}" name="start_created_date">
                  <input type="hidden" value="{{ Request::get('end_created_date') }}" name="end_created_date">
                  <input type="hidden" value="{{ Request::get('payment_type') }}" name="payment_type">
                  <button type="submit" class="btn btn-primary">Export Excel</button>
                </form>
              </div>

              <div class="card-body p-0" style="overflow: auto;">
                <table class="table styled-table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th style="min-width: 200px;">Student ID</th>
                      <th>Student Name</th>
                      <th>Class Name</th>
                      <th>Total Amount</th>
                      <th>Paid Amount</th>
                      <th>Remaning Amount</th>
                      <th>Payment Type</th>
                      <th>Remark</th>
                      <th>Created By</th>
                      <th>Created Date</th>
                    </tr>
                  </thead>
                  <tbody>
                      @forelse($getRecord as $value)
                        <tr>
                          <td style="min-width: 90px;">{{ $value->id }}</td>
                          <td style="min-width: 90px;">{{ $value->student_id }}</td>
                          <td style="min-width: 300px;">{{ $value->student_name_first }} {{ $value->student_name_last }}</td>
                          <td style="min-width: 300px;">{{ $value->class_name }}</td>
                          <td style="min-width: 300px;">${{ number_format($value->total_amount, 2) }}</td>
                          <td style="min-width: 300px;">${{ number_format($value->paid_amount, 2) }}</td>
                          <td style="min-width: 300px;">${{ number_format($value->remaning_amount, 2) }}</td>
                          <td style="min-width: 300px;">{{ $value->payment_type }}</td>
                          <td style="min-width: 300px;">{{ $value->remark }}</td>
                          <td style="min-width: 300px;">{{ $value->created_name }}</td>
                          <td style="min-width: 300px;">{{ date('d-m-Y', strtotime($value->created_at)) }}</td>
                        </tr>
                      @empty
                        <tr>
                          <td colspan="100%">Record not found</td>
                        </tr>
                      @endforelse
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

@section('script')


@endsection
