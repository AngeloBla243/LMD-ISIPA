@extends('layouts.app')

@section('content')
    <div class="content-wrapper py-4">
        <div class="container">
            <div class="row g-3 flex-lg-row flex-column">
                <!-- Zone de question -->
                <div class="col-lg-7">
                    <div class="card shadow h-100">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0">{{ $exam->title }}</h4>
                        </div>
                        <div class="card-body">
                            <form id="exam-form" method="POST" action="{{ route('student.exams.submit', $exam->id) }}">
                                @csrf

                                @foreach ($exam->questions as $index => $question)
                                    <div class="exam-question" data-index="{{ $index }}" style="display: none;">
                                        <h5 class="mb-3">
                                            <span class="badge bg-dark me-2">{{ chr(65 + $index) }}</span>
                                            {{ $question->question_text }}
                                        </h5>
                                        @foreach ($question->choices as $cIndex => $choice)
                                            <div class="form-check mb-2">
                                                <input type="radio" name="answers[{{ $question->id }}]"
                                                    value="{{ $choice->id }}" class="form-check-input"
                                                    id="choice-{{ $choice->id }}">
                                                <label class="form-check-label" for="choice-{{ $choice->id }}">
                                                    {{ chr(65 + $cIndex) }}. {{ $choice->choice_text }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach

                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" id="prev-btn" class="btn btn-secondary" style="display:none;">⬅
                                        Previous</button>
                                    <button type="button" id="next-btn" class="btn btn-primary">Next ➡</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Zone navigation & contrôle -->
                <div class="col-lg-5">
                    <div class="card shadow h-100">
                        <div class="card-header bg-light">
                            <div class="d-flex flex-column align-items-center">
                                <div id="exam-timer" class="fw-bold fs-3 mb-2" data-time-left="{{ $remaining_time * 60 }}"
                                    data-exam-id="{{ $exam->id }}"></div>
                                <div id="timer-alert" class="text-danger fw-bold" style="display:none;"></div>
                                <div class="mt-3">
                                    <video id="student-camera" width="320" height="240" autoplay muted playsinline
                                        style="border: 1px solid #ccc; border-radius: 8px;"></video>
                                    <p id="camera-status" class="text-muted mt-2 text-center">🎥 Caméra activée</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="mb-3">🧩 Carte des questions</h5>
                            <div id="question-indicators" class="mb-4 d-flex flex-wrap gap-2 justify-content-center">
                                @foreach ($exam->questions as $index => $question)
                                    <span class="question-indicator badge rounded-circle" data-index="{{ $index }}">
                                        {{ $index + 1 }}
                                    </span>
                                @endforeach
                            </div>
                            <button type="button" id="submit-btn" class="btn btn-danger btn-lg w-100 py-3 mt-2">
                                FINISH
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Face API -->
    <script defer src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', async function() {

            // === TIMER ===
            const timerElement = document.getElementById('exam-timer');
            const timerAlert = document.getElementById('timer-alert');
            let timerInterval = null;
            let timeLeft = parseInt(timerElement.dataset.timeLeft);
            const totalTime = timeLeft;

            function formatTime(sec) {
                if (sec >= 3600) {
                    const h = Math.floor(sec / 3600);
                    const m = Math.floor((sec % 3600) / 60);
                    const s = sec % 60;
                    return `${h.toString().padStart(2,'0')}:${m.toString().padStart(2,'0')}:${s.toString().padStart(2,'0')}`;
                } else {
                    const m = Math.floor(sec / 60);
                    const s = sec % 60;
                    return `${m.toString().padStart(2,'0')}:${s.toString().padStart(2,'0')}`;
                }
            }

            function updateTimerDisplay() {
                timerElement.textContent = formatTime(timeLeft);
                if (timeLeft <= totalTime * 0.2) {
                    timerElement.classList.add('text-danger');
                    timerAlert.style.display = '';
                    timerAlert.textContent = "⏰ Attention : il reste peu de temps !";
                } else {
                    timerElement.classList.remove('text-danger');
                    timerAlert.style.display = 'none';
                }
            }
            updateTimerDisplay();
            timerInterval = setInterval(() => {
                timeLeft--;
                updateTimerDisplay();
                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    Swal.fire({
                        title: 'Temps écoulé !',
                        text: 'Votre examen sera soumis automatiquement.',
                        icon: 'warning',
                        showConfirmButton: false,
                        timer: 2000
                    });
                    setTimeout(() => submitExamForm(), 2000);
                }
            }, 1000);


            // === CAMERA ET SURVEILLANCE VISAGE ===

            const video = document.getElementById('student-camera');
            const cameraStatus = document.getElementById('camera-status');
            let absenceSeconds = 0;
            const absenceLimit = 10; // secondes sans visage tolérées

            async function initCameraAndModels() {
                try {
                    // Accès caméra webcam, pas audio
                    const stream = await navigator.mediaDevices.getUserMedia({
                        video: true,
                        audio: false
                    });
                    video.srcObject = stream;
                    cameraStatus.textContent = "🎥 Caméra activée, détection visage en cours...";

                    // Chargement des modèles (modèle TinyFaceDetector)
                    await faceapi.nets.tinyFaceDetector.loadFromUri(
                        'https://cdn.jsdelivr.net/npm/@vladmandic/face-api/model/');

                    startFaceDetectionLoop();
                } catch (error) {
                    cameraStatus.textContent = "⚠️ Caméra indisponible : " + error.message;
                    Swal.fire('Erreur', 'La caméra est nécessaire pour cet examen.', 'error').then(() => {
                        window.location.href = "{{ route('student.exams.index') }}";
                    });
                }
            }

            async function startFaceDetectionLoop() {
                const options = new faceapi.TinyFaceDetectorOptions({
                    inputSize: 160,
                    scoreThreshold: 0.5
                });

                setInterval(async () => {
                    const detection = await faceapi.detectSingleFace(video, options);

                    if (detection) {
                        absenceSeconds = 0;
                        cameraStatus.textContent = "🎥 Visage détecté";
                        cameraStatus.classList.remove('text-danger');
                        cameraStatus.classList.add('text-success');
                    } else {
                        absenceSeconds++;
                        cameraStatus.textContent =
                            `🚨 Visage non détecté depuis ${absenceSeconds} seconde(s)`;
                        cameraStatus.classList.remove('text-success');
                        cameraStatus.classList.add('text-danger');

                        if (absenceSeconds >= absenceLimit) {
                            clearInterval(timerInterval);
                            Swal.fire({
                                icon: 'error',
                                title: 'Examen annulé',
                                text: 'Votre visage est absent depuis trop longtemps.',
                                allowOutsideClick: false,
                                allowEscapeKey: false
                            }).then(() => {
                                window.location.href =
                                    "{{ route('student.exams.index') }}";
                            });
                        }
                    }
                }, 1000);
            }

            initCameraAndModels();


            // === NAVIGATION QUESTIONS ===

            const questions = document.querySelectorAll('.exam-question');
            const indicators = document.querySelectorAll('.question-indicator');
            let current = 0;

            function showQuestion(idx) {
                questions.forEach((q, i) => q.style.display = (i === idx ? 'block' : 'none'));
                indicators.forEach((ind, i) => {
                    ind.classList.remove('bg-primary', 'text-white', 'bg-success', 'bg-light',
                        'text-dark');
                    if (i === idx) ind.classList.add('bg-primary', 'text-white');
                    const inputs = questions[i].querySelectorAll('input[type=radio]');
                    if ([...inputs].some(input => input.checked)) {
                        ind.classList.add('bg-success', 'text-white');
                    } else {
                        ind.classList.add('bg-light', 'text-dark');
                    }
                });
                document.getElementById('prev-btn').style.display = idx === 0 ? 'none' : '';
                document.getElementById('next-btn').style.display = idx === questions.length - 1 ? 'none' : '';
            }
            showQuestion(current);

            document.getElementById('prev-btn').onclick = function() {
                if (current > 0) {
                    current--;
                    showQuestion(current);
                }
            };
            document.getElementById('next-btn').onclick = function() {
                if (current < questions.length - 1) {
                    current++;
                    showQuestion(current);
                }
            };
            indicators.forEach(ind => {
                ind.onclick = function() {
                    const idx = parseInt(this.dataset.index);
                    current = idx;
                    showQuestion(current);
                };
            });
            // Met à jour l’affichage si une réponse est choisie
            questions.forEach((q, i) => {
                q.querySelectorAll('input[type=radio]').forEach(input => {
                    input.addEventListener('change', () => showQuestion(current));
                });
            });


            // === SOUMISSION ===

            const examForm = document.getElementById('exam-form');
            document.getElementById('submit-btn').onclick = function() {
                if (timerInterval) clearInterval(timerInterval);
                submitExamForm();
            };
            if (examForm) {
                examForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    if (timerInterval) clearInterval(timerInterval);
                    submitExamForm();
                });
            }

            function submitExamForm() {
                const form = document.getElementById('exam-form');
                const formData = new FormData(form);

                @foreach ($exam->questions as $question)
                    if (!formData.has('answers[{{ $question->id }}]')) {
                        formData.append('answers[{{ $question->id }}]', '');
                    }
                @endforeach

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                }).then(async response => {
                    if (!response.ok) {
                        if (response.status === 422) {
                            const data = await response.json();
                            let errorMsg = 'Veuillez répondre à toutes les questions.';
                            if (data && data.errors) {
                                errorMsg = Object.values(data.errors).flat().join('\n');
                            }
                            Swal.fire({
                                title: 'Erreur',
                                text: errorMsg,
                                icon: 'error'
                            });
                        } else {
                            Swal.fire({
                                title: 'Erreur',
                                text: "Une erreur est survenue lors de la soumission.",
                                icon: 'error'
                            });
                        }
                        throw new Error('Validation failed');
                    }
                    return response.json();
                }).then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Examen terminé !',
                            text: 'Votre score total : ' + data.score,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = data.redirect;
                        });
                    }
                });
            }

            // === Bloquer navigation & changement onglet ===

            window.addEventListener('beforeunload', function(e) {
                e.preventDefault();
                e.returnValue = '';
                return 'Quitter cette page annulera votre examen !';
            });

            document.addEventListener('visibilitychange', function() {
                if (document.hidden) {
                    if (timerInterval) clearInterval(timerInterval);
                    Swal.fire({
                        title: 'Attention !',
                        text: 'Vous avez quitté l\'onglet, votre examen va être soumis.',
                        icon: 'warning',
                        showConfirmButton: false,
                        timer: 2000
                    });
                    setTimeout(() => submitExamForm(), 2000);
                }
            });

        });
    </script>
    <style>
        /* Responsive deux colonnes */
        @media (max-width: 991.98px) {
            .flex-lg-row {
                flex-direction: column !important;
            }
        }

        .question-indicator {
            cursor: pointer;
            border: 2px solid #007bff;
            background: #f8f9fa;
            transition: background 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            width: 38px;
            height: 38px;
            font-size: 1.1em;
            font-weight: bold;
        }

        .question-indicator.bg-success {
            border-color: #28a745 !important;
            background: #28a745 !important;
            color: #fff !important;
        }

        .question-indicator.bg-primary {
            border-color: #007bff !important;
            background: #007bff !important;
            color: #fff !important;
        }

        .question-indicator.bg-light {
            border-color: #ccc !important;
            background: #f8f9fa !important;
            color: #222 !important;
        }
    </style>
@endsection
