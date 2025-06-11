@extends('layouts.app')

@section('content')
    <div class="content-wrapper py-4" style="background: #f8f9fc; min-height: 100vh;">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-7 col-md-9">
                    <div class="card shadow border-0 mb-4">
                        <div class="card-header bg-gradient-primary py-3 d-flex align-items-center">
                            <i class="fas fa-user-plus fa-lg text-white me-2"></i>
                            <h5 class="m-0 font-weight-bold text-white">{{ $header_title }}</h5>
                        </div>
                        <div class="card-body bg-white">
                            <form action="{{ route('admin.student.import.submit') }}" method="POST"
                                enctype="multipart/form-data" class="p-2">
                                @csrf
                                <div class="form-group mb-4">
                                    <label class="form-label fw-semibold" for="file">
                                        <i class="fas fa-file-excel text-success me-2"></i>
                                        Télécharger le fichier Excel
                                    </label>
                                    <input type="file" name="file" id="file" class="form-control"
                                        accept=".xlsx,.xls,.csv" required>
                                    <small class="form-text text-muted">
                                        Formats acceptés : <span class="fw-bold">.xlsx, .xls, .csv</span><br>
                                        <a href="{{ asset('sample/student_import_sample.xlsx') }}" class="text-primary"
                                            download>
                                            <i class="fas fa-download me-1"></i> Télécharger le modèle
                                        </a>
                                    </small>
                                </div>
                                <button type="submit" class="btn btn-success px-4 py-2">
                                    <i class="fas fa-file-import me-2"></i> Importer
                                </button>
                            </form>
                        </div>
                    </div>
                    @if (session('success'))
                        <div class="alert alert-success mt-3">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger mt-3">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
