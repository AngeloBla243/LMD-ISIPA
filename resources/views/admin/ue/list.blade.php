@extends('layouts.app')

@section('content')
    <div class="content-wrapper py-4" style="background-color: #f8f9fc; min-height: 100vh;">
        <section class="content-header mb-4">
            <h1 class="h3 text-primary fw-bold">{{ $header_title }}</h1>
        </section>

        <section class="content">
            <div class="card shadow-sm border-0">
                <div
                    class="card-header d-flex justify-content-between align-items-center bg-gradient-primary text-white py-3">
                    <span class="fw-semibold fs-5">Liste des Unités d'Enseignement (UE)</span>
                    <a href="{{ route('admin.ue.add') }}" class="btn btn-light btn-sm d-flex align-items-center gap-1">
                        <i class="fas fa-plus"></i> Ajouter une UE
                    </a>
                </div>

                <div class="card-body bg-white">
                    @include('_message')

                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <thead class="table-primary text-uppercase small">
                                <tr>
                                    <th scope="col" style="width: 15%;">Code</th>
                                    <th scope="col" style="width: 40%;">Nom</th>
                                    <th scope="col" style="width: 15%;">Crédits</th>
                                    <th scope="col" style="width: 20%;">Année académique</th>
                                    <th scope="col" style="width: 10%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($getRecord as $ue)
                                    <tr>
                                        <td class="fw-bold text-primary">{{ $ue->code }}</td>
                                        <td>{{ $ue->name }}</td>
                                        <td>{{ $ue->credits }}</td>
                                        <td>{{ $ue->academicYear->name ?? 'N/A' }}</td>
                                        <td>
                                            <a href="{{ route('admin.ue.edit', $ue->id) }}"
                                                class="btn btn-sm btn-warning me-1" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.ue.delete', $ue->id) }}" method="POST"
                                                class="d-inline" onsubmit="return confirm('Supprimer cette UE ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted fst-italic py-4">Aucune UE trouvée
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
