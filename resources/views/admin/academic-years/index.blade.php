@extends('layouts.app')

@section('content')
    <div class="content-wrapper">

        <section class="content-header">


            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Années Académiques</h1>
                        </div>
                        <div class="col-sm-6" style="text-align: right;">
                            <a href="{{ route('admin.academic-years.create') }}" class="btn btn-primary">Ajouter une année</a>
                        </div>



                    </div>
                </div><!-- /.container-fluid -->
            </section>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Début</th>
                        <th>Fin</th>
                        <th>Active</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($years as $year)
                        <tr>
                            <td>{{ $year->name }}</td>
                            <td>{{ $year->start_date->format('d/m/Y') }}</td>
                            <td>{{ $year->end_date->format('d/m/Y') }}</td>
                            <td>
                                @if ($year->is_active)
                                    <span class="badge bg-success">Oui</span>
                                @else
                                    <a href="{{ route('admin.academic-years.set-active', $year) }}"
                                        class="btn btn-sm btn-outline-secondary">Définir active</a>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.academic-years.edit', $year) }}"
                                    class="btn btn-sm btn-warning">Modifier</a>
                                <form action="{{ route('admin.academic-years.destroy', $year) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Confirmer la suppression ?')">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </div>
@endsection
