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
        <!-- Content Header -->
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h1 class="h3 fw-bold text-primary">
                            Exam List (Total : {{ $getRecord->total() }})
                        </h1>
                    </div>
                    {{-- <div class="col-sm-6 text-end">
                        <a href="{{ url('admin/examinations/exam/add') }}" class="btn btn-info shadow-sm rounded-3">
                            <i class="fa-solid fa-file-circle-plus me-2"></i> Add New Exam
                        </a>
                    </div> --}}
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content pb-5">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">

                        <!-- Search Card -->
                        <div class="card shadow-sm rounded-4 border-0 mb-4">
                            <div class="card-header bg-primary text-white rounded-top-4">
                                <h3 class="card-title mb-0">
                                    <i class="fa-solid fa-magnifying-glass me-2"></i>Search Exam
                                </h3>
                            </div>
                            <form method="get" action="">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label for="name" class="form-label fw-semibold">Exam Name</label>
                                            <input type="text" id="name" name="name" class="form-control"
                                                value="{{ Request::get('name') }}" placeholder="Exam Name">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="date" class="form-label fw-semibold">Date</label>
                                            <input type="date" id="date" name="date" class="form-control"
                                                value="{{ Request::get('date') }}">
                                        </div>
                                        <div class="col-md-3 d-flex align-items-end gap-2">
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="fa-solid fa-magnifying-glass me-1"></i> Search
                                            </button>
                                            <a href="{{ url('admin/examinations/exam/list') }}"
                                                class="btn btn-success w-100">
                                                Reset
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        @include('_message')

                        <!-- Exam List Card -->
                        <div class="card shadow-sm rounded-4 border-0">
                            <div class="card-header bg-primary text-white rounded-top-4">
                                <h3 class="card-title mb-0">
                                    <i class="fa-solid fa-list-ul me-2"></i>Exam List
                                </h3>
                            </div>
                            <div class="card-body p-0 table-responsive">
                                <table class="table table-hover table-bordered align-middle mb-0">
                                    <thead class="table-primary text-center text-uppercase small">
                                        <tr>
                                            <th>#</th>
                                            <th>Année Académique</th>
                                            <th>Session</th>
                                            <th>Nom</th>
                                            <th>Note</th>
                                            <th>Statut</th>
                                            <th>Date création</th>
                                            <th>Actions</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($getRecord as $value)
                                            <tr>
                                                <td style="min-width: 200px;" class="text-center">{{ $value->id }}</td>
                                                <td style="min-width: 200px;" class="text-center">
                                                    {{ $value->academic_year_name ?? 'N/A' }}</td>
                                                <td style="min-width: 200px;" class="text-center">{{ $value->session }}</td>
                                                <td style="min-width: 200px;" class="text-center">{{ $value->name }}</td>
                                                <td style="min-width: 200px;" class="text-center">{{ $value->note }}</td>
                                                {{-- <td style="min-width: 200px;" class="text-center">
                                                    {{ $value->created_name }}</td> --}}
                                                <td style="min-width: 200px;" class="text-center">
                                                    Session {{ $value->session }}
                                                    @if ($value->is_active)
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-secondary">Inactive</span>
                                                    @endif
                                                </td>
                                                <td style="min-width: 200px;" class="text-center">
                                                    {{ date('d-m-Y H:i A', strtotime($value->created_at)) }}</td>
                                                <td class="text-center" style="min-width: 200px;" class="text-center">
                                                    <a href="{{ url('admin/examinations/exam/edit/' . $value->id) }}"
                                                        class="btn btn-info btn-sm me-1" title="Edit">
                                                        <i class="fas fa-pencil-alt"></i> Edit
                                                    </a>
                                                    <a href="{{ url('admin/examinations/exam/delete/' . $value->id) }}"
                                                        class="btn btn-danger btn-sm" title="Delete"
                                                        onclick="return confirm('Are you sure you want to delete this exam?');">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </a>
                                                </td>


                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="mt-3 d-flex justify-content-end px-3">
                                    {!! $getRecord->appends(Illuminate\Support\Facades\Request::except('page'))->links() !!}
                                </div>
                            </div>
                        </div>

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
    </style>
@endsection
