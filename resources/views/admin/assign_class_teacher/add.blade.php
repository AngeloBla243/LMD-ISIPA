{{-- @extends('layouts.app')

@section('content')

 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Add New Assign Class Teacher</h1>
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
              <form method="post" action="">
                 {{ csrf_field() }}
                <div class="card-body">
                  <div class="form-group">
                    <label>Class Name</label>
                     <select class="form-control" name="class_id" required>
                        <option value="">Select Class</option>
                        @foreach ($getClass as $class)
                          <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>

                  </div>


                   <div class="form-group">
                    <label>Teacher Name</label>
                        @foreach ($getTeacher as $teacher)
                        <div>
                          <label style="font-weight: normal;">
                            <input type="checkbox" value="{{ $teacher->id }}" name="teacher_id[]"> {{ $teacher->name }} {{ $teacher->last_name }}
                          </label>
                          </div>
                        @endforeach
                  </div>


                  <div class="form-group">
                    <label>Status</label>
                    <select class="form-control" name="status">
                        <option value="0">Active</option>
                        <option value="1">Inactive</option>
                    </select>

                  </div>


                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>
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

@endsection --}}

@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Assigner une classe à un enseignant</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            @include('_message')
                            <form id="assignClassForm" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="card-body">
                                    <!-- Sélectionner une classe -->
                                    <div class="form-group">
                                        <label for="class_id">Sélectionner une classe</label>
                                        <select class="form-control" name="class_id" id="class_id" required>
                                            <option value="">Sélectionner une classe</option>
                                            @foreach ($getClass as $class)
                                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Sélectionner un ou plusieurs enseignants -->
                                    <div class="form-group">
                                        <label for="teacher_id">Sélectionner un enseignant</label>
                                        @foreach ($getTeacher as $teacher)
                                            <div>
                                                <label style="font-weight: normal;">
                                                    <input type="checkbox" value="{{ $teacher->id }}" name="teacher_id[]">
                                                    {{ $teacher->name }} {{ $teacher->last_name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Statut -->
                                    <div class="form-group">
                                        <label for="status">Statut</label>
                                        <select class="form-control" name="status">
                                            <option value="0">Actif</option>
                                            <option value="1">Inactif</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button type="button" id="submitAssignClass" class="btn btn-primary">Assigner la
                                        classe</button>
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
    <!-- Script pour gérer la soumission Ajax -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#submitAssignClass').click(function(e) {
                e.preventDefault();

                // Récupérer les données du formulaire
                let formData = $('#assignClassForm').serialize();

                // Envoyer les données avec Ajax
                $.ajax({
                    type: 'POST',
                    url: '{{ route('admin.assign_class_teacher.add') }}', // Route nommée pour assign_subject1
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            // Rediriger vers la page d'assignation des matières
                            let teacher_id = response.teacher_id;
                            let status = response.status;
                            let class_id = response.class_id;
                            window.location.href =
                                '{{ url('admin/assign_class_teacher/assign_subject_subject1') }}/' +
                                teacher_id + '/' + class_id ;
                        } else {
                            alert(response.error);
                        }
                    },
                    error: function(xhr, status, error) {
                        let err = JSON.parse(xhr.responseText);
                        alert(err.message);
                    }
                });
            });
        });
    </script>
@endsection
