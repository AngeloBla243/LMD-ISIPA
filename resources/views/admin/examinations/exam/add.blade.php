{{-- @extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Add New Exam</h1>
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
                                        <label>Exam Name</label>
                                        <input type="text" class="form-control" value="{{ old('name') }}"
                                            name="name" required placeholder="Exam Name">
                                    </div>
                                    <div class="form-group">
                                        <label>Note</label>
                                        <textarea class="form-control" name="note" placeholder="Note"></textarea>
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
                        <h1>Add New Exam</h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <form method="post" action="">
                                {{ csrf_field() }}
                                <div class="card-body">

                                    <!-- Année académique -->
                                    <div class="form-group">
                                        <label>Année académique</label>
                                        <select name="academic_year_id" class="form-control" required>
                                            <option value="">Sélectionner une année</option>
                                            @foreach ($academicYears as $year)
                                                <option value="{{ $year->id }}">{{ $year->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Nombre de cases examen -->
                                    <div class="form-group">
                                        <label for="number_exams">Nombre de noms d’examens à ajouter (max 5)</label>
                                        <input type="number" class="form-control" id="number_exams" min="1"
                                            max="5" value="1">
                                    </div>
                                    <div id="dynamic_exams">
                                        <div class="form-group exam-block">
                                            <label>Exam Name #1</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="name[]" required
                                                    placeholder="Exam Name">
                                                <div class="input-group-append">
                                                    <input type="checkbox" checked name="enabled[]" class="enable-checkbox"
                                                        value="1" title="Activer/Désactiver">
                                                    <span style="margin-left:10px;">Activé</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Note</label>
                                        <textarea class="form-control" name="note" placeholder="Note"></textarea>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function renderExamFields(count) {
                let container = document.getElementById('dynamic_exams');
                container.innerHTML = '';
                for (let i = 1; i <= count; i++) {
                    container.innerHTML += `
      <div class="form-group exam-block">
        <label>Exam Name #${i}</label>
        <div class="input-group">
          <input type="text" class="form-control" name="name[]" required placeholder="Exam Name">
          <div class="input-group-append">
            <input type="checkbox" checked name="enabled[]" class="enable-checkbox" value="1" title="Activer/Désactiver">
            <span style="margin-left:10px;">Activé</span>
          </div>
        </div>
      </div>
      `;
                }
            }

            // Initialisation
            let numberExamsInput = document.getElementById('number_exams');
            renderExamFields(numberExamsInput.value);

            numberExamsInput.addEventListener('change', function() {
                let val = parseInt(this.value);
                if (val > 0 && val <= 5) {
                    renderExamFields(val);
                }
            });

            // Enabled/disabled (optionnel pour alerter)
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('enable-checkbox')) {
                    let label = e.target.closest('.input-group-append').querySelector('span');
                    label.textContent = e.target.checked ? 'Activé' : 'Désactivé';
                    if (!e.target.checked) {
                        e.target.previousElementSibling.value =
                        ''; // optionnel: vider le champ si désactivé
                    }
                }
            });
        });
    </script>
@endsection
