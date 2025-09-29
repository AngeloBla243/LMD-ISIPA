@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <h1>Modifier la fonctionnalité : {{ $feature->feature_name }}</h1>

        <form method="POST" action="{{ route('admin.features.update', $feature->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="enabled" class="form-label">Activée</label>
                <select name="enabled" id="enabled" class="form-control">
                    <option value="1" {{ $feature->enabled ? 'selected' : '' }}>Oui</option>
                    <option value="0" {{ !$feature->enabled ? 'selected' : '' }}>Non</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="start_date" class="form-label">Date de début</label>
                <input type="date" name="start_date" id="start_date" value="{{ $feature->start_date }}"
                    class="form-control" />
            </div>

            <div class="mb-3">
                <label for="end_date" class="form-label">Date de fin</label>
                <input type="date" name="end_date" id="end_date" value="{{ $feature->end_date }}"
                    class="form-control" />
            </div>

            <button type="submit" class="btn btn-success">Enregistrer</button>
            <a href="{{ route('admin.features.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
@endsection
