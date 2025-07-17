@extends('layouts.app')

@section('content')
    <div class="content-wrapper py-4">
        <div class="container">
            <h2>Modifier le questionnaire</h2>

            <form method="POST" action="{{ route('teacher.exams.update', $exam->id) }}">
                @csrf
                @method('PUT')
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <!-- Titre du questionnaire -->
                        <div class="mb-3">
                            <label class="form-label">Titre du questionnaire</label>
                            <input type="text" name="title" class="form-control" required value="{{ $exam->title }}">
                        </div>

                        <!-- Classe et Matière -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Classe <span class="text-danger">*</span></label>
                                <select name="class_id" id="class_id" class="form-select form-control" required>
                                    <option value="">Sélectionner une classe</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}"
                                            {{ $exam->class_id == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>



                            <div class="col-md-6">
                                <label class="form-label">Matière <span class="text-danger">*</span></label>
                                <select name="subject_id" id="subject_id" class="form-select form-control" required>
                                    <option value="">Sélectionner une matière</option>
                                    @foreach ($subjects as $subject)
                                        <option value="{{ $subject->id }}"
                                            {{ $exam->subject_id == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Durée -->
                        <div class="row mb-3">

                            <div class="col-md-6">
                                <label class="form-label">Nombre de questions</label>
                                <input type="number" name="question_count" class="form-control" min="1" required
                                    value="{{ $exam->question_count }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Choix par question</label>
                                <input type="number" name="choices_per_question" class="form-control" min="2"
                                    max="6" required value="{{ $exam->choices_per_question }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Durée (minutes)</label>
                                <input type="number" name="duration_minutes" class="form-control" min="5" required
                                    value="{{ $exam->duration_minutes }}">
                            </div>
                        </div>




                        <!-- Date de début et fin -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Date de début</label>
                                <input type="datetime-local" name="start_time" class="form-control" required
                                    value="{{ \Carbon\Carbon::parse($exam->start_time)->format('Y-m-d\TH:i') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Date de fin</label>
                                <input type="datetime-local" name="end_time" class="form-control" required
                                    value="{{ \Carbon\Carbon::parse($exam->end_time)->format('Y-m-d\TH:i') }}">
                            </div>
                        </div>

                        <!-- Bouton de soumission -->
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // Script pour charger les matières en fonction de la classe
        document.addEventListener('DOMContentLoaded', function() {
            const classSelect = document.getElementById('class_id');
            const subjectSelect = document.getElementById('subject_id');

            classSelect.addEventListener('change', function() {
                const classId = this.value;
                subjectSelect.innerHTML = '<option value="">Chargement...</option>';

                fetch(`{{ url('teacher/exams/get-subjects') }}/${classId}`)
                    .then(response => response.json())
                    .then(subjects => {
                        subjectSelect.innerHTML = '<option value="">Sélectionner une matière</option>';
                        subjects.forEach(subject => {
                            const option = document.createElement('option');
                            option.value = subject.id;
                            option.textContent = subject.name;
                            subjectSelect.appendChild(option);
                        });
                    });
            });
        });
    </script>
@endsection
