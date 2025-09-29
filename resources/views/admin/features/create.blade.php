@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <h1>Ajouter une nouvelle fonctionnalité</h1>

        <form method="POST" action="{{ route('admin.features.store') }}">
            @csrf

            <div class="mb-3">
                <label for="feature_name" class="form-label">Nom de la fonctionnalité</label>
                <input type="text" name="feature_name" id="feature_name" class="form-control"
                    value="{{ old('feature_name') }}" required>
            </div>

            <div class="mb-3">
                <label for="enabled" class="form-label">Activée</label>
                <select name="enabled" id="enabled" class="form-control">
                    <option value="1">Oui</option>
                    <option value="0">Non</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="start_date" class="form-label">Date de début</label>
                <input type="date" name="start_date" id="start_date" class="form-control"
                    value="{{ old('start_date') }}">
            </div>

            <div class="mb-3">
                <label for="end_date" class="form-label">Date de fin</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date') }}">
            </div>

            <button type="submit" class="btn btn-success">Enregistrer</button>
            <a href="{{ route('admin.features.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
@endsection
