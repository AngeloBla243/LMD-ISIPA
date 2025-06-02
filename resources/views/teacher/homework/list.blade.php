@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <div class="row mb-2 align-items-center">
                    <div class="col-sm-6">
                        <h1 class="h3 fw-bold text-primary">Homework</h1>
                    </div>
                    <div class="col-sm-6 text-end">
                        <a href="{{ url('teacher/homework/homework/add') }}" class="btn btn-info shadow-sm rounded-3">
                            <i class="fa-solid fa-file-circle-plus me-2"></i> Add New Homework
                        </a>
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
                                    <i class="fa-solid fa-magnifying-glass me-2"></i>Search Homework
                                </h3>
                            </div>
                            <form method="get" action="">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">Class</label>
                                            <input type="text" class="form-control" name="class_name"
                                                value="{{ Request::get('class_name') }}" placeholder="Class Name">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">Subject</label>
                                            <input type="text" class="form-control" name="subject_name"
                                                value="{{ Request::get('subject_name') }}" placeholder="Subject Name">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">From Homework Date</label>
                                            <input type="date" class="form-control" name="from_homework_date"
                                                value="{{ Request::get('from_homework_date') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">To Homework Date</label>
                                            <input type="date" class="form-control" name="to_homework_date"
                                                value="{{ Request::get('to_homework_date') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">From Submission Date</label>
                                            <input type="date" class="form-control" name="from_submission_date"
                                                value="{{ Request::get('from_submission_date') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">To Submission Date</label>
                                            <input type="date" class="form-control" name="to_submission_date"
                                                value="{{ Request::get('to_submission_date') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">From Created Date</label>
                                            <input type="date" class="form-control" name="from_created_date"
                                                value="{{ Request::get('from_created_date') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">To Created Date</label>
                                            <input type="date" class="form-control" name="to_created_date"
                                                value="{{ Request::get('to_created_date') }}">
                                        </div>
                                        <div class="col-md-3 d-flex align-items-end gap-2">
                                            <button class="btn btn-primary w-100" type="submit">
                                                <i class="fa-solid fa-magnifying-glass me-1"></i> Search
                                            </button>
                                            <a href="{{ url('teacher/homework/homework') }}" class="btn btn-success w-100">
                                                Reset
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        @include('_message')

                        <!-- Homework List Card -->
                        <div class="card shadow-sm rounded-4 border-0">
                            <div class="card-header bg-primary text-white rounded-top-4">
                                <h3 class="card-title mb-0">
                                    <i class="fa-solid fa-list-ul me-2"></i> Homework List
                                </h3>
                            </div>
                            <div class="card-body p-0 table-responsive">
                                <table class="table table-hover table-bordered align-middle mb-0 styled-table">
                                    <thead class="table-primary text-center text-uppercase small">
                                        <tr>
                                            <th>#</th>
                                            <th style="min-width: 200px;">Class</th>
                                            <th style="min-width: 180px;">Subject</th>
                                            <th style="min-width: 180px;">Homework Date</th>
                                            <th style="min-width: 180px;">Submission Date</th>
                                            <th>Document</th>
                                            <th style="min-width: 180px;">Created By</th>
                                            <th style="min-width: 180px;">Created Date</th>
                                            <th style="min-width: 200px;">Compteur</th>
                                            <th style="min-width: 300px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($getRecord as $value)
                                            <tr>
                                                <td class="text-center">{{ $value->id }}</td>
                                                <td>{{ $value->class_name }} {{ $value->class_opt }}</td>
                                                <td>{{ $value->subject_name }}</td>
                                                <td>{{ date('d-m-Y', strtotime($value->homework_date)) }}</td>
                                                <td>{{ date('d-m-Y', strtotime($value->submission_date)) }}</td>
                                                <td>
                                                    @if (!empty($value->getDocument()))
                                                        <a href="{{ $value->getDocument() }}"
                                                            class="btn btn-primary btn-sm" download>
                                                            <i class="fas fa-download"></i> Download
                                                        </a>
                                                    @endif
                                                </td>
                                                <td>{{ $value->created_by_name }}</td>
                                                <td>{{ date('d-m-Y', strtotime($value->created_at)) }}</td>
                                                @php
                                                    $now = \Carbon\Carbon::now();
                                                    $submission = \Carbon\Carbon::parse(
                                                        $value->submission_date,
                                                    )->endOfDay();
                                                    $diffInSeconds = $now->diffInSeconds($submission, false);
                                                    if ($diffInSeconds > 0) {
                                                        $days = floor($diffInSeconds / 86400);
                                                        $hours = floor(($diffInSeconds % 86400) / 3600);
                                                        $minutes = floor(($diffInSeconds % 3600) / 60);
                                                    } else {
                                                        $days = $hours = $minutes = 0;
                                                    }
                                                @endphp
                                                <td>
                                                    @if ($diffInSeconds <= 0)
                                                        <span class="text-danger fw-bold">Terminé</span>
                                                    @else
                                                        <span class="text-success fw-bold">
                                                            {{ $days }} jour(s)
                                                            {{ $hours }} heure(s)
                                                            {{ $minutes }} minute(s) restant(s)
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ url('teacher/homework/homework/edit/' . $value->id) }}"
                                                        class="btn btn-primary btn-sm mb-1">
                                                        <i class="fas fa-pencil-alt"></i> Edit
                                                    </a>
                                                    <a href="{{ url('teacher/homework/homework/delete/' . $value->id) }}"
                                                        class="btn btn-danger btn-sm mb-1"
                                                        onclick="return confirm('Are you sure?')">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </a>
                                                    <a href="{{ url('teacher/homework/homework/submitted/' . $value->id) }}"
                                                        class="btn btn-success btn-sm mb-1">
                                                        <i class="fas fa-download"></i> Submitted Homework
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center text-muted py-4">
                                                    <i class="fa-solid fa-folder-open fa-2x mb-2"></i><br>
                                                    Record not found
                                                </td>
                                            </tr>
                                        @endforelse
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
            vertical-align: middle;
        }

        .table td,
        .table th {
            vertical-align: middle;
            font-size: 0.96rem;
        }

        .styled-table tbody tr:hover {
            background-color: #e9f0ff;
            transition: background-color 0.3s ease;
        }

        .btn-info,
        .btn-danger,
        .btn-success,
        .btn-primary {
            font-weight: 500;
        }

        .btn-sm {
            padding: 0.25rem 0.75rem;
            font-size: 0.9rem;
        }
    </style>
@endsection

@section('script')
    .
    <script type="text/javascript">
        document.getElementById('downloadBtn').addEventListener('click', function() {
            const button = this;
            const icon = button.querySelector('i');

            // Simuler le téléchargement (changer l'icône après clic)
            setTimeout(() => {
                icon.classList.remove('fa-download'); // Supprimer l'icône de téléchargement
                icon.classList.add('fa-check-circle'); // Ajouter l'icône de confirmation
                button.innerHTML = '<i class="fas fa-check-circle"></i> Downloaded'; // Changer le texte
                button.style.backgroundColor = '#28a745'; // Changer la couleur du bouton (vert)
            }, 1000); // Simule un temps de téléchargement de 1 seconde
        });
    </script>
@endsection
