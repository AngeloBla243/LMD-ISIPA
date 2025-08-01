@extends('layouts.app')

@section('content')
    <div class="content-wrapper py-4">
        <div class="container">
            <h2>Créer un Frais</h2>

            <form action="{{ route('admin.fee_types.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Intitulé</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Montant</label>
                    <input type="number" step="0.01" name="amount" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Date début</label>
                    <input type="date" name="start_date" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Date fin/limite</label>
                    <input type="date" name="end_date" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Classes concernées</label>
                    <select name="class_ids[]" class="form-control" multiple required>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                    <small>Ctrl+Click pour sélectionner plusieurs classes</small>
                </div>
                <button type="submit" class="btn btn-success">Enregistrer</button>
            </form>
        </div>
    </div>
@endsection
