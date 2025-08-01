@extends('layouts.app')

@section('style')
    <style>
        /* Conteneur principal */
        .content-wrapper {
            background: #f8f9fc;
            min-height: 100vh;
            padding: 2.5rem 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            color: #222;
        }

        .container {
            max-width: 600px;
        }

        h2 {
            color: #2176bd;
            font-weight: 700;
            margin-bottom: 2rem;
            font-size: 2rem;
            text-align: center;
        }

        /* Card styling */
        .card {
            border-radius: 18px;
            box-shadow: 0 8px 24px rgba(33, 118, 189, 0.1);
            border: none;
            background: #fff;
            padding: 2rem 2.5rem;
        }

        /* Form groups */
        .mb-3 {
            margin-bottom: 1.3rem;
        }

        label.form-label {
            font-weight: 600;
            color: #2176bd;
            display: block;
            margin-bottom: 0.5rem;
            font-size: 1.05rem;
        }

        /* Inputs and Select */
        .form-control,
        .form-select,
        textarea.form-control {
            border: 1.8px solid #cbd5e1;
            border-radius: 12px;
            padding: 0.65rem 1rem;
            font-size: 1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            width: 100%;
            font-family: inherit;
            color: #222;
            background-color: #fff;
            resize: vertical;
        }

        .form-control:focus,
        .form-select:focus,
        textarea.form-control:focus {
            outline: none;
            border-color: #2176bd;
            box-shadow: 0 0 6px rgba(33, 118, 189, 0.45);
            background-color: #fff;
            color: #222;
        }

        /* Error messages */
        .text-danger {
            color: #dc3545 !important;
            font-weight: 600;
            margin-top: 0.25rem;
            font-size: 0.9rem;
        }

        /* Button styling */
        button.btn-primary {
            background-color: #2176bd;
            border-color: #2176bd;
            border-radius: 24px;
            font-weight: 700;
            font-size: 1.1rem;
            padding: 0.65rem 1.8rem;
            width: 100%;
            transition: background-color 0.25s ease, box-shadow 0.25s ease;
            cursor: pointer;
        }

        button.btn-primary:hover,
        button.btn-primary:focus {
            background-color: #195a98;
            border-color: #195a98;
            box-shadow: 0 6px 15px rgba(25, 90, 152, 0.7);
            color: #fff;
            outline: none;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .container {
                padding: 0 1rem;
                max-width: 100%;
            }

            .card {
                padding: 1.5rem 1.5rem;
            }

            h2 {
                font-size: 1.75rem;
                margin-bottom: 1.5rem;
            }

            button.btn-primary {
                font-size: 1rem;
                padding: 0.55rem 1.2rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="container">
            <h2>{{ $header_title }}</h2>

            <div class="card shadow">
                <form method="POST" action="{{ route('teacher.meetings.store') }}" novalidate>
                    @csrf

                    <div class="mb-3">
                        <label for="class_id" class="form-label">Classe</label>
                        <select name="class_id" id="class_id" class="form-select @error('class_id') is-invalid @enderror"
                            required>
                            <option value="" disabled selected>-- Sélectionnez une classe --</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }} {{ $class->opt }}
                                </option>
                            @endforeach
                        </select>
                        @error('class_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="topic" class="form-label">Sujet de la réunion</label>
                        <input type="text" name="topic" id="topic"
                            class="form-control @error('topic') is-invalid @enderror" value="{{ old('topic') }}" required>
                        @error('topic')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="start_time" class="form-label">Date et heure</label>
                        <input type="datetime-local" name="start_time" id="start_time"
                            class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time') }}"
                            required>
                        @error('start_time')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="duration" class="form-label">Durée (minutes)</label>
                        <input type="number" name="duration" id="duration"
                            class="form-control @error('duration') is-invalid @enderror" min="15"
                            value="{{ old('duration', 60) }}" required>
                        @error('duration')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="agenda" class="form-label">Agenda (optionnel)</label>
                        <textarea name="agenda" id="agenda" class="form-control @error('agenda') is-invalid @enderror" rows="4">{{ old('agenda') }}</textarea>
                        @error('agenda')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Créer</button>
                </form>
            </div>
        </div>
    </div>
@endsection
