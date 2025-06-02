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
        <!-- Content Header -->
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="h3 fw-bold text-primary">Ajouter un nouvel examen</h1>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content pb-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card shadow-sm rounded-4 border-0">
                            <form method="post" action="" novalidate>
                                @csrf
                                <div class="card-body">
                                    <!-- Année académique -->
                                    <div class="mb-4">
                                        <label for="academic_year_id" class="form-label fw-semibold">Année académique <span
                                                class="text-danger">*</span></label>
                                        <select id="academic_year_id" name="academic_year_id"
                                            class="form-select @error('academic_year_id') is-invalid @enderror" required>
                                            <option value="">Sélectionner une année</option>
                                            @foreach ($academicYears as $year)
                                                <option value="{{ $year->id }}">{{ $year->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('academic_year_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Nombre de cases examen -->
                                    <div class="mb-4">
                                        <label for="number_exams" class="form-label fw-semibold">Nombre de noms d’examens à
                                            ajouter (max 5)</label>
                                        <input type="number" id="number_exams" class="form-control" min="1"
                                            max="5" value="1" />
                                    </div>

                                    <!-- Conteneur dynamique des examens -->
                                    <div id="dynamic_exams">
                                        <div class="mb-4 exam-block">
                                            <label class="form-label">Nom de l’examen #1 <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" name="name[]" class="form-control"
                                                    placeholder="Nom de l’examen" required>
                                                <div class="input-group-text ps-3">
                                                    <input type="checkbox" name="enabled[]" class="form-check-input mt-0"
                                                        checked value="1" title="Activer/Désactiver">
                                                    <span class="ms-2">Activé</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Session <span class="text-danger">*</span></label>
                                        <select name="session" class="form-control" required>
                                            <option value="1">Session 1 (Ordinaire)</option>
                                            <option value="2">Session 2 (Rattrapage)</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>
                                            <input type="checkbox" name="is_active" value="1"> Activer cette session
                                        </label>
                                    </div>


                                    <!-- Note -->
                                    <div class="mb-4">
                                        <label for="note" class="form-label fw-semibold">Note</label>
                                        <textarea id="note" name="note" class="form-control" placeholder="Note"></textarea>
                                    </div>
                                </div>

                                <div class="card-footer d-flex justify-content-end bg-white border-0">
                                    <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold shadow-sm">
                                        Soumettre
                                    </button>
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
        .form-control:focus {
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

        .input-group-text {
            background-color: transparent;
            border-left: none;
        }
    </style>
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
