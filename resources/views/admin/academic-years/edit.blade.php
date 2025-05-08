@extends('layouts.app')

@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ isset($academicYear) ? 'Modifier' : 'Ajouter' }} une année académique</h1>
                    </div>

                </div>
            </div><!-- /.container-fluid -->
        </section>
        <section class="content">
            <form
                action="{{ isset($academicYear) ? route('admin.academic-years.update', $academicYear) : route('admin.academic-years.store') }}"
                method="POST">
                @csrf
                @if (isset($academicYear))
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label for="name" class="form-label">Nom (ex: 2024-2025)</label>
                    <input type="text" class="form-control" id="name" name="name"
                        value="{{ $academicYear->name ?? '' }}" required>
                </div>

                <div class="mb-3">
                    <label for="start_date" class="form-label">Date de début</label>
                    <input type="date" class="form-control" id="start_date" name="start_date"
                        value="{{ $academicYear->start_date ?? '' }}" required>
                </div>

                <div class="mb-3">
                    <label for="end_date" class="form-label">Date de fin</label>
                    <input type="date" class="form-control" id="end_date" name="end_date"
                        value="{{ $academicYear->end_date ?? '' }}" required>
                </div>

                <div class="mb-3">
                    <label for="is_active" class="form-label">Année active ?</label>
                    <select name="is_active" id="is_active" class="form-control">
                        <option value="1" {{ $academicYear->is_active ? 'selected' : '' }}>Oui</option>
                        <option value="0" {{ !$academicYear->is_active ? 'selected' : '' }}>Non</option>
                    </select>
                </div>


                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <a href="{{ route('admin.academic-years.index') }}" class="btn btn-secondary">Annuler</a>
            </form>
        </section>
    </div>
@endsection
