@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <div class="container">
            <h1>Types de frais</h1>
            <a href="{{ route('admin.fee_types.create') }}" class="btn btn-primary mb-3">Ajouter un type de frais</a>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Frais</th>
                        <th>Montant</th>
                        <th>Période</th>
                        <th>Classes concernées</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fee_types as $fee)
                        <tr>
                            <td>{{ $fee->name }}</td>
                            <td>{{ number_format($fee->amount, 2) }} F CFA</td>
                            <td>
                                {{ $fee->start_date }} au {{ $fee->end_date }}
                            </td>
                            <td>
                                @foreach ($fee->classes as $class)
                                    <span class="badge bg-info">{{ $class->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                <a href="{{ route('admin.fee_types.edit', $fee->id) }}"
                                    class="btn btn-warning btn-sm">Éditer</a>
                                <!-- bouton suppression etc -->
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">Aucun type de frais trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $fee_types->links() }}
        </div>
    </div>
@endsection
