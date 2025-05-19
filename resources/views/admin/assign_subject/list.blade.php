@extends('layouts.app')
@section('style')
    <style type="text/css">
        .styled-table {
            border-collapse: collapse;
            margin: 25px 0;
            min-width: 400px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
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
    </style>
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h1 class="h3 fw-bold text-primary">
                            Assign Subject List
                        </h1>
                    </div>
                    <div class="col-sm-6 text-end">
                        <a href="{{ url('admin/assign_subject/add') }}" class="btn btn-info shadow-sm rounded-3">
                            <i class="fa-solid fa-file-circle-plus me-2"></i> Add New Assign Subject
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content pb-5">
            <div class="container-fluid">
                <!-- Search Card -->
                <div class="card shadow-sm rounded-4 border-0 mb-4">
                    <div class="card-header bg-primary text-white rounded-top-4">
                        <h3 class="card-title mb-0">
                            <i class="fa-solid fa-magnifying-glass me-2"></i>Search Assign Subject
                        </h3>
                    </div>
                    <form method="get" action="">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Class Name</label>
                                    <input type="text" class="form-control" value="{{ Request::get('class_name') }}"
                                        name="class_name" placeholder="Class Name">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Subject Name</label>
                                    <input type="text" class="form-control" value="{{ Request::get('subject_name') }}"
                                        name="subject_name" placeholder="Subject Name">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Date</label>
                                    <input type="date" class="form-control" name="date"
                                        value="{{ Request::get('date') }}">
                                </div>
                                <div class="col-md-3 d-flex align-items-end gap-2">
                                    <button class="btn btn-primary w-100" type="submit">
                                        <i class="fa-solid fa-magnifying-glass me-1"></i> Search
                                    </button>
                                    <a href="{{ url('admin/assign_subject/list') }}" class="btn btn-success w-100">
                                        Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                @include('_message')

                <!-- List Card -->
                <div class="card shadow-sm rounded-4 border-0">
                    <div class="card-header bg-primary text-white rounded-top-4">
                        <h3 class="card-title mb-0">
                            <i class="fa-solid fa-list-ul me-2"></i>Assign Subject List
                        </h3>
                    </div>
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-hover table-bordered align-middle mb-0">
                            <thead class="table-primary text-center text-uppercase small">
                                <tr>
                                    <th>#</th>
                                    <th>Année Académique</th>
                                    <th>Class Name</th>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th>Status</th>
                                    <th>Created By</th>
                                    <th>Date Created</th>
                                    <th style="min-width: 260px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($getRecord as $value)
                                    <tr>
                                        <td class="text-center">{{ $value->id }}</td>
                                        <td style="min-width: 200px;">{{ $value->academic_year_name }}</td>
                                        <td style="min-width: 200px;">{{ $value->class_name }} {{ $value->class_opt }}</td>
                                        <td style="min-width: 200px;">{{ $value->subject_code }}</td>
                                        <td style="min-width: 200px;">{{ $value->subject_name }}</td>
                                        <td class="text-center" style="min-width: 200px;">
                                            <span class="badge {{ $value->status == 0 ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $value->status == 0 ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td style="min-width: 200px;">{{ $value->created_by_name }}</td>
                                        <td style="min-width: 200px;">
                                            {{ date('d-m-Y H:i A', strtotime($value->created_at)) }}</td>
                                        <td class="text-center" style="min-width: 200px;">
                                            <a href="{{ url('admin/assign_subject/edit/' . $value->id) }}"
                                                class="btn btn-info btn-sm me-1 mb-1" title="Edit">
                                                <i class="fas fa-pencil-alt"></i> Edit
                                            </a>
                                            <a href="{{ url('admin/assign_subject/edit_single/' . $value->id) }}"
                                                class="btn btn-primary btn-sm me-1 mb-1" title="Edit Single">
                                                <i class="fas fa-edit"></i> Edit Single
                                            </a>
                                            <a href="{{ url('admin/assign_subject/delete/' . $value->id) }}"
                                                class="btn btn-danger btn-sm mb-1" title="Delete"
                                                onclick="return confirm('Are you sure you want to delete this assignment?');">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">
                                            <i class="fa-solid fa-folder-open fa-2x mb-2"></i><br>
                                            No assigned subject found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @if ($getRecord instanceof \Illuminate\Pagination\AbstractPaginator && $getRecord->hasPages())
                            <div class="mt-3 d-flex justify-content-end px-3">
                                {!! $getRecord->appends(Illuminate\Support\Facades\Request::except('page'))->links() !!}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>

    <style>
        .card {
            border-radius: 1.25rem;
        }

        .card-header {
            border-radius: 1.25rem 1.25rem 0 0;
        }

        .table-primary th {
            background-color: #cfe2ff !important;
            color: #084298 !important;
            font-weight: 600;
        }

        .btn-info,
        .btn-danger,
        .btn-success,
        .btn-primary {
            font-weight: 500;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .badge {
            font-size: 0.9rem;
            padding: 0.4em 0.75em;
        }
    </style>
@endsection
