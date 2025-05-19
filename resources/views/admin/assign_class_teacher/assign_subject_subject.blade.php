@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="h3 fw-bold text-primary">Assigner des matières à un enseignant</h1>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content pb-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">

                        <div class="card shadow-sm rounded-4 border-0">
                            @include('_message')
                            <div class="card-body">
                                <form method="post"
                                    action="{{ route('admin.assign_class_teacher.assign_subject.submit') }}" novalidate>
                                    @csrf

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Enseignant</label>
                                        <input type="text" class="form-control"
                                            value="{{ $selectedTeacher->name }} {{ $selectedTeacher->last_name }}" readonly>
                                        <input type="hidden" name="teacher_id" value="{{ $selectedTeacher->id }}">
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Classe Assignée</label>
                                        @if ($classes->count() > 1)
                                            <select name="class_id" id="classSelect" class="form-select" required
                                                onchange="window.location.href='?class_id='+this.value">
                                                @foreach ($classes as $c)
                                                    <option value="{{ $c->id }}"
                                                        {{ $selectedClass->id == $c->id ? 'selected' : '' }}>
                                                        {{ $c->name }} {{ $c->opt }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            <input type="text" class="form-control"
                                                value="{{ $selectedClass->name }} {{ $selectedClass->opt }}" readonly>
                                            <input type="hidden" name="class_id" value="{{ $selectedClass->id }}">
                                        @endif
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Année Académique</label>
                                        <input type="text" class="form-control" value="{{ $academicYear->name }}"
                                            readonly>
                                        <input type="hidden" name="academic_year_id" value="{{ $academicYear->id }}">
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Matières Disponibles</label>
                                        <select name="subject_ids[]" class="form-select" multiple required size="8"
                                            style="min-height: 200px;">
                                            @foreach ($subjects as $subject)
                                                @php $isAssigned = in_array($subject->id, $assignedSubjectIds) @endphp
                                                <option value="{{ $subject->id }}" {{ $isAssigned ? 'selected' : '' }}>
                                                    {{ $subject->name }} ({{ $subject->code }}) @if ($isAssigned)
                                                        - <em>Déjà assigné</em>
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Les matières déjà assignées sont présélectionnées</small>
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold shadow-sm">
                                            Assigner
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Modal (optionnel) -->
                        <div id="customModal" class="modal fade" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content rounded-4 shadow">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Information</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p id="modalMessage"></p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Fermer</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>

    <style>
        .card {
            border-radius: 1.5rem;
        }

        .form-label {
            font-weight: 600;
        }

        .form-select:focus,
        input.form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, .25);
        }

        .btn-primary {
            background: linear-gradient(90deg, #0d6efd 60%, #0dcaf0 100%);
            border: none;
            transition: background 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(90deg, #0dcaf0 0%, #0d6efd 100%);
        }

        select[multiple] option {
            padding: 0.3rem 0.5rem;
        }
    </style>


@endsection



@section('script')
    <script type="text/javascript">
        $(function() {
            $('#yourFormId').submit(function(event) {
                event.preventDefault(); // Empêche le rechargement de la page

                $.ajax({
                    url: '{{ url('admin/assign_class_teacher/assign_subject_subject') }}',
                    type: 'POST',
                    data: $(this)
                        .serialize(), // Utilisation de $(this) pour s'assurer que nous avons accès à jQuery
                    dataType: 'json',
                    success: function(response) { // Afficher la réponse pour débogage
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Succès',
                                text: response.success, // Correction ici
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href =
                                        "{{ url('admin/assign_class_teacher/list') }}"; // Redirection après confirmation
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur',
                                text: response
                                    .error, // Affichage d'une erreur si nécessaire
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: xhr.responseJSON.error ||
                                'Une erreur est survenue.', // Afficher l'erreur
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
    </script>

    <!-- Ajoutez le script ici, avant la fin de la section -->
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            // Récupérer l'élément <select> et le lien
            var teacherSelect = document.getElementById('teacherSelect');
            var editLink = document.getElementById('editTeacherLink');

            // Écouter les changements sur la sélection de l'enseignant
            teacherSelect.addEventListener('change', function() {
                var selectedTeacherId = this.value;

                // Vérifier si un enseignant est sélectionné
                if (selectedTeacherId) {
                    // Mettre à jour l'URL du lien avec l'ID de l'enseignant sélectionné
                    editLink.href = "{{ url('admin/assign_class_teacher/assign_subject_subject') }}/" +
                        selectedTeacherId;
                } else {
                    // Si aucun enseignant n'est sélectionné, désactiver le lien ou réinitialiser l'URL
                    editLink.href = "#";
                }
            });
        });
    </script>

    <script>
        function loadTeacherDetails(teacherId) {
            if (!teacherId) return;

            const academicYearInput = document.getElementById('academicYear');
            const assignedClassInput = document.getElementById('assignedClass');
            const subjectSelect = document.getElementById('subjectSelect');

            academicYearInput.value = '';
            assignedClassInput.value = '';
            subjectSelect.innerHTML = '<option value="">Chargement...</option>';
            subjectSelect.disabled = true;

            fetch("{{ url('admin/assign_class_teacher/get-teacher-details') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        teacher_id: teacherId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        academicYearInput.value = 'N/A';
                        assignedClassInput.value = 'N/A';
                        subjectSelect.innerHTML = '<option value="">Aucune matière disponible</option>';
                        subjectSelect.disabled = true;
                        return;
                    }

                    academicYearInput.value = data.academic_year_name || 'N/A';
                    assignedClassInput.value = data.class_name || 'N/A';

                    if (data.subjects && data.subjects.length > 0) {
                        subjectSelect.innerHTML = '';
                        data.subjects.forEach(subject => {
                            const option = document.createElement('option');
                            option.value = subject.id;
                            option.textContent = subject.name;
                            subjectSelect.appendChild(option);
                        });
                        subjectSelect.disabled = false;
                    } else {
                        subjectSelect.innerHTML = '<option value="">Aucune matière disponible</option>';
                        subjectSelect.disabled = true;
                    }
                })
                .catch(() => {
                    alert('Erreur lors de la récupération des données.');
                });
        }

        // Au chargement de la page, si un enseignant est sélectionné, charger ses données
        document.addEventListener('DOMContentLoaded', function() {
            const teacherSelect = document.getElementById('teacherSelect');
            if (teacherSelect.value) {
                loadTeacherDetails(teacherSelect.value);
            }

            // Aussi charger quand on change la sélection
            teacherSelect.addEventListener('change', function() {
                loadTeacherDetails(this.value);
            });
        });
    </script>

    <script>
        document.querySelectorAll('option[disabled]').forEach(option => {
            option.addEventListener('click', (e) => {
                e.preventDefault();
                alert('Cette matière est déjà assignée !');
                return false;
            });
        });
    </script>
@endsection
