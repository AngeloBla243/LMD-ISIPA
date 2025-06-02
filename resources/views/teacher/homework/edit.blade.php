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
                        <h1 class="h3 fw-bold text-primary">Modifier un devoir</h1>
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
                            <form method="post" action="" enctype="multipart/form-data" novalidate>
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
                                                <option value="{{ $class->class_id }}" @selected(old('class_id', $getRecord->class_id ?? '') == $class->class_id)>
                                                    {{ $class->class_name }} {{ $class->class_opt }}
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
                                            <option value="">Sélectionner une matière</option>
                                            @foreach ($getSubjects as $subject)
                                                <option value="{{ $subject->id }}" @selected(old('subject_id', $getRecord->subject_id ?? '') == $subject->id)>
                                                    {{ $subject->name }}
                                                </option>
                                            @endforeach
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
                                                value="{{ old('homework_date', $getRecord->homework_date) }}" required>
                                            @error('homework_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="submission_date" class="form-label fw-semibold">Date de remise <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" id="submission_date" name="submission_date"
                                                class="form-control @error('submission_date') is-invalid @enderror"
                                                value="{{ old('submission_date', $getRecord->submission_date) }}" required>
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
                                        @if (!empty($getRecord->getDocument()))
                                            <a href="{{ $getRecord->getDocument() }}" class="btn btn-primary mt-2"
                                                download>
                                                <i class="fas fa-download me-1"></i> Télécharger
                                            </a>
                                        @endif
                                    </div>

                                    <div class="mb-4">
                                        <label for="description" class="form-label fw-semibold">Description <span
                                                class="text-danger">*</span></label>
                                        <textarea id="compose-textarea" name="description" class="form-control @error('description') is-invalid @enderror"
                                            style="height: 300px;" required>{{ old('description', $getRecord->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="card-footer d-flex justify-content-end bg-white border-0">
                                    <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold shadow-sm">Mettre à
                                        jour</button>
                                </div>
                            </form>
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
    <script>
        $(document).ready(function() {
            $('#getClass').change(function() {
                var classId = $(this).val();
                var teacherId = {{ Auth::id() }};

                if (classId) {
                    $.ajax({
                        url: "{{ route('getSubjectByClass') }}",
                        type: "GET",
                        data: {
                            class_id: classId,
                            teacher_id: teacherId // Optionnel si déjà géré côté serveur
                        },
                        success: function(data) {
                            $('#subject').html(
                                '<option value="">Sélectionner une matière</option>');
                            $.each(data, function(key, value) {
                                $('#subject').append(
                                    '<option value="' + value.id + '">' + value
                                    .name + '</option>'
                                );
                            });

                            // Sélectionner la matière existante après chargement
                            var existingSubjectId = "{{ $getRecord->subject_id ?? '' }}";
                            if (existingSubjectId) {
                                $('#subject').val(existingSubjectId);
                            }
                        },
                        error: function(xhr) {
                            console.error('Erreur AJAX :', xhr.responseText);
                        }
                    });
                } else {
                    $('#subject').html('<option value="">Sélectionnez une classe</option>');
                }
            });

            // Déclencher le changement initial si classe pré-sélectionnée
            @if ($getRecord->class_id)
                $('#getClass').trigger('change');
            @endif
        });
    </script>
@endsection
