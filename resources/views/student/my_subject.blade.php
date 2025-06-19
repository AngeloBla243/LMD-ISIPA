@extends('layouts.app')
@section('style')
    <style type="text/css">
        .styled-table {
            border-collapse: collapse;
            margin: 25px 0;
            min-width: 400px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
            border-radius: 8px;
            overflow: hidden;
        }

        .styled-table thead tr {
            background-color: #009879;
            color: #ffffff;
            text-align: left;
        }

        .styled-table th,
        .styled-table td {
            padding: 12px 15px;
        }

        .styled-table tbody tr {
            border-bottom: 1px solid #dddddd;
        }

        .styled-table tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }

        .styled-table tbody tr:last-of-type {
            border-bottom: 2px solid #009879;
        }

        /* Effet survol (hover) */
        .styled-table tbody tr:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }

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
        <!-- Content Header (Page header) -->
        <section class="content-header py-3 bg-light border-bottom mb-4">
            <div class="container-fluid">
                <div class="row mb-2 align-items-center">
                    <div class="col-sm-6">
                        <h1 class="h3 fw-bold text-primary">Mes Cours</h1>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-md-12">

                        @include('_message')

                        <div class="card shadow-sm border-0 rounded-3">
                            <div class="card-header bg-primary text-white">
                                <h3 class="card-title mb-0">Liste des cours</h3>
                            </div>

                            <div class="card-body p-0 table-responsive">
                                <table class="table table-hover table-bordered align-middle mb-0">
                                    <thead class="table-primary text-center text-uppercase small">
                                        <tr>
                                            <th>Nom du cours</th>
                                            <th style="min-width: 180px;">Type de cours</th>
                                            {{-- <th style="min-width: 180px;">Recours</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($getRecord as $value)
                                            <tr>
                                                <td class="fw-semibold">{{ $value->subject_name }}</td>
                                                <td class="text-center text-muted text-capitalize">
                                                    {{ $value->subject_type }}</td>
                                                {{-- <td class="text-center">
                                                    <a href="#" data-toggle="modal" data-target="#addFeesModal"
                                                        data-subjectid="{{ $value->subject_id }}"
                                                        class="btn btn-sm btn-outline-primary openModal shadow-sm">
                                                        <i class="fas fa-edit me-1"></i> Faire votre recours
                                                    </a>
                                                </td> --}}
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-4">
                                                    Aucun cours disponible pour cette année académique.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->

                    </div>
                </div>

            </div>
        </section>
    </div>


    <div class="modal fade" id="addFeesModal" tabindex="-1" role="dialog" aria-labelledby="addFeesModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addFeesModalLabel">Recours</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="recoursForm" action="" method="POST">
                    {{ csrf_field() }}

                    <!-- Récupère l'année depuis l'URL -->
                    {{-- <input type="hidden" name="academic_year_id" value="{{ Request::get('academic_year_id') }}"> --}}
                    <input type="hidden" name="academic_year_id" value="{{ $academic_year_id }}">

                    <input type="hidden" name="subject_id" id="subject_id">

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
    <script>
        $(document).ready(function() {
            // Lorsque le bouton avec la classe 'openModal' est cliqué
            $('.openModal').click(function() {
                // Récupérer les informations de l'utilisateur
                var studentName = "{{ Auth::user()->name }}";
                var studentName1 = "{{ Auth::user()->last_name }}"; // Nom de l'étudiant
                var className =
                    "{{ optional(Auth::user()->class)->name ?? 'N/A' }}"; // Classe de l'étudiant
                var subjectName = $(this).data('subjectname'); // Nom du cours sélectionnéµ
                var subjectId = $(this).data('subjectid');


                $('#subject_id').val(subjectId);


                // Insérer les informations dans la modale
                $('#studentName').text(studentName);
                $('#studentName1').text(studentName1);
                $('#className').text(className);
                $('#courseName').text(courseName);
                $('#subject_name_display').text(subjectName);

                // Afficher la modale
                $('#userInfoModal').modal('show');

            });

            // Lorsque le formulaire est soumis
            $('#recoursForm').on('submit', function(event) {
                event.preventDefault(); // Empêcher le comportement par défaut
                // Vérifier si une case est cochée
                if (!$('input[type="checkbox"]:checked').length) {
                    // Si aucune case n'est cochée, afficher un message d'erreur avec SweetAlert
                    Swal.fire({
                        title: 'Erreur!',
                        text: "Veuillez cocher au moins une case avant de soumettre.",
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return; // Arrêter l'exécution ici si la case n'est pas cochée
                }

                // Soumettre le formulaire via AJAX
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(data) {
                        // Afficher un message de succès
                        Swal.fire({
                            title: 'Votre recour est bien envoyé!',
                            text: "RECOURS N° " + data.nextNumero + " / Session de " +
                                data.session_year,
                            icon: 'success',
                            buttons: {
                                confirm: {
                                    text: 'OK',
                                    value: true,
                                }
                            }
                        }).then(() => {
                            location.reload();
                        });

                    },
                    error: function(xhr, status, error) {
                        // Gérer les erreurs ici
                        var errorMessage = xhr.responseJSON.message ||
                            "Une erreur s'est produite lors de la soumission du recours.";
                        Swal.fire({
                            title: 'Erreur!',
                            text: errorMessage,
                            icon: 'error',
                            buttons: {
                                confirm: {
                                    text: 'OK',
                                    value: true,
                                }
                            }
                        });
                    }
                });
            });


        });



        $(document).ready(function() {
            // Lorsque l'une des checkboxes est cochée
            $('.single-checkbox').on('change', function() {
                // Si cette checkbox est cochée, décocher toutes les autres
                if ($(this).is(':checked')) {
                    $('.single-checkbox').not(this).prop('checked', false);

                    // Cacher le message d'avertissement si une seule case est cochée
                    $('#checkboxWarning').hide();
                } else {
                    // Si aucune checkbox n'est cochée, cacher le message
                    $('#checkboxWarning').hide();
                }
            });
        });
    </script>
@endsection
