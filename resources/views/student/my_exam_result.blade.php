@extends('layouts.app')

@section('style')
    <style>
        /* Conteneur général */
        .content-wrapper {
            padding: 2.5rem 0;
            min-height: 100vh;
            background: #f8fbff;
            font-family: 'Montserrat', sans-serif;
            color: #222;
        }

        h2 {
            text-align: center;
            color: #2176bd;
            font-weight: 700;
            margin-bottom: 2rem;
            font-size: 2rem;
        }

        /* Cards */
        .card {
            border-radius: 18px;
            box-shadow: 0 8px 24px rgba(14, 74, 107, 0.07);
            border: none;
            margin-bottom: 2rem;
            background: #fff;
        }

        .card-header {
            background: #2176bd;
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
            border-radius: 18px 18px 0 0 !important;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.5rem;
        }

        /* Badge styles */
        .badge {
            font-weight: 600;
            font-size: 0.85rem;
            padding: 0.4em 0.75em;
            border-radius: 1rem;
            min-width: 80px;
            text-align: center;
            display: inline-block;
            box-shadow: 0 1px 6px rgba(0, 0, 0, 0.1);
        }

        .bg-info {
            background-color: #3a99d8 !important;
            color: white !important;
            box-shadow: 0 2px 8px rgba(58, 153, 216, 0.5);
        }

        .bg-success {
            background-color: #27c381 !important;
            color: white !important;
            box-shadow: 0 2px 8px rgba(39, 195, 129, 0.5);
        }

        .bg-warning {
            background-color: #f0b619 !important;
            color: #222 !important;
            box-shadow: 0 2px 8px rgba(240, 182, 25, 0.5);
        }

        .bg-danger {
            background-color: #d9534f !important;
            color: white !important;
            box-shadow: 0 2px 8px rgba(217, 83, 79, 0.5);
        }

        .bg-secondary {
            background-color: #6c757d !important;
            color: white !important;
            box-shadow: 0 2px 6px rgba(108, 117, 125, 0.4);
        }

        /* Boutons */
        .btn {
            border-radius: 18px !important;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 0.38em 0.8em;
            display: inline-flex;
            align-items: center;
            gap: 0.35em;
            white-space: nowrap;
            transition: background-color 0.3s ease;
        }

        .btn i {
            font-size: 1.1em;
        }

        .btn-primary {
            background-color: #2176bd;
            border-color: #2176bd;
            color: white;
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background-color: #145a8d;
            border-color: #145a8d;
            color: white;
        }

        .btn-success {
            background-color: #27c381;
            border-color: #27c381;
            color: white;
        }

        .btn-success:hover,
        .btn-success:focus {
            background-color: #1db16a;
            border-color: #1db16a;
            color: white;
        }

        .btn-warning {
            background-color: #f0b619;
            border-color: #f0b619;
            color: #222;
        }

        .btn-warning:hover,
        .btn-warning:focus {
            background-color: #d4a30d;
            border-color: #d4a30d;
            color: #222;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover,
        .btn-secondary:focus {
            background-color: #5a6268;
            border-color: #5a6268;
            color: white;
        }

        /* Table */
        .table {
            margin-bottom: 0;
        }

        .styled-table {
            width: 100%;
            border-spacing: 0;
            border-collapse: separate;
            min-width: 720px;
            /* minimum width to allow horizontal scrolling */
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
            border-radius: 14px 14px 0 0;
            font-size: 0.95rem;
            color: #222;
        }

        .styled-table thead tr {
            background-color: #2176bd;
            color: white;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.07em;
        }

        .styled-table thead th,
        .styled-table tbody td {
            padding: 14px 18px;
            border-bottom: 1px solid #e3e9f1;
            border-right: 1px solid #e3e9f1;
            vertical-align: middle;
            white-space: nowrap;
        }

        .styled-table thead th:last-child,
        .styled-table tbody td:last-child {
            border-right: none;
        }

        .styled-table tbody tr:nth-child(even) {
            background-color: #f5faff;
        }

        .styled-table tbody tr:hover {
            background-color: #e9f2ff;
            cursor: pointer;
        }

        /* Responsive container for horizontal scroll */
        .table-responsive {
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch;
            border-radius: 14px;
            box-shadow: inset 0 0 8px #c8dafb;
            padding-bottom: 0.7rem;
        }

        /* Form */
        .form-group {
            margin-bottom: 1rem;
        }

        label {
            font-weight: 600;
            color: #2176bd;
        }

        input[type="checkbox"].single-checkbox {
            margin-right: 8px;
            transform: scale(1.1);
        }

        /* Sections */
        .header,
        .signature,
        .signature-section {
            text-align: center;
            margin-bottom: 1rem;
            font-weight: 700;
            color: #2176bd;
        }

        .signature-section,
        .signature {
            margin-top: 3rem;
        }

        .signature {
            margin-top: 4rem;
        }

        .note-section {
            font-size: 0.9rem;
            margin-top: 1.5rem;
            color: #555;
        }

        ul {
            padding-left: 1.25rem;
        }

        ul li {
            margin-bottom: 0.4rem;
        }

        /* Disabled link */
        a.disabled {
            pointer-events: none;
            cursor: default;
            opacity: 0.5;
        }

        /* Modal adjustments */
        .modal-content {
            border-radius: 15px;
            box-shadow: 0 6px 22px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            border-bottom: 1px solid #dee2e6;
            padding: 1rem 1.25rem;
            background-color: #2176bd;
            color: #fff;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header .btn-close {
            filter: invert(1);
        }

        /* Responsive tweaks */
        @media (max-width: 768px) {
            h2 {
                font-size: 1.6rem;
            }

            .styled-table {
                min-width: 600px;
            }

            .styled-table thead th,
            .styled-table tbody td {
                padding: 10px 12px;
                font-size: 0.85rem;
            }

            .btn {
                font-size: 0.85rem;
                padding: 0.3em 0.7em;
            }

            .badge {
                font-size: 0.75rem;
                min-width: 55px;
                padding: 0.3em 0.5em;
            }
        }

        @media (max-width: 480px) {
            .styled-table {
                min-width: 480px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header mb-4">
            <div class="container">
                <form method="GET" action="" class="mb-3">
                    <div class="input-group">
                        @if ($selectedAcademicYear && !$selectedAcademicYear->is_active)
                            <span class="text-warning d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Vous n'êtes pas dans l'année active !
                            </span>
                        @endif
                    </div>
                </form>

                <div class="card mb-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class="header">
                            Année Académique : {{ $selectedAcademicYear->name ?? 'N/A' }}
                        </div>
                        <a href="{{ route('student.year_result.print', ['academic_year_id' => $selectedAcademicYear->id, 'student_id' => Auth::id()]) }}"
                            target="_blank" class="btn btn-primary btn-sm">
                            <i class="fas fa-file-pdf me-1"></i> Relevé Annuel
                        </a>
                    </div>
                </div>

                @if (!empty($getRecord) && count($getRecord) > 0)
                    @foreach ($getRecord as $record)
                        <div class="card mb-4 shadow-sm">
                            <div
                                class="card-header d-flex justify-content-between align-items-center bg-secondary text-white">
                                <strong>{{ $record['exam_name'] }}</strong>

                                <a href="{{ url('student/my_exam_result/print?exam_id=' . $record['exam_id'] . '&student_id=' . Auth::user()->id) }}"
                                    target="_blank" class="btn btn-sm btn-light text-primary" title="Imprimer Relevé">
                                    <i class="fas fa-file-pdf"></i> Print
                                </a>
                            </div>

                            <div class="card-body p-0">
                                @if (!empty($record['subject']) && count($record['subject']) > 0)
                                    <div class="table-responsive rounded-3">
                                        <table class="table styled-table table-bordered table-striped mb-0">
                                            <thead>
                                                <tr>
                                                    <th style="min-width: 200px;">Ecs</th>
                                                    <th>Crédit Ec</th>
                                                    <th>Note / 20</th>
                                                    <th>Décision</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $Grandtotals = 0;
                                                    $creditsObtained = 0;
                                                    $totalWeight = 0;
                                                    $failCount = 0;
                                                @endphp
                                                @foreach ($record['subject'] as $subject)
                                                    @php
                                                        $Grandtotals += $subject['totals_score'] ?? 0;
                                                        $score = $subject['total_score'] ?? 0;
                                                        $passingMark = $subject['passing_mark'] ?? 0;
                                                        $weight = $subject['ponde'] ?? 0;
                                                        $totalWeight += $weight;
                                                        if ($score >= $passingMark) {
                                                            $creditsObtained += $weight;
                                                        } else {
                                                            $failCount++;
                                                        }
                                                    @endphp
                                                    <tr>
                                                        <td class="d-flex justify-content-between align-items-center"
                                                            style="width: 400px;">
                                                            <span>{{ $subject['subject_name'] ?? 'N/A' }}</span>
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-primary btn-recours"
                                                                data-exam-id="{{ $record['exam_id'] }}"
                                                                data-session="{{ $subject['session'] ?? 1 }}"
                                                                data-subject-id="{{ $subject['subject_id'] }}"
                                                                title="Faire un recours">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                        </td>
                                                        <td class="text-center">{{ $weight }}</td>
                                                        <td class="text-center">
                                                            @if ($score == 0)
                                                                <span class="text-muted" style="font-weight:700;">ND</span>
                                                            @else
                                                                @if ($score >= $passingMark)
                                                                    <span class="text-success"
                                                                        style="font-weight:700;">{{ $score }}</span>
                                                                @else
                                                                    <span class="text-danger"
                                                                        style="font-weight:700;">{{ $score }}</span>
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            @if ($score >= $passingMark)
                                                                <span class="text-success"
                                                                    style="font-weight:700;">VAL</span>
                                                            @else
                                                                <span class="text-danger"
                                                                    style="font-weight:700;">NVL</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-info m-3">
                                        Aucun résultat enregistré pour cette session.
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="alert alert-warning text-center mt-4">
                        Aucun résultat disponible pour le moment.
                    </div>
                @endif
            </div>
        </section>

        {{-- Modal Recours --}}
        <div class="modal fade" id="recoursModal" tabindex="-1" aria-labelledby="recoursModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 shadow-lg">
                    <div
                        class="modal-header bg-primary text-white rounded-top-4 d-flex justify-content-between align-items-center">
                        <h5 class="modal-title" id="recoursModalLabel">Recours</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>


                    <form id="recoursForm" action="{{ route('student.my_subject') }}" method="POST" class="p-4">
                        @csrf

                        <input type="hidden" name="exam_id" id="modal_exam_id" />
                        <input type="hidden" name="session" id="modal_session" />
                        <input type="hidden" name="subject_id" id="modal_subject_id" />
                        <input type="hidden" name="academic_year_id" id="modal_academic_year_id" />

                        <div class="text-center mb-4">
                            <h3 class="mb-0">I.S.I.P.A</h3>
                            <small class="text-primary fw-bold">Secrétariat Général - Bureau du Jury</small>
                        </div>

                        <div class="mb-3">
                            @php $currentClass = Auth::user()->getCurrentClass(); @endphp
                            @if ($currentClass)
                                <label><strong>Section / Département :</strong> {{ $currentClass->opt }} /
                                    {{ Auth::user()->departement ?? 'N/A' }}</label>
                                <label><strong>Promotion :</strong> {{ $currentClass->name }}
                                    {{ $currentClass->opt }}</label>
                            @else
                                <p class="text-danger fw-bold">Vous n'êtes affecté à aucune classe pour l'année académique
                                    en cours.</p>
                            @endif
                            <label><strong>Nom & Post-nom :</strong> {{ Auth::user()->name }}
                                {{ Auth::user()->last_name }}</label>
                        </div>

                        <h5 class="mb-3 text-decoration-underline">I. Objet (cochez la raison du recours)</h5>
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input single-checkbox" type="checkbox" name="objet[]"
                                    value="Omission des cotes sur la grille de délibération" id="obj1" />
                                <label class="form-check-label" for="obj1">Omission des cotes sur la grille de
                                    délibération</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input single-checkbox" type="checkbox" name="objet[]"
                                    value="Omission du nom sur la grille de délibération" id="obj2" />
                                <label class="form-check-label" for="obj2">Omission du nom sur la grille de
                                    délibération</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input single-checkbox" type="checkbox" name="objet[]"
                                    value="Calcul erroné des cotes" id="obj3" />
                                <label class="form-check-label" for="obj3">Calcul erroné des cotes</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input single-checkbox" type="checkbox" name="objet[]"
                                    value="Non transmission des cotes au Jury" id="obj4" />
                                <label class="form-check-label" for="obj4">Non transmission des cotes au Jury</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input single-checkbox" type="checkbox" name="objet[]"
                                    value="Transcription erronée par l'enseignant ou secrétaire" id="obj5" />
                                <label class="form-check-label" for="obj5">Transcription erronée par l'enseignant ou
                                    secrétaire</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input single-checkbox" type="checkbox" name="objet[]"
                                    value="Omission de la correction des copies" id="obj6" />
                                <label class="form-check-label" for="obj6">Omission de la correction des
                                    copies</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input single-checkbox" type="checkbox" name="objet[]"
                                    value="Identification confuse des copies" id="obj7" />
                                <label class="form-check-label" for="obj7">Identification confuse des copies</label>
                            </div>
                        </div>

                        <div class="note-section fst-italic">
                            <p><strong>NB :</strong></p>
                            <ul>
                                <li>Le recours est retourné au bureau du Jury sous 48h après retrait.</li>
                                <li>Tout recours sans motif cochée sera annulé automatiquement.</li>
                                <li>Recours possible par cours seulement.</li>
                                <li>Le recours ne garantit pas la validation.</li>
                            </ul>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary px-4">Soumettre</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Ouvre modal et pré-remplit les inputs
            document.querySelectorAll('.btn-recours').forEach(button => {
                button.addEventListener('click', () => {
                    const examId = button.dataset.examId;
                    const session = button.dataset.session;
                    const subjectId = button.dataset.subjectId;
                    const academicYearId = "{{ session('academic_year_id') }}";

                    document.getElementById('modal_exam_id').value = examId;
                    document.getElementById('modal_session').value = session;
                    document.getElementById('modal_subject_id').value = subjectId;
                    document.getElementById('modal_academic_year_id').value = academicYearId;

                    new bootstrap.Modal(document.getElementById('recoursModal')).show();
                });
            });

            // Limite à une seule checkbox cochée
            document.querySelectorAll('.single-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', () => {
                    if (checkbox.checked) {
                        document.querySelectorAll('.single-checkbox').forEach(cb => {
                            if (cb !== checkbox) cb.checked = false;
                        });
                    }
                });
            });

            // Gestion de la soumission en Ajax avec SweetAlert pour retours utilisateur
            document.getElementById('recoursForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const checkboxes = Array.from(this.querySelectorAll('input[name="objet[]"]'));
                if (!checkboxes.some(cb => cb.checked)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: 'Veuillez cocher au moins un motif pour le recours.',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name=csrf-token]').content,
                        "Accept": "application/json"
                    },
                    body: new FormData(this)
                }).then(response => {
                    if (!response.ok) throw new Error("Erreur réseau");
                    return response.json();
                }).then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Recours envoyé',
                            html: `Numéro: <b>${data.nextNumero}</b><br/>Session: <b>${data.session}</b>`
                        }).then(() => window.location.reload());
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: data.message || "Une erreur est survenue."
                        });
                    }
                }).catch(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: "Une erreur est survenue, veuillez réessayer plus tard."
                    });
                });
            });
        });
    </script>
@endsection
