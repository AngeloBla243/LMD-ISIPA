@extends('layouts.app')
@section('style')
    <style type="text/css">
        .styled-table thead tr {
            background-color: #009879;
            color: #ffffff;
            text-align: left;
        }

        /* Effet survol (hover) */
        .styled-table tbody tr:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }
    </style>

    <style type="text/css">
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #000;
        }

        .header {
            text-align: center;
        }


        .section-title {
            margin-top: 10px;
            font-weight: bold;
            text-decoration: underline;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
        }

        .checkbox-group {
            margin-left: 20px;
        }

        .checkbox-group input {
            margin-right: 10px;
        }

        .signature-section {
            margin-top: 30px;
            text-align: right;
        }

        .note-section {
            margin-top: 20px;
            font-size: 0.9em;
        }

        .signature {
            margin-top: 50px;
            text-align: right;
        }

        a.disabled {
            pointer-events: none;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .table-primary th {
            background-color: #cfe2ff;
            color: #084298;
        }

        .btn-outline-primary:hover {
            background-color: #084298;
            color: #fff;
            border-color: #084298;
        }

        .fw-semibold {
            font-weight: 600;
        }
    </style>
@endsection


@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="row mb-3">
                <div class="col-md-12">
                    <form method="GET" action="">
                        <div class="input-group mb-2">

                            @if ($selectedAcademicYear && !$selectedAcademicYear->is_active)
                                <span class="ms-3 align-items-center" style="color: #ffc107;">
                                    <i class="fas fa-exclamation-triangle"></i> Vous n'êtes pas dans l'année active !
                                </span>
                            @endif
                        </div>
                    </form>



                    <div class="card mb-4">
                        <div class="text-black d-flex justify-content-between align-items-center">
                            <span> Année Academique : {{ $selectedAcademicYear->name ?? 'Année académique' }}</span>

                            <!-- Bouton d'impression -->
                            <a class="btn btn-primary btn-sm" style="float: right;"
                                href="{{ route('student.year_result.print', [
                                    'academic_year_id' => $selectedAcademicYear->id,
                                    'student_id' => Auth::id(),
                                ]) }}"
                                class="btn btn-light btn-sm" target="_blank">
                                <i class="fas fa-file-pdf"></i> Relever Annuel
                            </a>
                        </div>

                    </div>


                </div>

            </div>

            @if (!empty($getRecord))
                @foreach ($getRecord as $value)
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header bg-secondary text-white">
                            <strong>{{ $value['exam_name'] }}</strong>

                            <a class="btn btn-primary btn-sm" style="float: right;" target="_blank"
                                href="{{ url('student/my_exam_result/print?exam_id=' . $value['exam_id'] . '&student_id=' . Auth::user()->id) }}"><i
                                    class="fas fa-file-pdf"></i> Print</a>
                        </div>
                        <div class="card-body p-0 table-responsive"">
                            @if (!empty($value['subject']))
                                <table class="table styled-table table-bordered table-striped m-0">
                                    <thead>
                                        <tr>
                                            <th>Ec</th>
                                            <th>Crédit Ec</th>
                                            <th>Note / 20</th>
                                            <th>Décision</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $Grandtotals_score = 0;
                                            $credits_obtenus = 0;
                                            $full_marks = 0;
                                            $fail_count = 0;
                                        @endphp
                                        @foreach ($value['subject'] as $exam)
                                            @php
                                                $Grandtotals_score += $exam['totals_score'] ?? 0;
                                                $total_score = $exam['total_score'] ?? 0;
                                                $passing_mark = $exam['passing_mark'] ?? 0;
                                                $full_marks += $exam['ponde'] ?? 0;
                                                if ($total_score >= 10) {
                                                    $credits_obtenus += $exam['ponde'] ?? 0;
                                                }
                                            @endphp
                                            <tr>
                                                <td
                                                    style="width: 200px; display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                                                    <span>{{ $exam['subject_name'] ?? 'N/A' }}</span>

                                                    <button class="btn btn-sm btn-outline-primary btn-recours"
                                                        data-exam-id="{{ $value['exam_id'] }}"
                                                        data-session="{{ $exam['session'] ?? 1 }}"
                                                        data-subject-id="{{ $exam['subject_id'] }}"
                                                        style="display: flex; align-items: center; gap: 4px;">
                                                        <i class="fas fa-edit me-1"></i>
                                                    </button>
                                                </td>

                                                <td>{{ $exam['ponde'] ?? 0 }}</td>
                                                <td>
                                                    @if (($exam['total_score'] ?? 0) == 0)
                                                        <span style="color: gray; font-weight: bold;">ND</span>
                                                    @else
                                                        @if ($exam['total_score'] >= $exam['passing_mark'])
                                                            <span
                                                                style="color: green; font-weight: bold;"><b>{{ $exam['total_score'] }}</b></span>
                                                        @else
                                                            <span
                                                                style="color: red; font-weight: bold;"><b>{{ $exam['total_score'] }}</b></span>
                                                            @php $fail_count++; @endphp
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (($exam['total_score'] ?? 0) >= 10)
                                                        <span style="color: green; font-weight: bold;"><b>VAL</b></span>
                                                    @else
                                                        <span style="color: red; font-weight: bold;"><b>NVL</b></span>
                                                        @php $fail_count++; @endphp
                                                    @endif
                                                </td>

                                                {{-- <td>
                                                    <button class="btn btn-warning btn-sm btn-recours"
                                                        data-exam-id="{{ $value['exam_id'] }}"
                                                        data-session="{{ $exam['session'] ?? 1 }}"
                                                        data-subject-id="{{ $exam['subject_id'] }}">
                                                        <i class="fa fa-exclamation-triangle"></i> Recours
                                                    </button>
                                                </td> --}}
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info m-3">
                                    <i class="fas fa-info-circle"></i> Aucun résultat d'examen enregistré pour cette
                                    session.
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="alert alert-warning mt-4">
                    <i class="fas fa-info-circle"></i> Aucun résultat d'examen disponible pour le moment.
                </div>
            @endif
        </section>
    </div>

    <div class="modal fade" id="recoursModal" tabindex="-1" role="dialog" aria-labelledby="recoursModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addFeesModalLabel">Recours</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="recoursForm" action="{{ route('student.my_subject') }}" method="POST">
                    {{ csrf_field() }}

                    <!-- Récupère l'année depuis l'URL -->

                    <input type="hidden" name="exam_id" id="modal_exam_id">
                    <input type="hidden" name="session" id="modal_session">
                    <input type="hidden" name="subject_id" id="modal_subject_id">
                    <input type="hidden" name="academic_year_id" id="modal_academic_year_id">

                    <div class="container">
                        <div class="header">
                            <h2>I.S.I.P.A</h2>
                            <h3>Secrétariat Général Académique <br> Bureau du Jury</h3>
                        </div>

                        <div class="form-group">
                            @php
                                $currentClass = Auth::user()->getCurrentClass();
                            @endphp

                            @if ($currentClass)
                                <label>Section / Département : {{ $currentClass->opt }} /
                                    {{ Auth::user()->departement ?? 'N/A' }}</label>
                            @else
                                <label class="text-danger">Aucune classe assignée pour cette année académique.</label>
                            @endif
                        </div>

                        <div class="form-group">
                            <label>Promotion : <b>{{ $currentClass->name ?? 'N/A' }}
                                    {{ $currentClass->opt ?? 'N/A' }}</b></label>
                        </div>

                        <div class="form-group">
                            <label>Nom et Post-nom : <b>{{ Auth::user()->name }} {{ Auth::user()->last_name }}</b></label>
                        </div>

                        <h4 class="section-title">I. Objet (Prière de cocher la case concernée)</h4>
                        <div class="checkbox-group">
                            <!-- Les cases à cocher ici -->
                            <div>
                                <input type="checkbox" name="objet[]"
                                    value="Omission des cotes sur la grille de délibération" class="single-checkbox">
                                Omission des cotes sur la grille de délibération
                            </div>
                            <div>
                                <input type="checkbox" name="objet[]"
                                    value="Omission
                                du nom sur la grille de délibération"
                                    class="single-checkbox"> Omission
                                du nom sur la grille de délibération
                            </div>
                            <div>
                                <input type="checkbox" name="objet[]" value="Calcul erroné des cotes"
                                    class="single-checkbox"> Calcul
                                erroné des cotes
                            </div>
                            <div>
                                <input type="checkbox" name="objet[]" value="Non transmission des cotes au Jury"
                                    class="single-checkbox"> Non
                                transmission des cotes au Jury
                            </div>
                            <div>
                                <input type="checkbox" name="objet[]"
                                    value="Transcription erronée des cotes par l'enseignant (titulaire) ou le secrétaire du jury"class="single-checkbox">
                                Transcription erronée des cotes par l'enseignant (titulaire) ou le secrétaire du jury
                            </div>
                            <div>
                                <input type="checkbox" name="objet[]" value="Omission de la correction des copies"
                                    class="single-checkbox">
                                Omission de la correction des copies
                            </div>
                            <div>
                                <input type="checkbox" name="objet[]" value="Identification confuse des copies"
                                    class="single-checkbox">
                                Identification confuse des copies
                            </div>

                        </div>

                        <div class="note-section">
                            <p>NB :</p>
                            <ul>
                                <li>Le recours retourne au bureau du Jury 48 heures après le retrait</li>
                                <li>Le recours dont l'objet ne sera pas coché est d'office annulé</li>
                                <li>Le recours se fait par cours</li>
                                <li>Le recours ne garantit pas la réussite</li>
                            </ul>
                        </div>

                        <button type="submit" class="btn btn-primary">Soumettre</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="customModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p id="modalMessage"></p>
        </div>
    </div>
@endsection


@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion de l'ouverture du modal
            document.querySelectorAll('.btn-recours').forEach(button => {
                button.addEventListener('click', function() {
                    const examId = this.dataset.examId;
                    const session = this.dataset.session;
                    const subjectId = this.dataset.subjectId;
                    const academicYearId = "{{ session('academic_year_id') }}";

                    document.getElementById('modal_exam_id').value = examId;
                    document.getElementById('modal_session').value = session;
                    document.getElementById('modal_subject_id').value = subjectId;
                    document.getElementById('modal_academic_year_id').value = academicYearId;

                    // Affichage du modal (Bootstrap 5)
                    new bootstrap.Modal(document.getElementById('recoursModal')).show();
                });
            });

            // Validation des cases à cocher (une seule case)
            document.querySelectorAll('.single-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    if (this.checked) {
                        document.querySelectorAll('.single-checkbox').forEach(cb => {
                            if (cb !== this) cb.checked = false;
                        });
                    }
                });
            });

            // Soumission AJAX du formulaire
            document.getElementById('recoursForm').addEventListener('submit', function(e) {
                e.preventDefault();

                // Vérifier si au moins une case est cochée
                const checkboxes = Array.from(this.querySelectorAll('input[name="objet[]"]'));
                const isChecked = checkboxes.some(cb => cb.checked);

                if (!isChecked) {
                    Swal.fire({
                        title: 'Erreur !',
                        text: 'Veuillez cocher au moins un motif.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                // Soumettre le formulaire
                fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: new FormData(this)
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Erreur réseau');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Recours envoyé !',
                                html: `N° ${data.nextNumero}<br>Session ${data.session_year}`,
                                icon: 'success'
                            }).then(() => window.location.reload());
                        } else {
                            Swal.fire('Erreur !', data.message, 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Erreur !', error.message ||
                            'Une erreur est survenue lors de la soumission du recours.', 'error');
                    });
            });
        });
    </script>
@endsection
