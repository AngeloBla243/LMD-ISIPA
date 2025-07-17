{{-- teacher/exams/index.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="content-wrapper py-4">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Mes questionnaires</h2>
            </div>

            @if ($current_academic_year && !$current_academic_year->is_active)
                <div class="alert alert-warning mb-4">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Année académique non active - Les étudiants ne peuvent pas passer les examens
                </div>
            @endif

            <div class="mb-3 text-end">
                <a href="{{ route('teacher.exams.create') }}" class="btn btn-success">
                    <i class="fa fa-plus"></i> Créer un questionnaire
                </a>
            </div>

            <div class="card shadow">
                <div class="card-body">
                    @if ($exams->isEmpty())
                        <div class="alert alert-info">
                            Aucun questionnaire créé pour cette année académique
                        </div>
                    @else
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Titre</th>
                                    <th>Classe</th>
                                    <th>Matière</th>
                                    <th>Date de début</th>
                                    <th>Date de fin</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($exams as $exam)
                                    <tr>
                                        <td>{{ $exam->title }}</td>
                                        <td>{{ $exam->class->name }}</td>
                                        <td>{{ $exam->subject->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($exam->start_time)->format('d/m/Y H:i') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($exam->end_time)->format('d/m/Y H:i') }}</td>

                                        <td>
                                            <div class="d-flex gap-2">
                                                <!-- Bouton pour ajouter des questions -->
                                                <a href="{{ route('teacher.exams.questions.create', $exam->id) }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fa fa-plus"></i> Questions
                                                </a>

                                                <!-- Bouton pour éditer l'examen -->
                                                <a href="{{ route('teacher.exams.edit', $exam->id) }}"
                                                    class="btn btn-sm btn-info">
                                                    <i class="fa fa-edit"></i> Modifier
                                                </a>

                                                <!-- Bouton pour voir les résultats -->
                                                <a href="{{ route('teacher.exams.results', $exam->id) }}"
                                                    class="btn btn-sm btn-warning">
                                                    <i class="fa fa-chart-bar"></i> Résultats
                                                </a>

                                                <!-- Bouton pour supprimer -->
                                                <form action="{{ route('teacher.exams.destroy', $exam->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet examen ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fa fa-trash"></i> Supprimer
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $exams->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
