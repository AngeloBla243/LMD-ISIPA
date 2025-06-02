@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="h3 fw-bold text-primary">
                            Attendance Report <span class="text-primary fw-normal">(Total : {{ $getRecord->total() }})</span>
                        </h1>
                    </div>
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
                                    <i class="fa-solid fa-magnifying-glass me-2"></i>Search Attendance Report
                                </h3>
                            </div>
                            <form method="get" action="">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">Student ID</label>
                                            <input type="text" class="form-control" name="student_id"
                                                placeholder="Student ID" value="{{ Request::get('student_id') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">Student Name</label>
                                            <input type="text" class="form-control" name="student_name"
                                                placeholder="Student Name" value="{{ Request::get('student_name') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">Student Last Name</label>
                                            <input type="text" class="form-control" name="student_last_name"
                                                placeholder="Student Last Name"
                                                value="{{ Request::get('student_last_name') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">Class</label>
                                            <select class="form-select form-control" name="class_id">
                                                <option value="">Select</option>
                                                @foreach ($getClass as $class)
                                                    <option value="{{ $class->class_id }}"
                                                        {{ Request::get('class_id') == $class->class_id ? 'selected' : '' }}>
                                                        {{ $class->class_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">Attendance Date</label>
                                            <input type="date" class="form-control" name="start_attendance_date"
                                                value="{{ Request::get('start_attendance_date') }}">
                                        </div>
                                        {{-- <div class="col-md-2">
                                            <label class="form-label fw-semibold">End Attendance Date</label>
                                            <input type="date" class="form-control" name="end_attendance_date"
                                                value="{{ Request::get('end_attendance_date') }}">
                                        </div> --}}
                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">Attendance Type</label>
                                            <select class="form-select form-control" name="attendance_type">
                                                <option value="">Select</option>
                                                <option value="1"
                                                    {{ Request::get('attendance_type') == 1 ? 'selected' : '' }}>Present
                                                </option>
                                                {{-- <option value="2"
                                                    {{ Request::get('attendance_type') == 2 ? 'selected' : '' }}>Late
                                                </option> --}}
                                                <option value="3"
                                                    {{ Request::get('attendance_type') == 3 ? 'selected' : '' }}>Absent
                                                </option>
                                                {{-- <option value="4"
                                                    {{ Request::get('attendance_type') == 4 ? 'selected' : '' }}>Half Day
                                                </option> --}}
                                            </select>
                                        </div>
                                        <div class="col-md-3 d-flex align-items-end gap-2">
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="fa-solid fa-magnifying-glass me-1"></i> Search
                                            </button>
                                            <a href="{{ url('teacher/attendance/report') }}" class="btn btn-success w-100">
                                                Reset
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Attendance List Card -->
                        <div class="card shadow-sm rounded-4 border-0">
                            <div class="card-header bg-primary text-white rounded-top-4">
                                <h3 class="card-title mb-0">
                                    <i class="fa-solid fa-list-ul me-2"></i>Attendance List
                                </h3>
                            </div>
                            <div class="card-body p-0 table-responsive">
                                <table class="table table-hover table-bordered align-middle mb-0 styled-table">
                                    <thead class="table-primary text-center text-uppercase small">
                                        <tr>
                                            <th>Student ID</th>
                                            <th>Student Name</th>
                                            <th>Class Name</th>
                                            <th>Attendance Type</th>
                                            <th>Attendance Date</th>
                                            <th>Created By</th>
                                            <th>Created Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($getRecord as $value)
                                            <tr>
                                                <td class="text-center">{{ $value->student_id }}</td>
                                                <td>{{ $value->student_name }} {{ $value->student_last_name }}</td>
                                                <td>{{ $value->class_name }} {{ $value->class_opt }}</td>
                                                <td class="text-center">
                                                    @if ($value->attendance_type == 1)
                                                        <span class="badge bg-success">P</span>
                                                    @elseif($value->attendance_type == 2)
                                                        <span class="badge bg-warning text-dark">L</span>
                                                    @elseif($value->attendance_type == 3)
                                                        <span class="badge bg-danger">A</span>
                                                    @elseif($value->attendance_type == 4)
                                                        <span class="badge bg-info text-dark">H</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    {{ date('d-m-Y', strtotime($value->attendance_date)) }}</td>
                                                <td>{{ $value->created_name }}</td>
                                                <td>{{ date('d-m-Y H:i A', strtotime($value->created_at)) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted py-4">
                                                    <i class="fa-solid fa-folder-open fa-2x mb-2"></i><br>
                                                    Record not found
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                @if (!empty($getRecord))
                                    <div class="mt-3 d-flex justify-content-end px-3">
                                        {!! $getRecord->appends(Illuminate\Support\Facades\Request::except('page'))->links() !!}
                                    </div>
                                @endif
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

        .badge {
            font-size: 1rem;
            padding: 0.4em 0.75em;
        }

        .badge.bg-success {
            background-color: #198754 !important;
        }

        .badge.bg-warning {
            background-color: #ffc107 !important;
            color: #212529 !important;
        }

        .badge.bg-danger {
            background-color: #dc3545 !important;
        }

        .badge.bg-info {
            background-color: #0dcaf0 !important;
            color: #212529 !important;
        }
    </style>
@endsection

@section('script')
    .
@endsection
