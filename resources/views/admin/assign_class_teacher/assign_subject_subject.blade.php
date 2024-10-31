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
                            <form method="post" action="" id="yourFormId" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="teacher_id">Sélectionner un enseignant</label>
                                        <select id="teacherSelect" name="teacher_id" class="form-control" required>
                                            @if ($teachers && count($teachers) > 0)
                                                @foreach ($teachers as $teacher)
                                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}
                                                        {{ $teacher->last_name }}</option>
                                                @endforeach
                                            @else
                                                <option value="">Aucun enseignant disponible</option>
                                            @endif
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="subject_ids">Sélectionner les matières</label>
                                        <select name="subject_ids[]" class="form-control" multiple required>
                                            @if ($subjects && count($subjects) > 0)
                                                @foreach ($subjects as $subject)
                                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                                @endforeach
                                            @else
                                                <option value="">Aucune matière disponible</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
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
                    url: '{{ url("admin/assign_class_teacher/assign_subject_subject") }}',
                    type: 'POST',
                    data: $(this).serialize(), // Utilisation de $(this) pour s'assurer que nous avons accès à jQuery
                    dataType: 'json',
                    success: function(response) {// Afficher la réponse pour débogage
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
@endsection
