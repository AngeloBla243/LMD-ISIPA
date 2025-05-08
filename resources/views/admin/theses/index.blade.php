@extends('layouts.app')

@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Soumissions de mémoires</h1>
                        {{-- @if ($activeYear)
                            <h2>Année active : {{ $activeYear->name }}</h2>
                        @else
                            <h2>Aucune année active sélectionnée</h2>
                        @endif --}}

                    </div>

                </div>
            </div><!-- /.container-fluid -->

            <form method="GET" action="" class="mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control"
                            placeholder="Recherche étudiant, classe ou mémoire" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Rechercher</button>
                    </div>
                </div>
            </form>

            {{-- <div class="form-group">
                <label for="academic_year_id">Année Académique</label>
                <select name="academic_year_id" id="academic_year_id" class="form-control">
                    <option value="">Sélectionner une année</option>
                    @foreach ($academicYears as $year)
                        <option value="{{ $year->id }}"
                            {{ isset($thesis) && $thesis->academic_year_id == $year->id ? 'selected' : '' }}>
                            {{ $year->name }}</option>
                    @endforeach
                </select>
            </div> --}}


        </section>


        <section class="content">
            <div class="card-body p-0" style="overflow: auto;">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Étudiant</th>
                            <th>Classe</th>
                            <th>Sujet</th>
                            <th>Taux plagiat</th>
                            <th>Statut</th>
                            <th>Date de soumission</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $start = ($submissions->currentPage() - 1) * $submissions->perPage() + 1;
                        @endphp
                        @forelse($submissions as $i => $submission)
                            <tr>
                                <td>{{ $start + $i }}</td>
                                <td>{{ $submission->student->name ?? 'N/A' }} {{ $submission->student->last_name ?? '' }}
                                </td>
                                <td>{{ $submission->student->class->name ?? 'N/A' }}</td>
                                <td>{{ $submission->subject }}</td>
                                <td>{{ $submission->plagiarism_rate }}%</td>
                                {{-- <td>{{ ucfirst($submission->status ?? 'pending') }}</td> --}}
                                <td>
                                    <span
                                        class="badge
                                        {{ $submission->status === 'accepted' ? 'badge-success' : '' }}
                                        {{ $submission->status === 'rejected' ? 'badge-danger' : '' }}
                                        {{ $submission->status === 'pending' ? 'badge-warning' : '' }}">
                                        {{ ucfirst($submission->status ?? 'pending') }}
                                    </span>
                                </td>

                                <td>{{ $submission->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.theses.show', $submission->id) }}"
                                        class="btn btn-sm btn-info">Voir</a>
                                    {{-- <form action="{{ route('admin.theses.destroy', $submission->id) }}" method="POST"
                                    style="display:inline" onsubmit="return confirm('Confirmer la suppression ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit">Supprimer</button>
                                </form> --}}

                                    <button type="button" class="btn btn-sm btn-danger delete-btn"
                                        data-id="{{ $submission->id }}"
                                        data-nom="{{ $submission->student->name ?? '' }} {{ $submission->student->last_name ?? '' }}">
                                        Supprimer
                                    </button>
                                    <form id="delete-form-{{ $submission->id }}"
                                        action="{{ route('admin.theses.destroy', $submission->id) }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Aucune soumission trouvée.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $submissions->links() }}
        </section>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-btn').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const memoId = btn.getAttribute('data-id');
                    const nom = btn.getAttribute('data-nom');
                    Swal.fire({
                        title: 'Supprimer ?',
                        text: 'Voulez-vous vraiment supprimer ce mémoire de ' + nom + ' ?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Oui, supprimer',
                        cancelButtonText: 'Annuler'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('delete-form-' + memoId).submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
