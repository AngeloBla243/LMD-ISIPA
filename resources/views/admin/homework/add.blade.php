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
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        @include('_message')
                        <div class="card card-primary">
                            <form method="post" action="" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Année académique <span style="color:red">*</span></label>
                                        <select class="form-control" name="academic_year_id" onchange="this.form.submit()"
                                            required>
                                            <option value="">Sélectionner une année</option>
                                            @foreach ($academicYears as $year)
                                                <option value="{{ $year->id }}"
                                                    {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>
                                                    {{ $year->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Classe <span style="color:red">*</span></label>
                                        <select class="form-control" name="class_id" onchange="this.form.submit()" required
                                            {{ empty($getClass) ? 'disabled' : '' }}>
                                            <option value="">Sélectionner la classe</option>
                                            @foreach ($getClass as $class)
                                                <option value="{{ $class->id }}"
                                                    {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                                    {{ $class->name }} {{ $class->opt }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Matière <span style="color:red">*</span></label>
                                        <select class="form-control" name="subject_id" required
                                            {{ empty($getSubject) ? 'disabled' : '' }}>
                                            <option value="">Sélectionner la matière</option>
                                            @foreach ($getSubject as $subject)
                                                <option value="{{ $subject->subject_id }}"
                                                    {{ request('subject_id') == $subject->subject_id ? 'selected' : '' }}>
                                                    {{ $subject->subject->name }}
                                                </option>
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
                                {{-- <button type="submit" class="btn btn-primary">Continuer</button> --}}
                            </form>
                        </div>
                    </div>


                </div>
            </div>

    </div>
    </section>

    </div>
@endsection

@section('script')
    <script src="{{ url('public/plugins/summernote/summernote-bs4.min.js') }}"></script>

    {{-- <script type="text/javascript">
        $(function() {


            // $('#compose-textarea').summernote({
            // 	  height: 200
            // 	});

            $('#getClass').change(function() {
                var class_id = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "{{ url('admin/ajax_get_subject') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        class_id: class_id,
                    },
                    dataType: "json",
                    success: function(data) {
                        $('#getSubject').html(data.success);
                    }
                });

            });

        });
    </script> --}}
@endsection
