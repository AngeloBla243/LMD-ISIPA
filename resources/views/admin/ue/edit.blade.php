@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>{{ $header_title }}</h1>
        </section>
        <section class="content">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.ue.update', $ue->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label>Code UE <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control" required
                                value="{{ old('code', $ue->code) }}">
                        </div>
                        <div class="form-group">
                            <label>Nom de l'UE <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required
                                value="{{ old('name', $ue->name) }}">
                        </div>
                        <div class="form-group">
                            <label>Crédits ECTS <span class="text-danger">*</span></label>
                            <input type="number" name="credits" class="form-control" required min="1"
                                value="{{ old('credits', $ue->credits) }}">
                        </div>
                        <div class="form-group">
                            <label>Note minimale de validation (0-20) <span class="text-danger">*</span></label>
                            <input type="number" name="min_passing_mark" step="0.01" class="form-control" required
                                min="0" max="20" value="{{ old('min_passing_mark', $ue->min_passing_mark) }}">
                        </div>
                        <div class="form-group">
                            <label>Seuil de compensation (0-20)</label>
                            <input type="number" name="compensation_threshold" step="0.01" class="form-control"
                                min="0" max="19.99"
                                value="{{ old('compensation_threshold', $ue->compensation_threshold) }}">
                            <small class="text-muted">Ex: 8.50/20</small>
                        </div>
                        <div class="form-group">
                            <label>Échelle de notation <span class="text-danger">*</span></label>
                            <select name="grade_scale" class="form-control" required>
                                <option value="LMD"
                                    {{ old('grade_scale', $ue->grade_scale) == 'LMD' ? 'selected' : '' }}>Système LMD
                                </option>
                                <option value="OTHER"
                                    {{ old('grade_scale', $ue->grade_scale) == 'OTHER' ? 'selected' : '' }}>Autre système
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Année académique <span class="text-danger">*</span></label>
                            <select name="academic_year_id" class="form-control" required>
                                <option value="">Sélectionner</option>
                                @foreach ($academicYears as $year)
                                    <option value="{{ $year->id }}"
                                        {{ old('academic_year_id', $ue->academic_year_id) == $year->id ? 'selected' : '' }}>
                                        {{ $year->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">Mettre à jour</button>
                        <a href="{{ route('admin.ue.list') }}" class="btn btn-secondary">Retour</a>
                    </form>

                </div>
            </div>
        </section>
    </div>
@endsection
