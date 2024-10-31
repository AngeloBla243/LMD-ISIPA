@extends('layouts.app')
@section('style')
    <style type="text/css">
    </style>
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Add New Homework</h1>
                    </div>

                </div>
            </div>
        </section>


        <section class="content">

            <div id="customModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <p id="modalMessage"></p>
                </div>
            </div>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        @include('_message')
                        <div class="card card-primary">
                            <form id="yourFormId" method="post" action="" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="card-body">

                                    <div class="form-group">
                                        <label>Class <span style="color:red">*</span></label>
                                        <select class="form-control" id="getClass" name="class_id" required>
                                            <option value="">Select Class</option>
                                            @foreach ($getClass as $class)
                                                <option value="{{ $class->class_id }}">{{ $class->class_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="form-group">
                                        <label>Subject <span style="color:red">*</span></label>
                                        {{-- <select class="form-control" name="subject_id" id="getSubject" required>
                        <option value="">Select Subject</option>
                    </select> --}}
                                        <select id="subject" name="subject_id" class="form-control" required>
                                            <option value="">Select Subject</option>
                                            @foreach ($getSubjects as $subject)
                                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="form-group">
                                        <label>Homework Date <span style="color:red">*</span></label>
                                        <input type="date" class="form-control" name="homework_date" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Submission Date <span style="color:red">*</span></label>
                                        <input type="date" class="form-control" name="submission_date" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Document</label>
                                        <input type="file" class="form-control" name="document_file">
                                    </div>

                                    <div class="form-group">
                                        <label>Description <span style="color:red">*</span></label>
                                        <textarea id="compose-textarea" name="description" class="form-control" style="height: 300px"></textarea>
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>


                    </div>
                </div>

            </div>
        </section>

    </div>
@endsection

@section('script')
    <script src="{{ url('public/plugins/summernote/summernote-bs4.min.js') }}"></script>

    <script type="text/javascript">
        $(function() {


            $('#compose-textarea').summernote({
                height: 200
            });

            //   $('#getClass').change(function() {
            //       var class_id = $(this).val();
            //       $.ajax({
            //           type: "POST",
            //         //   url: "{{ url('teacher/ajax_get_subject') }}",
            //           data : {
            //              "_token": "{{ csrf_token() }}",
            //             class_id : class_id,
            //           },
            //           dataType : "json",
            //           success: function(data) {
            //               $('#getSubject').html(data.success);
            //           }
            //       });

            //   });
            $('#yourFormId').submit(function(event) {
                event.preventDefault(); // Empêcher le rechargement de la page

                var class_id = $('#getClass').val(); // Récupérer la valeur de getClass
                $.ajax({
                    type: "POST",
                    url: "{{ url('teacher/ajax_get_subject') }}", // URL pour récupérer les matières
                    data: {
                        "_token": "{{ csrf_token() }}",
                        class_id: class_id,
                    },
                    dataType: "json",
                    success: function(data) {
                        // Vérifier si des matières ont été récupérées
                        if (data.success) {
                            $('#getSubject').html(data
                                .success); // Afficher les matières dans le select

                            Swal.fire({
                                icon: 'success',
                                title: 'Succès',
                                text: 'Le Tp est bien Envoyé.',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Rediriger vers une autre page
                                    window.location.href =
                                        "{{ url('teacher/homework/homework') }}"; // Remplacez par votre URL
                                }
                            });

                        } else {
                            // Alerte en cas de message d'erreur
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur',
                                text: 'Aucune Tp trouvée.',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        // Gestion des erreurs AJAX
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: 'Une erreur est survenue lors de la récupération des matières.',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });

        });
    </script>
@endsection
