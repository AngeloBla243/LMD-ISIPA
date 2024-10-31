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
                        <h1>Parent Student List ({{ $getParent->name }} {{ $getParent->last_name }})</h1>
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
                                            <label>Student ID</label>
                                            <input type="text" class="form-control" value="{{ Request::get('id') }}"
                                                name="id" placeholder="Student ID">
                                        </div>


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



                                        <div class="form-group col-md-3">
                                            <button class="btn btn-primary" type="submit"
                                                style="margin-top: 30px;"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
                                            <a href="{{ url('admin/parent/my-student/' . $parent_id) }}"
                                                class="btn btn-success" style="margin-top: 30px;">Reset</a>

                                        </div>

                                    </div>
                                </div>
                            </form>
                        </div>


                        @include('_message')

                        <!-- /.card -->

                        @if (!empty($getSearchStudent))
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Student List</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body p-0" style="overflow: auto;">
                                    <table class="table styled-table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Profile Pic</th>
                                                <th>Student Name</th>
                                                <th>Email</th>
                                                <th style="min-width: 150px;">Parent Name</th>
                                                <th>Created Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($getSearchStudent as $value)
                                                <tr>
                                                    <td>{{ $value->id }}</td>
                                                    <td style="min-width: 200px;">
                                                        @if (!empty($value->getProfile()))
                                                            <img src="{{ $value->getProfile() }}"
                                                                style="height: 50px; width:50px; border-radius: 50px;">
                                                        @endif
                                                    </td>
                                                    <td style="min-width: 200px;">{{ $value->name }}
                                                        {{ $value->last_name }}</td>
                                                    <td style="min-width: 200px;">{{ $value->email }}</td>
                                                    <td>{{ $value->parent_name }}</td>

                                                    <td style="min-width: 200px;">
                                                        {{ date('d-m-Y H:i A', strtotime($value->created_at)) }}</td>
                                                    <td style="min-width: 300px;">

                                                        <a href="{{ url('admin/parent/assign_student_parent/' . $value->id . '/' . $parent_id) }}"
                                                            class="btn btn-primary"><i class="fas fa-user bg-primary"></i>
                                                            Add Student to Parent</a>

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
                        @endif



                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Parent Student List</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body p-0" style="overflow: auto;">
                                <table class="table styled-table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Profile Pic</th>
                                            <th>Student Name</th>
                                            <th>Email</th>
                                            <th style="min-width: 150px;">Parent Name</th>
                                            <th>Created Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($getRecord as $value)
                                            <tr>
                                                <td>{{ $value->id }}</td>
                                                <td style="min-width: 200px;">
                                                    @if (!empty($value->getProfile()))
                                                        <img src="{{ $value->getProfile() }}"
                                                            style="height: 50px; width:50px; border-radius: 50px;">
                                                    @endif
                                                </td>
                                                <td style="min-width: 200px;">{{ $value->name }} {{ $value->last_name }}
                                                </td>
                                                <td style="min-width: 200px;">{{ $value->email }}</td>
                                                <td style="min-width: 200px;">{{ $value->parent_name }}</td>

                                                <td style="min-width: 200px;">
                                                    {{ date('d-m-Y H:i A', strtotime($value->created_at)) }}</td>
                                                <td style="min-width: 150px;">

                                                    <a href="{{ url('admin/parent/assign_student_parent_delete/' . $value->id) }}"
                                                        class="btn btn-danger"><i class="fas fa-trash"></i> Delete</a>

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
