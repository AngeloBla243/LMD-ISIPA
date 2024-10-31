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
            <h1>Notice Board</h1>
          </div>
          <div class="col-sm-6" style="text-align: right;">
              <a href="{{ url('admin/communicate/notice_board/add') }}" class="btn btn-info"><i class="fa-solid fa-file-circle-plus"></i> Add New Notice Board</a>
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
                <h3 class="card-title">Search Notice Board</h3>
              </div>
              <form method="get" action="">
                <div class="card-body">
                  <div class="row">


                  <div class="form-group col-md-2">
                    <label>Title</label>
                    <input type="text" class="form-control" value="{{ Request::get('title') }}" name="title"  placeholder="Title">
                  </div>






                  <div class="form-group col-md-2">
                    <label>Notice Date From</label>
                    <input type="date" class="form-control" name="notice_date_from" value="{{ Request::get('notice_date_from') }}"  >
                  </div>

                  <div class="form-group col-md-2">
                    <label>Notice Date To</label>
                    <input type="date" class="form-control" name="notice_date_to" value="{{ Request::get('notice_date_to') }}"  >
                  </div>

                  <div class="form-group col-md-2">
                    <label>Publish Date From</label>
                    <input type="date" class="form-control" name="publish_date_from" value="{{ Request::get('publish_date_from') }}"  >
                  </div>


                   <div class="form-group col-md-2">
                    <label>Publish Date To</label>
                    <input type="date" class="form-control" name="publish_date_to" value="{{ Request::get('publish_date_to') }}"  >
                  </div>



                  <div class="form-group col-md-2">
                    <label>Message To</label>
                    <select class="form-control" name="message_to">
                        <option value="">Select</option>
                        <option {{ (Request::get('message_to') == 3) ? 'selected' : '' }} value="3">Student</option>
                        <option {{ (Request::get('message_to') == 4) ? 'selected' : '' }} value="4">Parent</option>
                        <option {{ (Request::get('message_to') == 2) ? 'selected' : '' }} value="2">Teacher</option>
                    </select>
                  </div>




                  <div class="form-group col-md-3">
                    <button class="btn btn-primary" type="submit" style="margin-top: 10px;"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
                    <a href="{{ url('admin/communicate/notice_board') }}" class="btn btn-success" style="margin-top: 10px;">Reset</a>

                  </div>

                  </div>
                </div>
              </form>
            </div>


            @include('_message')
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Notice Board List</h3>
              </div>
              <div class="card-body p-0" style="overflow: auto;">
                <table class="table styled-table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Title</th>
                      <th style="min-width: 200px;">Notice Date</th>
                      <th style="min-width: 200px;">Publish Date</th>
                      <th>Message To</th>
                      <th style="min-width: 180px;">Created By</th>
                      <th style="min-width: 180px;">Created Date</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($getRecord as $value)
                        <tr>
                            <td>{{ ($getRecord->currentPage() - 1) * $getRecord->perPage() + $loop->iteration }}</td>
                          <td style="min-width: 100px;">{{ $value->title }}</td>
                          <td style="min-width: 100px;">{{ date('d-m-Y', strtotime($value->notice_date)) }}</td>
                          <td style="min-width: 100px;">{{ date('d-m-Y', strtotime($value->publish_date)) }}</td>
                          <td style="min-width: 150px;">
                            @foreach($value->getMessage as $message)
                                @if($message->message_to == 2)
                                  <div>Teacher</div>
                                @elseif($message->message_to == 3)
                                  <div>Student</div>
                                @elseif($message->message_to == 4)
                                  <div>Parent</div>
                                @endif
                            @endforeach
                          </td>
                          <td>{{ $value->created_by_name }}</td>
                          <td>{{ date('d-m-Y', strtotime($value->created_at)) }}</td>
                          <td style="min-width: 280px;">

                            <a href="{{ url('admin/communicate/notice_board/edit/'.$value->id) }}" class="btn btn-info"><i class="fas fa-pencil-alt"></i> Edit</a>

                            <a href="{{ url('admin/communicate/notice_board/delete/'.$value->id) }}" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</a>


                          </td>
                        </tr>
                    @empty
                      <tr>
                        <td colspan="100%">Record not found.</td>
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
