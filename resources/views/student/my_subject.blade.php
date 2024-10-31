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
    </style>
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>My Subject</h1>
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







                        @include('_message')

                        <!-- /.card -->

                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">My Subject</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body p-0" style="overflow: auto;">
                                <table class="table styled-table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Subject Name</th>
                                            <th>Subject Type</th>
                                            <th>Recours</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($getRecord as $value)
                                            <tr>
                                                <td>{{ $value->subject_name }}</td>
                                                <td>{{ $value->subject_type }}</td>
                                                <td style="min-width: 300px;"><a href="" data-toggle="modal"
                                                        data-target="#addFeesModal"
                                                        data-subjectid="{{ $value->subject_id }}"
                                                        class="btn btn-info openModal"><i class="fas fa-edit"></i> Faire
                                                        votre recours</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>


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
                    <input type="hidden" name="subject_id" id="subject_id">


                    <div class="container">
                        <div class="header">
                            <h2>I.S.I.P.A</h2>
                            <h3>Secrétariat Général Académique <br> Bureau du Jury</h3>
                        </div>

                        <div class="form-group">
                            <label>Section / Département :</label>
                            <input type="text" name="section" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Promotion : <b>{{ Auth::user()->class->name }}</b></label>
                        </div>

                        <div class="form-group">
                            <label>Nom et Post-nom : <b>{{ Auth::user()->name }} {{ Auth::user()->last_name }}</b></label>

                        </div>
                        <h4 class="section-title">I. Objet (Prière de cocher la case concernée)</h4>
                        <div class="checkbox-group">
                            <div>
                                <input type="checkbox" name="objet[]" value="omission-cotes" class="single-checkbox">
                                Omission des cotes sur la grille de délibération
                            </div>
                            <div>
                                <input type="checkbox" name="objet[]" value="omission-nom" class="single-checkbox"> Omission
                                du nom sur la grille de délibération
                            </div>
                            <div>
                                <input type="checkbox" name="objet[]" value="calcul-errone" class="single-checkbox"> Calcul
                                erroné des cotes
                            </div>
                            <div>
                                <input type="checkbox" name="objet[]" value="non-transmission" class="single-checkbox"> Non
                                transmission des cotes au Jury
                            </div>
                            <div>
                                <input type="checkbox" name="objet[]" value="transcription-erronne"class="single-checkbox">
                                Transcription erronée des cotes par l'enseignant (titulaire) ou le secrétaire du jury
                            </div>
                            <div>
                                <input type="checkbox" name="objet[]" value="omission-correction" class="single-checkbox">
                                Omission de la correction des copies
                            </div>
                            <div>
                                <input type="checkbox" name="objet[]" value="id-confuse" class="single-checkbox">
                                Identification confuse des copies
                            </div>

                            <!-- Ajoute les autres options -->
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
                var className = "{{ Auth::user()->class->name }}"; // Classe de l'étudiant
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
