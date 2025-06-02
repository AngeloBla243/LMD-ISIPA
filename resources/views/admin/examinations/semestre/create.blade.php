@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Créer un Semestre</h1>
        </section>
        <section class="content">
            <form method="POST" action="{{ route('admin.semester.create') }}">
                @csrf
                <div class="form-group">
                    <label>Nom du Semestre <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" required placeholder="Ex: S1-2024">
                </div>
                <div class="form-group">
                    <label>Année académique <span class="text-danger">*</span></label>
                    <select name="academic_year_id" class="form-control" required>
                        @foreach ($academicYears as $year)
                            <option value="{{ $year->id }}">{{ $year->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Créer le semestre</button>
            </form>


        </section>
    </div>
@endsection
