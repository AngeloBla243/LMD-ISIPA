@extends('layouts.app')
@section('style')
    <style type="text/css">
    </style>
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="h3 fw-bold text-primary">Ajouter un nouveau devoir</h1>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content pb-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">

                        @include('_message')

                        <div class="card shadow-sm rounded-4 border-0">
                            <form id="homeworkForm" method="post" action="" enctype="multipart/form-data" novalidate>
                                @csrf
                                <div class="card-body">

                                    <div class="mb-4">
                                        <label for="getClass" class="form-label fw-semibold">Classe <span
                                                class="text-danger">*</span></label>
                                        <select id="getClass" name="class_id"
                                            class="form-select form-control @error('class_id') is-invalid @enderror"
                                            required>
                                            <option value="">Sélectionner une classe</option>
                                            @foreach ($getClass as $class)
                                                <option value="{{ $class->class_id }}" @selected((string) $class->class_id === (string) old('class_id'))>
                                                    {{ $class->class->name }} {{ $class->class->opt }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('class_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <div class="mb-4">
                                        <label for="subject" class="form-label fw-semibold">Matière <span
                                                class="text-danger">*</span></label>
                                        <select id="subject" name="subject_id"
                                            class="form-select form-control @error('subject_id') is-invalid @enderror"
                                            required>
                                            @if (old('class_id'))
                                                <option value="">Sélectionner une matière</option>
                                                @foreach ($getSubjects as $subject)
                                                    @if ($subject->class_id == old('class_id'))
                                                        <option value="{{ $subject->id }}"
                                                            {{ (string) $subject->id === (string) old('subject_id') ? 'selected' : '' }}>
                                                            {{ $subject->name }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            @else
                                                <option value="">Sélectionnez d'abord une classe</option>
                                            @endif
                                        </select>
                                        @error('subject_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <div class="mb-4 row g-3">
                                        <div class="col-md-6">
                                            <label for="homework_date" class="form-label fw-semibold">Date du devoir <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" id="homework_date" name="homework_date"
                                                class="form-control @error('homework_date') is-invalid @enderror"
                                                value="{{ old('homework_date') }}" required>
                                            @error('homework_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="submission_date" class="form-label fw-semibold">Date de remise <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" id="submission_date" name="submission_date"
                                                class="form-control @error('submission_date') is-invalid @enderror"
                                                value="{{ old('submission_date') }}" required>
                                            @error('submission_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="document_file" class="form-label fw-semibold">Document</label>
                                        <input type="file" id="document_file" name="document_file"
                                            class="form-control @error('document_file') is-invalid @enderror">
                                        @error('document_file')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="description" class="form-label fw-semibold">Description <span
                                                class="text-danger">*</span></label>
                                        <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror"
                                            style="height: 300px;" required>{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="card-footer d-flex justify-content-end bg-white border-0">
                                    <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold shadow-sm">
                                        Soumettre
                                    </button>
                                </div>
                            </form>
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
        .form-control:focus,
        textarea.form-control:focus {
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
    </style>
@endsection

@section('script')
    <script src="{{ url('public/plugins/summernote/summernote-bs4.min.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $('#getClass').change(function() {
            var classId = $(this).val();
            if (classId) {
                $.ajax({
                    url: "{{ route('getSubjectsByClass') }}",
                    type: "GET",
                    data: {
                        class_id: classId
                    },
                    success: function(data) {
                        $('#subject').html('<option value="">Sélectionner une matière</option>');
                        $.each(data, function(key, value) {
                            $('#subject').append('<option value="' + value.id + '">' + value
                                .name + '</option>');
                        });
                    }
                });
            } else {
                $('#subject').html('<option value="">Sélectionnez d\'abord une classe</option>');
            }
        });
    </script>

    <script type="text/javascript">
        $(function() {


            $('#compose-textarea').summernote({
                height: 200
            });

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
