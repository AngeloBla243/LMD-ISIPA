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

button.download-btn {
    background-color: #007BFF; /* Couleur de base */
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease-in-out;
}

button.download-btn i {
    font-size: 18px; /* Taille de l'icône */
}



button.download-btn:hover {
    background-color: #0056b3;
    box-shadow: 0px 8px 12px rgba(0, 0, 0, 0.2);
    transform: translateY(-3px);
}

button.download-btn:active {
    transform: translateY(1px);
    box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
}

</style>

@endsection
@section('content')
<div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">
            <h1>Homework Report</h1>
          </div>
        </div>
      </div>
    </section>
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">


            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Search Homework Report</h3>
              </div>
              <form method="get" action="">
                <div class="card-body">
                  <div class="row">

                  <div class="form-group col-md-2">
                    <label>Student First Name</label>
                    <input type="text" class="form-control" value="{{ Request::get('first_name') }}" name="first_name"  placeholder="Student First Name">
                  </div>


                  <div class="form-group col-md-2">
                    <label>Student Last Name</label>
                    <input type="text" class="form-control" value="{{ Request::get('last_name') }}" name="last_name"  placeholder="Student Last Name">
                  </div>

                <div class="form-group col-md-2">
                    <label>Class</label>
                    <input type="text" class="form-control" value="{{ Request::get('class_name') }}" name="class_name"  placeholder="Class Name">
                  </div>


                  <div class="form-group col-md-2">
                    <label>Subject</label>
                    <input type="text" class="form-control" value="{{ Request::get('subject_name') }}" name="subject_name"  placeholder="Subject Name">
                  </div>



                  <div class="form-group col-md-2">
                    <label>From Homework Date</label>
                    <input type="date" class="form-control" name="from_homework_date" value="{{ Request::get('from_homework_date') }}"  >
                  </div>

                  <div class="form-group col-md-2">
                    <label>To Homework Date</label>
                    <input type="date" class="form-control" name="to_homework_date" value="{{ Request::get('to_homework_date') }}"  >
                  </div>


                   <div class="form-group col-md-2">
                    <label>From Submission Date</label>
                    <input type="date" class="form-control" name="from_submission_date" value="{{ Request::get('from_submission_date') }}"  >
                  </div>

                  <div class="form-group col-md-2">
                    <label>To Submission Date</label>
                    <input type="date" class="form-control" name="to_submission_date" value="{{ Request::get('to_submission_date') }}"  >
                  </div>


                  <div class="form-group col-md-2">
                    <label>From Submitted Created Date</label>
                    <input type="date" class="form-control" name="from_created_date" value="{{ Request::get('from_created_date') }}"  >
                  </div>

                  <div class="form-group col-md-2">
                    <label>To Submitted Created Date</label>
                    <input type="date" class="form-control" name="to_created_date" value="{{ Request::get('to_created_date') }}"  >
                  </div>



                  <div class="form-group col-md-3">
                    <button class="btn btn-primary" type="submit" style="margin-top: 30px;"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
                    <a href="{{ url('admin/homework/homework_report') }}" class="btn btn-success" style="margin-top: 30px;">Reset</a>

                  </div>

                  </div>
                </div>
              </form>
            </div>


            @include('_message')
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Homework Report List</h3>
              </div>
              <div class="card-body p-0" style="overflow: auto;">
                <table class="table styled-table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th style="min-width: 200px;">Student Name</th>
                      <th>Class</th>
                      <th>Subject</th>
                      <th style="min-width: 200px;">Homework Date</th>
                      <th style="min-width: 200px;">Submission Date</th>
                      <th>Document</th>
                      <th>Description</th>
                      <th>Created Date</th>

                      <th style="min-width: 200px;">Submitted Document</th>
                      <th style="min-width: 200px;">Submitted Description</th>
                      <th style="min-width: 200px;">Submitted Created Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($getRecord as $value)
                        <tr>
                          <td>{{ $value->id }}</td>
                          <td style="min-width: 100px;">{{ $value->first_name }} {{ $value->last_name }}</td>
                          <td>{{ $value->class_name }}</td>
                          <td>{{ $value->subject_name }}</td>
                          <td>{{ date('d-m-Y', strtotime($value->getHomework->homework_date)) }}</td>
                          <td>{{ date('d-m-Y', strtotime($value->getHomework->submission_date)) }}</td>
                          <td style="min-width: 300px;">
                              @if(!empty($value->getHomework->getDocument()))
                                {{-- <a href="{{ $value->getHomework->getDocument() }}" class="btn btn-primary" download="">Download</a> --}}
                                <button class="download-btn" id="downloadBtn">
                                    <i class="fas fa-download"></i><a href="{{ $value->getHomework->getDocument() }}" class="btn" download="">Download</a>
                                </button>
                              @endif
                          </td>
                          <td>
                            {!! $value->getHomework->description !!}
                          </td>
                          <td>{{ date('d-m-Y', strtotime($value->getHomework->created_at)) }}</td>


                           <td>
                              @if(!empty($value->getDocument()))
                                {{-- <a href="{{ $value->getDocument() }}" class="btn btn-primary" download="">Download</a> --}}
                                <button class="download-btn" id="downloadBt">
                                    <i class="fas fa-download"></i><a href="{{ $value->getDocument() }}" class="btn" download="">Download</a>
                                </button>
                              @endif
                          </td>
                          <td>
                            {!! $value->description !!}
                          </td>
                          <td>{{ date('d-m-Y', strtotime($value->created_at)) }}</td>

                        </tr>
                      @empty
                      <tr>
                        <td colspan="100%">Record not found</td>
                      </tr>
                      @endforelse
                  </tbody>
                </table>
                <div style="padding: 10px; float: right;">
                    {!! $getRecord->appends(Illuminate\Support\Facades\Request::except('page'))->links() !!}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

  </div>

@endsection

@section('script').
<script type="text/javascript">
    document.getElementById('downloadBtn').addEventListener('click', function() {
        const button = this;
        const icon = button.querySelector('i');

        // Simuler le téléchargement (changer l'icône après clic)
        setTimeout(() => {
            icon.classList.remove('fa-download'); // Supprimer l'icône de téléchargement
            icon.classList.add('fa-check-circle'); // Ajouter l'icône de confirmation
            button.innerHTML = '<i class="fas fa-check-circle"></i> Downloaded'; // Changer le texte
            button.style.backgroundColor = '#28a745'; // Changer la couleur du bouton (vert)
        }, 1000); // Simule un temps de téléchargement de 1 seconde
    });
</script>

<script type="text/javascript">
    document.getElementById('downloadBt').addEventListener('click', function() {
        const button = this;
        const icon = button.querySelector('i');

        // Simuler le téléchargement (changer l'icône après clic)
        setTimeout(() => {
            icon.classList.remove('fa-download'); // Supprimer l'icône de téléchargement
            icon.classList.add('fa-check-circle'); // Ajouter l'icône de confirmation
            button.innerHTML = '<i class="fas fa-check-circle"></i> Downloaded'; // Changer le texte
            button.style.backgroundColor = '#28a745'; // Changer la couleur du bouton (vert)
        }, 1000); // Simule un temps de téléchargement de 1 seconde
    });
</script>

@endsection
