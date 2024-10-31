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
                            <form id="assignForm" method="post" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <input type="hidden" id="teacher_id" name="teacher_id" value="{{ $teacher_id }}">
                                <input type="hidden" id="class_id" name="class_id" value="{{ $class_id }}">
                                <div class="form-group">
                                    {{-- <label for="subject_ids">Sélectionner les matières</label> --}}
                                    {{-- <select name="subject_ids[]" class="form-control" multiple required style="display: block">
                                        @foreach ($subjects as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                        @endforeach
                                    </select> --}}
                                    <select name="subject_ids[]" class="form-control" multiple required>
                                        <option value="">Sélectionnez une matière</option>
                                        @foreach ($subjects as $classSubject)
                                            <option value="{{ $classSubject->subject->id }}">
                                                {{ $classSubject->subject->name }}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="card-footer">
                                    <button type="button" id="submitButton" class="btn btn-primary">Submit</button>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#submitButton').click(function(e) {
                e.preventDefault(); // Empêche le formulaire de soumettre de manière traditionnelle

                // Récupérer les données du formulaire
                let formData = $('#assignForm').serialize();

                // Récupérer les valeurs des paramètres teacher_id et class_id
                let teacher_id = $('#teacher_id').val();
                let class_id = $('#class_id').val();

                // Vérifier si les deux valeurs sont présentes
                if (!teacher_id || !class_id) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Attention',
                        text: 'Veuillez sélectionner une classe et un enseignant.',
                    });
                    return;
                }

                // Envoyer une requête Ajax
                $.ajax({
                    type: 'POST',
                    url: '{{ route('admin.assign_class_teacher.assign_subject_subject1') }}', // Utilise la route nommée
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Succès',
                                text: 'Les matières ont été assignées avec succès.',
                            }).then(() => {
                                // Rediriger vers une nouvelle page après le succès
                                window.location.href =
                                    '{{ url('admin/assign_class_teacher/list') }}';
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur',
                                text: response.error ||
                                    'Une erreur est survenue lors de l\'assignation des matières.',
                            });
                        }
                    },
                    error: function(xhr) {
                        let err = JSON.parse(xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: err.message ||
                                'Une erreur est survenue lors de la requête.',
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
