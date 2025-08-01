@extends('layouts.app')

@section('style')
    <style>
        .card-custom {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 24px rgba(14, 74, 107, 0.07);
            border: none;
            padding: 2.5em 2.2em;
            max-width: 550px;
            margin: 0 auto;
        }

        .form-label,
        label {
            font-weight: 600;
            color: #2176bd;
            margin-bottom: .4em;
        }

        .form-control {
            border-radius: 12px;
            border: 1.5px solid #dde6f7;
            margin-bottom: 1.3em;
            font-size: 1.07em;
            padding: 0.7em 1em;
            transition: border-color 0.15s;
            box-shadow: 0 1px 4px rgba(33, 118, 189, 0.03);
        }

        .form-control:focus {
            border-color: #2176bd;
            box-shadow: 0 2px 8px rgba(33, 118, 189, 0.12);
        }

        .form-group small {
            color: #777;
            font-size: 0.98em;
            margin-top: -.7em;
            margin-bottom: 1em;
        }

        .btn-success {
            border-radius: 16px;
            font-weight: 600;
            letter-spacing: .7px;
            padding: .7em 2.2em;
            font-size: 1.1em;
            background: #2176bd;
            border-color: #2176bd;
            transition: background 0.2s;
        }

        .btn-success:hover {
            background: #1162a4;
            border-color: #1162a4;
            color: #fff;
        }

        h2 {
            color: #2176bd;
            font-weight: 800;
            margin-bottom: 1.4em;
            letter-spacing: 0.6px;
            text-align: center;
        }

        /* Select multiple style */
        select[multiple] {
            min-height: 90px;
        }

        @media (max-width: 600px) {
            .card-custom {
                padding: 1.2em 0.6em;
                min-width: unset;
            }

            h2 {
                font-size: 1.2em;
            }
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper py-4">
        <div class="container">
            <div class="card card-custom">
                <h2><i class="fas fa-plus-circle me-2"></i>Créer un Frais</h2>
                <form action="{{ route('admin.fee_types.store') }}" method="POST" autocomplete="off">
                    @csrf
                    <div class="form-group">
                        <label for="name">Intitulé</label>
                        <input type="text" name="name" id="name" class="form-control" required
                            placeholder="Ex: Inscription">
                    </div>
                    <div class="form-group">
                        <label for="amount">Montant</label>
                        <input type="number" step="0.01" name="amount" id="amount" class="form-control" required
                            placeholder="Ex: 10000">
                    </div>
                    <div class="form-group">
                        <label for="start_date">Date début</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="end_date">Date fin/limite</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="classes">Classes concernées</label>
                        <select name="class_ids[]" id="classes" class="form-control" multiple required>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                        <small>
                            <i class="fas fa-info-circle"></i>
                            Utilisez <b>Ctrl</b> (ou <b>Cmd</b> sur Mac) + clic pour sélectionner plusieurs classes
                        </small>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
