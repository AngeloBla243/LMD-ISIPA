@extends('layouts.app')
@section('style')

<style type="text/css">
.styled-table {
    border-collapse: collapse;
    margin: 25px 0;
    min-width: 400px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
    border-radius: 8px;
    overflow: hidden;
}
.styled-table thead tr {
    background-color: #009879;
    color: #ffffff;
    text-align: left;
}
.styled-table th,
.styled-table td {
    padding: 12px 15px;
}
.styled-table tbody tr {
    border-bottom: 1px solid #dddddd;
}

.styled-table tbody tr:nth-of-type(even) {
    background-color: #f3f3f3;
}

.styled-table tbody tr:last-of-type {
    border-bottom: 2px solid #009879;
}

/* Effet survol (hover) */
.styled-table tbody tr:hover {
    background-color: #f1f1f1;
    cursor: pointer;
}

</style>

@endsection
@section('content')



<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>List Recours</h1>
          </div>


        </div>
      </div><!-- /.container-fluid -->
    </section>




    <!-- Main content -->
    <section class="content">


      <div class="container-fluid">
        <div class="row">

          <!-- /.col -->
          <div class="col-md-12">



           <div class="card">
              <div class="card-header">
                <h3 class="card-title">Search Recours </h3>
              </div>
              <form method="get" action="">
                <div class="card-body">
                  <div class="row">

                  </div>
                </div>
              </form>
            </div>


            @include('_message')

            <!-- /.card -->

            <div class="card">

              <!-- /.card-header -->
              <div class="card-body p-0" style="overflow: auto;">
                <table class="table styled-table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom de l'Étudiant</th>
                            <th>Classe</th>
                            <th>Matière</th>
                            <th>Objet</th>
                            <th>Session</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recours as $recour)
                            <tr>
                                <td>{{ $recour->id }}</td>
                                <td>{{ $recour->student->name }}</td> <!-- Nom de l'étudiant -->
                                <td>{{ $recour->class->name }}</td> <!-- Nom de la classe -->
                                <td>{{ $recour->subject->name }}</td> <!-- Nom de la matière -->
                                <td>{{ $recour->objet }}</td>
                                <td>{{ $recour->session_year }}</td>
                                {{-- <td> <a href="{{ url('admin/examinations/marks_register?class_id=' . $recour->class->id) }}" target="_blank" class="btn btn-info  ">
                                    <i class="fas fa-pencil-alt"></i>
                                    Traité
                                </a></td> --}}

                                <td>
                                    <a href="{{ url('admin/examinations/marks_register?class_id=' . $recour->class->id . '&exam_id=' . $recour->exam_id) }}" target="_blank" class="btn btn-info">
                                        <i class="fas fa-pencil-alt"></i>
                                        Traité
                                    </a>
                                </td>
                            </tr>

                        @endforeach
                    </tbody>

                </table>
                <div style="padding: 10px; float: right;">
                    {{-- {!! $getRecord->appends(Illuminate\Support\Facades\Request::except('page'))->links() !!} --}}
                </div>

              </div>

              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

@endsection
