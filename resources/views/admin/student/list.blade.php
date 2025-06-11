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
                            Liste des étudiants <small class="text-muted">(Total : {{ $getRecord->total() }})</small>
                        </h1>
                    </div>
                    {{-- <div class="col-sm-6 text-end">
                        <a href="{{ url('admin/student/add') }}" class="btn btn-info shadow-sm rounded-3">
                            <i class="fa-solid fa-file-circle-plus me-2"></i> Ajouter un étudiant
                        </a>
                    </div> --}}

                    <div class="col-sm-6 text-end">
                        <a href="{{ url('admin/student/add') }}" class="btn btn-info shadow-sm rounded-3">
                            <i class="fa-solid fa-file-circle-plus me-2"></i> Ajouter un étudiant
                        </a>
                        <a href="{{ url('admin/student/import') }}" class="btn btn-success shadow-sm rounded-3 ms-2">
                            <i class="fa-solid fa-file-import me-2"></i> Importer via Excel
                        </a>
                    </div>


                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content pb-5">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">

                        <!-- Recherche -->
                        <div class="card shadow-sm rounded-4 border-0 mb-4">
                            <div class="card-header bg-primary text-white rounded-top-4">
                                <h3 class="card-title mb-0">
                                    <i class="fa-solid fa-magnifying-glass me-2"></i>Recherche Étudiant
                                </h3>
                            </div>
                            <form method="get" action="">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">Nom</label>
                                            <input type="text" class="form-control" value="{{ Request::get('name') }}"
                                                name="name" placeholder="Nom">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">Prénom</label>
                                            <input type="text" class="form-control"
                                                value="{{ Request::get('last_name') }}" name="last_name"
                                                placeholder="Prénom">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">Email</label>
                                            <input type="text" class="form-control" name="email"
                                                value="{{ Request::get('email') }}" placeholder="Email">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">N° Admission</label>
                                            <input type="text" class="form-control" name="admission_number"
                                                value="{{ Request::get('admission_number') }}" placeholder="N° Admission">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">N° Appel</label>
                                            <input type="text" class="form-control" name="roll_number"
                                                value="{{ Request::get('roll_number') }}" placeholder="N° Appel">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">Classe</label>
                                            <input type="text" class="form-control" name="class"
                                                value="{{ Request::get('class') }}" placeholder="Classe">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">Genre</label>
                                            <select class="form-control" name="gender">
                                                <option value="">Genre</option>
                                                <option {{ Request::get('gender') == 'Male' ? 'selected' : '' }}
                                                    value="Male">Homme</option>
                                                <option {{ Request::get('gender') == 'Female' ? 'selected' : '' }}
                                                    value="Female">Femme</option>
                                                <option {{ Request::get('gender') == 'Other' ? 'selected' : '' }}
                                                    value="Other">Autre</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">Département</label>
                                            <select class="form-control" name="departement">
                                                <option value="">Département</option>
                                                <option
                                                    {{ Request::get('departement') == 'Informatique de gestion' ? 'selected' : '' }}
                                                    value="Informatique de gestion">Informatique de gestion</option>
                                                <option
                                                    {{ Request::get('departement') == 'Techniques de Maintenance' ? 'selected' : '' }}
                                                    value="Techniques de Maintenance">Techniques de Maintenance</option>
                                                <option
                                                    {{ Request::get('departement') == 'Communication numérique' ? 'selected' : '' }}
                                                    value="Communication numérique">Communication numérique</option>
                                                <option
                                                    {{ Request::get('departement') == 'Gestion financière' ? 'selected' : '' }}
                                                    value="Gestion financière">Gestion financière</option>
                                                <option
                                                    {{ Request::get('departement') == 'Gestion Douanière et Accises' ? 'selected' : '' }}
                                                    value="Gestion Douanière et Accises">Gestion Douanière et Accises
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">Religion</label>
                                            <input type="text" class="form-control" name="religion"
                                                value="{{ Request::get('religion') }}" placeholder="Religion">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">Téléphone</label>
                                            <input type="text" class="form-control" name="mobile_number"
                                                value="{{ Request::get('mobile_number') }}" placeholder="Téléphone">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">Groupe Sanguin</label>
                                            <input type="text" class="form-control" name="blood_group"
                                                value="{{ Request::get('blood_group') }}" placeholder="Groupe sanguin">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">Statut</label>
                                            <select class="form-control" name="status">
                                                <option value="">Statut</option>
                                                <option {{ Request::get('status') == 100 ? 'selected' : '' }}
                                                    value="100">Actif</option>
                                                <option {{ Request::get('status') == 1 ? 'selected' : '' }}
                                                    value="1">Inactif</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">Date d'admission</label>
                                            <input type="date" class="form-control" name="admission_date"
                                                value="{{ Request::get('admission_date') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">Date création</label>
                                            <input type="date" class="form-control" name="date"
                                                value="{{ Request::get('date') }}">
                                        </div>
                                        <div class="col-md-3 d-flex align-items-end gap-2">
                                            <button class="btn btn-primary w-100" type="submit">
                                                <i class="fa-solid fa-magnifying-glass me-1"></i> Rechercher
                                            </button>
                                            <a href="{{ url('admin/student/list') }}"
                                                class="btn btn-secondary w-100">Réinitialiser</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        @include('_message')

                        <!-- Liste des étudiants -->
                        <div class="card shadow-sm rounded-4 border-0">
                            <div
                                class="card-header bg-primary text-white rounded-top-4 d-flex justify-content-between align-items-center">
                                <h3 class="card-title mb-0">
                                    <i class="fa-solid fa-users me-2"></i>Liste des étudiants
                                </h3>
                                <form action="{{ url('admin/student/export_excel') }}" method="post"
                                    class="d-inline-block ms-auto">
                                    {{ csrf_field() }}
                                    @foreach (['name', 'last_name', 'email', 'departement', 'admission_number', 'roll_number', 'gender', 'class', 'caste', 'religion', 'mobile_number', 'blood_group', 'status', 'admission_date', 'date'] as $field)
                                        <input type="hidden" name="{{ $field }}"
                                            value="{{ Request::get($field) }}">
                                    @endforeach
                                    <button class="btn btn-success">
                                        <i class="fa-solid fa-file-excel me-1"></i> Export Excel
                                    </button>
                                </form>
                            </div>
                            <div class="card-body p-0 table-responsive">
                                <table class="table table-hover table-bordered align-middle mb-0">
                                    <thead class="table-primary text-center text-uppercase small">
                                        <tr>
                                            <th>#</th>
                                            <th>Photo</th>
                                            <th>Nom</th>
                                            <th>Département</th>
                                            <th>Parent</th>
                                            <th>Email</th>
                                            <th>N° Admission</th>
                                            <th>N° Appel</th>
                                            <th>Classe</th>
                                            <th>Genre</th>
                                            <th>Date de naissance</th>
                                            <th>Caste</th>
                                            <th>Religion</th>
                                            <th>Téléphone</th>
                                            <th>Date Admission</th>
                                            <th>Groupe Sanguin</th>
                                            <th>Taille</th>
                                            <th>Poids</th>
                                            <th>Statut</th>
                                            <th>Date création</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($getRecord as $value)
                                            <tr>
                                                <td class="text-center">
                                                    {{ ($getRecord->currentPage() - 1) * $getRecord->perPage() + $loop->iteration }}
                                                </td>
                                                <td class="text-center" style="min-width: 200px;">
                                                    @if (!empty($value->getProfileDirect()))
                                                        <img src="{{ $value->getProfileDirect() }}" alt="Photo"
                                                            class="rounded-circle"
                                                            style="width: 50px; height: 50px; object-fit: cover;">
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>
                                                <td style="min-width: 200px;">{{ $value->name }} {{ $value->last_name }}
                                                </td>
                                                <td style="min-width: 200px;">{{ $value->departement }}</td>
                                                <td style="min-width: 200px;">{{ $value->parent_name }}</td>
                                                <td style="min-width: 200px;">{{ $value->email }}</td>
                                                <td style="min-width: 200px;">{{ $value->admission_number }}</td>
                                                <td style="min-width: 200px;">{{ $value->roll_number }}</td>
                                                {{-- <td>{{ $value->class_name }}</td> --}}
                                                </td>
                                                <td style="min-width: 400px;">
                                                    @if ($value->studentClasses->count() == 0)
                                                        <span class="text-muted">Aucune classe assignée</span>
                                                    @else
                                                        @foreach ($value->studentClasses as $class)
                                                            {{ $class->name }}
                                                            @if ($class->opt)
                                                                - {{ $class->opt }}
                                                            @endif
                                                            @if ($class->academicYear)
                                                                ({{ $class->academicYear->name }})
                                                            @endif
                                                            @if (!$loop->last)
                                                                ,
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td style="min-width: 200px;">{{ $value->gender }}</td>
                                                <td style="min-width: 200px;">
                                                    @if (!empty($value->date_of_birth))
                                                        {{ date('d-m-Y', strtotime($value->date_of_birth)) }}
                                                    @endif
                                                </td>
                                                <td style="min-width: 200px;">{{ $value->caste }}</td>
                                                <td style="min-width: 200px;">{{ $value->religion }}</td>
                                                <td style="min-width: 200px;">{{ $value->mobile_number }}</td>
                                                <td style="min-width: 200px;">
                                                    @if (!empty($value->admission_date))
                                                        {{ date('d-m-Y', strtotime($value->admission_date)) }}
                                                    @endif
                                                </td>
                                                <td style="min-width: 200px;">{{ $value->blood_group }}</td>
                                                <td>{{ $value->height }}</td>
                                                <td>{{ $value->weight }}</td>
                                                <td style="min-width: 200px;">
                                                    <span
                                                        class="badge {{ $value->status == 0 ? 'bg-success' : 'bg-secondary' }}">
                                                        {{ $value->status == 0 ? 'Actif' : 'Inactif' }}
                                                    </span>
                                                </td>
                                                <td style="min-width: 200px;">
                                                    {{ date('d-m-Y H:i A', strtotime($value->created_at)) }}</td>
                                                <td style="min-width: 200px;">
                                                    <a href="{{ url('admin/student/edit/' . $value->id) }}"
                                                        class="btn btn-info btn-sm me-1 mb-1" title="Modifier">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                    <a href="{{ url('admin/student/delete/' . $value->id) }}"
                                                        class="btn btn-danger btn-sm me-1 mb-1" title="Supprimer"
                                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?');">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                    <a href="{{ url('chat?receiver_id=' . base64_encode($value->id)) }}"
                                                        class="btn btn-success btn-sm mb-1" title="Envoyer un message">
                                                        <i class="fas fa-comments"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="21" class="text-center text-muted py-4">
                                                    <i class="fa-solid fa-face-frown-open fa-2x mb-2"></i><br>
                                                    Aucun étudiant trouvé.
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
        .btn-primary,
        .btn-secondary {
            font-weight: 500;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .badge {
            font-size: 1em;
            padding: 0.45em 0.9em;
        }

        .fa-face-frown-open {
            color: #adb5bd;
        }

        .form-label {
            font-weight: 600;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, .15);
        }
    </style>
@endsection
