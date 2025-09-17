@extends('layouts.app')

@section('content')
    <div class="content-header">
        <h1>{{ $header_title ?? 'Ajouter un département' }}</h1>
    </div>

    <div class="card card-primary">
        <form method="POST" action="{{ route('admin.departementNmae.insert') }}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="name">Nom du département</label>
                    <input type="text" name="name" id="name"
                        class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Si besoin d'autres champs (email, password etc.) ajouter ici --}}
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <a href="{{ route('admin.departementName.list') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
@endsection
