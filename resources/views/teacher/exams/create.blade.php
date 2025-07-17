@extends('layouts.app')

@section('content')
    <div class="content-wrapper py-4">
        <div class="container">
            <h2>Créer un nouveau questionnaire</h2>

            <form method="POST" action="{{ route('teacher.exams.store') }}">
                @csrf
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <!-- Titre du questionnaire -->
                        <div class="mb-3">
                            <label class="form-label">Titre du questionnaire</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>

                        <!-- Classe et Matière -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Classe <span class="text-danger">*</span></label>
                                <select name="class_id" id="class_id" class="form-select form-control" required>
                                    <option value="">Sélectionner une classe</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}"
                                            @if (old('class_id') == $class->id) selected @endif>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Matière <span class="text-danger">*</span></label>
                                <select name="subject_id" id="subject_id"
                                    class="form-select form-control @error('subject_id') is-invalid @enderror" required>
                                    <option value="">Sélectionnez d'abord une classe</option>
                                </select>
                                @error('subject_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Nombre de questions, choix par question, durée -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Nombre de questions</label>
                                <input type="number" name="question_count" class="form-control" min="1" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Choix par question</label>
                                <input type="number" name="choices_per_question" class="form-control" min="2"
                                    max="6" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Durée (minutes)</label>
                                <input type="number" name="duration_minutes" class="form-control" min="5" required>
                            </div>
                        </div>

                        <!-- Date de début et fin -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Date de début</label>
                                <input type="datetime-local" name="start_time" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Date de fin</label>
                                <input type="datetime-local" name="end_time" class="form-control" required>
                            </div>
                        </div>

                        <!-- Bouton de soumission -->
                        <button type="submit" class="btn btn-primary">Créer le questionnaire</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const classSelect = document.getElementById('class_id');
            const subjectSelect = document.getElementById('subject_id');

            classSelect.addEventListener('change', function() {
                const classId = this.value;
                subjectSelect.innerHTML = '<option value="">Chargement...</option>';

                if (!classId) {
                    subjectSelect.innerHTML = '<option value="">Sélectionnez d\'abord une classe</option>';
                    return;
                }

                fetch(`{{ url('teacher/exams/get-subjects') }}/${classId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Erreur réseau');
                        }
                        return response.json();
                    })
                    .then(subjects => {
                        subjectSelect.innerHTML = '';
                        if (subjects.length === 0) {
                            subjectSelect.innerHTML =
                                '<option value="">Aucune matière trouvée</option>';
                        } else {
                            subjectSelect.innerHTML =
                                '<option value="">Sélectionner une matière</option>';
                            subjects.forEach(subject => {
                                const option = document.createElement('option');
                                option.value = subject.id;
                                option.textContent = subject.name;
                                subjectSelect.appendChild(option);
                            });
                        }
                    })
                    .catch(() => {
                        subjectSelect.innerHTML = '<option value="">Erreur lors du chargement</option>';
                    });
            });

            // Recharge la liste des matières si le formulaire a été soumis avec erreur (old)
            @if (old('class_id'))
                fetch(`{{ url('teacher/exams/get-subjects') }}/{{ old('class_id') }}`)
                    .then(response => response.json())
                    .then(subjects => {
                        subjectSelect.innerHTML = '<option value="">Sélectionner une matière</option>';
                        subjects.forEach(subject => {
                            const option = document.createElement('option');
                            option.value = subject.id;
                            option.textContent = subject.name;
                            if (option.value == '{{ old('subject_id') }}') {
                                option.selected = true;
                            }
                            subjectSelect.appendChild(option);
                        });
                    })
                    .catch(() => {
                        subjectSelect.innerHTML = '<option value="">Erreur lors du chargement</option>';
                    });
            @endif
        });
    </script>
@endsection
