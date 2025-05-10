@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Add New Subject</h1>
                    </div>

                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">


                        <div class="card card-primary">

                            @include('_message')
                            <div class="card-body">
                                <form method="post"
                                    action="{{ route('admin.assign_class_teacher.assign_subject.submit') }}">
                                    @csrf

                                    <div class="form-group">
                                        <label>Enseignant</label>
                                        <input type="text" class="form-control"
                                            value="{{ $selectedTeacher->name }} {{ $selectedTeacher->last_name }}" readonly>
                                        <input type="hidden" name="teacher_id" value="{{ $selectedTeacher->id }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Classe Assignée</label>
                                        @if ($classes->count() > 1)
                                            <select name="class_id" id="classSelect" class="form-control" required
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

                                    <div class="form-group">
                                        <label>Année Académique</label>
                                        <input type="text" class="form-control" value="{{ $academicYear->name }}"
                                            readonly>
                                        <input type="hidden" name="academic_year_id" value="{{ $academicYear->id }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Matières Disponibles</label>
                                        <select name="subject_ids[]" class="form-control" multiple required>
                                            @foreach ($subjects as $subject)
                                                @php $isAssigned = in_array($subject->id, $assignedSubjectIds) @endphp
                                                <option value="{{ $subject->id }}" {{ $isAssigned ? 'selected' : '' }}>
                                                    {{ $subject->name }} ({{ $subject->code }})
                                                    @if ($isAssigned)
                                                        - Déjà assigné
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Les matières déjà assignées sont présélectionnées</small>
                                    </div>



                                    <button type="submit" class="btn btn-primary">Assigner</button>
                                </form>
                            </div>

                        </div>

                        <div id="customModal" class="modal">
                            <div class="modal-content">
                                <span class="close">&times;</span>
                                <p id="modalMessage"></p>
                            </div>
                        </div>




                    </div>

                    <!--/.col (left) -->
                    <!-- right column -->

                    <!--/.col (right) -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
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
