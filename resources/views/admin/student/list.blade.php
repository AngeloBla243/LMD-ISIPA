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
                        <h1>Student List (Total : {{ $getRecord->total() }})</h1>
                    </div>
                    <div class="col-sm-6" style="text-align: right;">
                        <a href="{{ url('admin/student/add') }}" class="btn btn-info"><i class="fa-solid fa-file-circle-plus"></i> Add New Student</a>
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
                                <h3 class="card-title">Search Student </h3>
                            </div>
                            <form method="get" action="">
                                <div class="card-body">
                                    <div class="row">


                                        <div class="form-group col-md-2">
                                            <label>Name</label>
                                            <input type="text" class="form-control" value="{{ Request::get('name') }}"
                                                name="name" placeholder="Name">
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label>Last Name</label>
                                            <input type="text" class="form-control"
                                                value="{{ Request::get('last_name') }}" name="last_name"
                                                placeholder="Last Name">
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label>Email</label>
                                            <input type="text" class="form-control" name="email"
                                                value="{{ Request::get('email') }}" placeholder="Email">
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label>Admission Number</label>
                                            <input type="text" class="form-control" name="admission_number"
                                                value="{{ Request::get('admission_number') }}"
                                                placeholder="Admission Number">
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label>Roll Number</label>
                                            <input type="text" class="form-control" name="roll_number"
                                                value="{{ Request::get('roll_number') }}" placeholder="Roll Number">
                                        </div>



                                        <div class="form-group col-md-2">
                                            <label>Class</label>
                                            <input type="text" class="form-control" name="class"
                                                value="{{ Request::get('class') }}" placeholder="Class">
                                        </div>


                                        <div class="form-group col-md-2">
                                            <label>Gender</label>
                                            <select class="form-control" name="gender">
                                                <option value="">Select Gender</option>
                                                <option {{ Request::get('gender') == 'Male' ? 'selected' : '' }}
                                                    value="Male">Male</option>
                                                <option {{ Request::get('gender') == 'Female' ? 'selected' : '' }}
                                                    value="Female">Female</option>
                                                <option {{ Request::get('gender') == 'Other' ? 'selected' : '' }}
                                                    value="Other">Other</option>
                                            </select>
                                        </div>


                                        <div class="form-group col-md-2">
                                            <label>Caste</label>
                                            <input type="text" class="form-control" name="caste"
                                                value="{{ Request::get('caste') }}" placeholder="Caste">
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label>Religion</label>
                                            <input type="text" class="form-control" name="religion"
                                                value="{{ Request::get('religion') }}" placeholder="Religion">
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label>Mobile Number</label>
                                            <input type="text" class="form-control" name="mobile_number"
                                                value="{{ Request::get('mobile_number') }}" placeholder="Mobile Number">
                                        </div>


                                        <div class="form-group col-md-2">
                                            <label>Blood Group</label>
                                            <input type="text" class="form-control" name="blood_group"
                                                value="{{ Request::get('blood_group') }}" placeholder="Blood Group">
                                        </div>


                                        <div class="form-group col-md-2">
                                            <label>Status</label>
                                            <select class="form-control" name="status">
                                                <option value="">Select Status</option>
                                                <option {{ Request::get('status') == 100 ? 'selected' : '' }}
                                                    value="100">Active</option>
                                                <option {{ Request::get('status') == 1 ? 'selected' : '' }}
                                                    value="1">Inactive</option>

                                            </select>
                                        </div>





                                        <div class="form-group col-md-2">
                                            <label>Admission Date</label>
                                            <input type="date" class="form-control" name="admission_date"
                                                value="{{ Request::get('admission_date') }}">
                                        </div>






                                        <div class="form-group col-md-2">
                                            <label>Created Date</label>
                                            <input type="date" class="form-control" name="date"
                                                value="{{ Request::get('date') }}" placeholder="">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <button class="btn btn-primary" type="submit"
                                                style="margin-top: 30px;"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
                                            <a href="{{ url('admin/student/list') }}" class="btn btn-success"
                                                style="margin-top: 30px;">Reset</a>

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
                                <form action="{{ url('admin/student/export_excel') }}" method="post"
                                    style="float: right;">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="name" value="{{ Request::get('name') }}">
                                    <input type="hidden" name="last_name" value="{{ Request::get('last_name') }}">
                                    <input type="hidden" name="email" value="{{ Request::get('email') }}">
                                    <input type="hidden" name="admission_number"
                                        value="{{ Request::get('admission_number') }}">
                                    <input type="hidden" name="roll_number" value="{{ Request::get('roll_number') }}">
                                    <input type="hidden" name="gender" value="{{ Request::get('gender') }}">
                                    <input type="hidden" name="class" value="{{ Request::get('class') }}">
                                    <input type="hidden" name="caste" value="{{ Request::get('caste') }}">
                                    <input type="hidden" name="religion" value="{{ Request::get('religion') }}">
                                    <input type="hidden" name="mobile_number"
                                        value="{{ Request::get('mobile_number') }}">
                                    <input type="hidden" name="blood_group" value="{{ Request::get('blood_group') }}">
                                    <input type="hidden" name="status" value="{{ Request::get('status') }}">
                                    <input type="hidden" name="admission_date"
                                        value="{{ Request::get('admission_date') }}">
                                    <input type="hidden" name="date" value="{{ Request::get('date') }}">
                                    <button class="btn btn-primary">Export Excel</button>
                                </form>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body p-0" style="overflow: auto;">
                                <table class="table styled-table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Profile Pic</th>
                                            <th style="min-width: 150px;">Student Name</th>
                                            <th style="min-width: 150px;">Parent Name</th>
                                            <th>Email</th>
                                            <th style="min-width: 200px;">Admission Number</th>
                                            <th style="min-width: 150px;">Roll Number</th>
                                            <th>Class</th>
                                            <th>Gender</th>
                                            <th style="min-width: 150px;">Date of Birth </th>
                                            <th>Caste </th>
                                            <th>Religion</th>
                                            <th style="min-width: 150px;">Mobile Number</th>
                                            <th style="min-width: 150px;">Admission Date</th>
                                            <th style="min-width: 150px;">Blood Group</th>
                                            <th>Height</th>
                                            <th>Weight</th>
                                            <th>Status</th>
                                            <th style="min-width: 150px;">Created Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($getRecord as $value)
                                            <tr>
                                                <td style="min-width: 10px;">
                                                    {{ ($getRecord->currentPage() - 1) * $getRecord->perPage() + $loop->iteration }}
                                                </td>
                                                <td style="min-width: 100px;">
                                                    @if (!empty($value->getProfileDirect()))
                                                        <img src="{{ $value->getProfileDirect() }}"
                                                            style="height: 50px; width:50px; border-radius: 50px;">
                                                    @endif
                                                </td>

                                                <td style="min-width: 100px;">{{ $value->name }} {{ $value->last_name }}
                                                </td>
                                                <td style="min-width: 100px;">{{ $value->parent_name }}
                                                    {{ $value->parent_last_name }}</td>
                                                <td style="min-width: 100px;">{{ $value->email }}</td>
                                                <td style="min-width: 100px;">{{ $value->admission_number }}</td>
                                                <td style="min-width: 100px;">{{ $value->roll_number }}</td>
                                                <td style="min-width: 100px;">{{ $value->class_name }}</td>
                                                <td style="min-width: 100px;">{{ $value->gender }}</td>
                                                <td style="min-width: 100px;">
                                                    @if (!empty($value->date_of_birth))
                                                        {{ date('d-m-Y', strtotime($value->date_of_birth)) }}
                                                    @endif
                                                </td>
                                                <td style="min-width: 100px;">{{ $value->caste }}</td>
                                                <td style="min-width: 100px;">{{ $value->religion }}</td>
                                                <td style="min-width: 100px;">{{ $value->mobile_number }}</td>
                                                <td style="min-width: 100px;">
                                                    @if (!empty($value->admission_date))
                                                        {{ date('d-m-Y', strtotime($value->admission_date)) }}
                                                    @endif
                                                </td>
                                                <td style="min-width: 100px;">{{ $value->blood_group }}</td>
                                                <td style="min-width: 100px;">{{ $value->height }}</td>
                                                <td style="min-width: 100px;">{{ $value->weight }}</td>
                                                <td style="min-width: 100px;">
                                                    {{ $value->status == 0 ? 'Active' : 'Inactive' }}</td>


                                                <td style="min-width: 100px;">
                                                    {{ date('d-m-Y H:i A', strtotime($value->created_at)) }}</td>
                                                <td style="min-width: 400px;">
                                                    <a href="{{ url('admin/student/edit/' . $value->id) }}"
                                                        class="btn btn-info  ">
                                                        <i class="fas fa-pencil-alt"></i>
                                                        Edit
                                                    </a>
                                                    <a href="{{ url('admin/student/delete/' . $value->id) }}"
                                                        class="btn btn-danger ">
                                                        <i class="fas fa-trash"></i>
                                                        Delete
                                                    </a>
                                                    <a href="{{ url('chat?receiver_id=' . base64_encode($value->id)) }}"
                                                        class="btn btn-success">
                                                        <i class="fas fa-comments"></i>
                                                        Send Message
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div style="padding: 10px; float: right;">
                                    {!! $getRecord->appends(Illuminate\Support\Facades\Request::except('page'))->links() !!}
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
