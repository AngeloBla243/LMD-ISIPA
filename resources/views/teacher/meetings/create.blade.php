@extends('layouts.app')

@section('content')
    <div class="content-wrapper py-4" style="background: #f8f9fc; min-height: 100vh;">
        <div class="container">
            <h2>{{ $header_title }}</h2>

            <div class="card shadow">
                <div class="card-body">
                    <form method="POST" action="{{ route('teacher.meetings.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="class_id" class="form-label">Classe</label>
                            <select name="class_id" id="class_id" class="form-select" required>
                                <option value="" disabled selected>-- Sélectionnez une classe --</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }} {{ $class->opt }}</option>
                                @endforeach
                            </select>
                            @error('class_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="topic" class="form-label">Sujet de la réunion</label>
                            <input type="text" name="topic" id="topic" class="form-control"
                                value="{{ old('topic') }}" required>
                            @error('topic')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="start_time" class="form-label">Date et heure</label>
                            <input type="datetime-local" name="start_time" id="start_time" class="form-control"
                                value="{{ old('start_time') }}" required>
                            @error('start_time')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="duration" class="form-label">Durée (minutes)</label>
                            <input type="number" name="duration" id="duration" class="form-control" min="15"
                                value="{{ old('duration', 60) }}" required>
                            @error('duration')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="agenda" class="form-label">Agenda (optionnel)</label>
                            <textarea name="agenda" id="agenda" class="form-control">{{ old('agenda') }}</textarea>
                            @error('agenda')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Créer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
