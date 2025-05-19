@extends('layouts.app')
@section('style')
    {{-- <style type="text/css">
.styled-table {
    border-collapse: collapse;
    margin: 25px 0;
    min-width: 400px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
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
</style> --}}
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h1 class="h3 fw-bold text-primary">
                            Liste des Admins <small class="text-muted">(Total : {{ $getRecord->total() }})</small>
                        </h1>
                    </div>
                    <div class="col-sm-6 text-end">
                        <a href="{{ url('admin/admin/add') }}" class="btn btn-info shadow-sm rounded-3">
                            <i class="fa-solid fa-file-circle-plus me-2"></i> Ajouter un nouvel admin
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content pb-5">
            <div class="container-fluid">

                <!-- Recherche -->
                <div class="card shadow-sm rounded-4 border-0 mb-4">
                    <div class="card-header bg-primary text-white rounded-top-4">
                        <h3 class="card-title mb-0">
                            <i class="fa-solid fa-magnifying-glass me-2"></i>Recherche Admin
                        </h3>
                    </div>
                    <form method="get" action="">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label for="name" class="form-label fw-semibold">Nom</label>
                                    <input type="text" id="name" name="name" class="form-control"
                                        value="{{ Request::get('name') }}" placeholder="Nom">
                                </div>
                                <div class="col-md-3">
                                    <label for="email" class="form-label fw-semibold">Email</label>
                                    <input type="email" id="email" name="email" class="form-control"
                                        value="{{ Request::get('email') }}" placeholder="Email">
                                </div>
                                <div class="col-md-3">
                                    <label for="date" class="form-label fw-semibold">Date</label>
                                    <input type="date" id="date" name="date" class="form-control"
                                        value="{{ Request::get('date') }}">
                                </div>
                                <div class="col-md-3 d-flex align-items-end gap-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fa-solid fa-magnifying-glass me-1"></i> Rechercher
                                    </button>
                                    <a href="{{ url('admin/admin/list') }}" class="btn btn-secondary w-100">
                                        Réinitialiser
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                @include('_message')

                <!-- Liste des Admins -->
                <div class="card shadow-sm rounded-4 border-0">
                    <div class="card-header bg-primary text-white rounded-top-4">
                        <h3 class="card-title mb-0">
                            <i class="fa-solid fa-users-gear me-2"></i>Liste des Admins
                        </h3>
                    </div>
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-hover table-bordered align-middle mb-0">
                            <thead class="table-primary text-center text-uppercase small">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th style="width: 80px;">Photo</th>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th style="min-width: 150px;">Date de création</th>
                                    <th style="min-width: 300px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($getRecord as $index => $value)
                                    <tr>
                                        <td class="text-center">
                                            {{ ($getRecord->currentPage() - 1) * $getRecord->perPage() + $loop->iteration }}
                                        </td>
                                        <td class="text-center">
                                            @if (!empty($value->getProfileDirect()))
                                                <img src="{{ $value->getProfileDirect() }}" alt="Profil"
                                                    class="rounded-circle"
                                                    style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>{{ $value->name }}</td>
                                        <td>{{ $value->email }}</td>
                                        <td class="text-center">{{ date('d-m-Y H:i A', strtotime($value->created_at)) }}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ url('admin/admin/edit/' . $value->id) }}"
                                                class="btn btn-info btn-sm me-1 mb-1" title="Modifier">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            <a href="{{ url('admin/admin/delete/' . $value->id) }}"
                                                class="btn btn-danger btn-sm me-1 mb-1" title="Supprimer"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet admin ?');">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            <a href="{{ url('chat?receiver_id=' . base64_encode($value->id)) }}"
                                                class="btn btn-success btn-sm mb-1" title="Envoyer un message">
                                                <i class="fas fa-comments"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($getRecord->isEmpty())
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="fa-solid fa-face-frown-open fa-2x mb-2"></i><br>
                                            Aucun admin trouvé.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    @if ($getRecord instanceof \Illuminate\Pagination\AbstractPaginator && $getRecord->hasPages())
                        <div class="mt-3 d-flex justify-content-end px-3">
                            {!! $getRecord->appends(Illuminate\Support\Facades\Request::except('page'))->links() !!}
                        </div>
                    @endif
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
        .btn-success {
            font-weight: 500;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    </style>
@endsection
