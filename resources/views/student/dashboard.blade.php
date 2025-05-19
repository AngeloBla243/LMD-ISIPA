@extends('layouts.app')

@section('style')
    <style type="text/css">
        .container {
            max-width: 900px;
            margin: auto;
        }

        /* Styles pour les cartes */
        .card-body {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin-bottom: 20px;
            padding: 20px;
            background-color: #fff;
        }

        /* Espacement ajusté pour les éléments de la liste */
        .list-group-item1 {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 6px;
            border: 1px solid #dee2e6;
            transition: all 0.3s ease-in-out;
        }


        /* Effet de survol sur les éléments de la liste */
        .list-group-item1:hover {
            background-color: #e0f7fa;
            transform: translateX(5px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Style des icônes de cours */
        .course-icon {
            margin-right: 10px;
            font-size: 20px;
            color: #1e88e5;
            /* Couleur de l'icône bleue */
        }

        /* Effet d'ombre sur l'image de profil */
        .profile-user-img {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            /* Ombre subtile autour de l'image */
        }

        /* Style pour les titres */
        h1,
        h5 {
            color: #a90000;
            /* Couleur rouge personnalisée */
            font-weight: bold;
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 20px;
        }

        h5 {
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        /* Style du nom d'utilisateur */
        .profile-username {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
        }

        .text-muted {
            color: #6c757d !important;
        }

        /* Liste des informations utilisateur */
        .list-group-item {
            padding: 12px 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 10px;
            border: 1px solid #dee2e6;
        }

        /* Padding du ul ajusté */
        ul.list-group1 {
            padding-left: 10px;
            /* Ajout de padding à gauche */
        }

        /* Media query pour les grands écrans (ordinateurs) */
        @media (min-width: 992px) {
            .card-body {
                margin-bottom: 30px;
                padding: 30px;
            }
        }

        /* Media query pour les petits écrans (smartphones et tablettes) */
        @media (max-width: 768px) {
            .card-body {
                margin-bottom: 15px;
                padding: 15px;
            }

            h1 {
                font-size: 1.75rem;
            }

            h5 {
                font-size: 1.25rem;
            }
        }

        /* Dans votre fichier CSS */
        .input-group {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-select {
            border: 2px solid #007bff;
            border-radius: 25px;
            padding: 8px 15px;
        }

        .fa-exclamation-triangle {
            text-shadow: 0 2px 4px rgba(255, 193, 7, 0.3);
        }

        /* Style pour le bouton bleu */
        .btn-blue {
            --blue: #1e88e5;
            color: #fff;
            background-color: var(--blue);
            border-color: #1a73e8;
            padding: 12px 20px;
            border-radius: 60px;
            font-size: 16px;
            font-weight: 400;
            text-align: center;
            box-shadow: 0 8px 15px rgba(30, 136, 229, 0.3);
            transition: all 0.1s ease-out;
            cursor: pointer;
            display: inline-block;
            overflow: hidden;
            user-select: none;
            vertical-align: middle;
            z-index: 1;
            will-change: opacity, transform;
        }

        /* Effet au survol */
        .btn-blue:hover {
            background-color: darken(var(--blue), 10%);
            border-color: darken(#1a73e8, 10%);
        }

        /* Responsive: Écrans moyens (tablettes, petits ordinateurs portables) */
        @media (max-width: 992px) {
            .btn-blue {
                padding: 10px 18px;
                font-size: 14px;
                border-radius: 50px;
            }
        }

        /* Responsive: Écrans petits (mobiles) */
        @media (max-width: 576px) {
            .btn-blue {
                padding: 8px 15px;
                font-size: 12px;
                border-radius: 40px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content py-4">
            <div class="container">
                <div class="row justify-content-center g-4">

                    <!-- Carte Profil Utilisateur -->
                    <div class="col-md-4">
                        <div class="card shadow-sm rounded-4 border-0 text-center p-4">
                            <img class="profile-user-img img-fluid rounded-circle mx-auto mb-3"
                                src="{{ Auth::user()->getProfileDirect() }}" alt="Photo de profil"
                                style="width: 140px; height: 140px; object-fit: cover;">

                            <h3 class="profile-username fw-bold mb-1">{{ Auth::user()->name }} {{ Auth::user()->last_name }}
                            </h3>
                            <p class="text-muted mb-3">{{ Auth::user()->departement }}</p>

                            <ul class="list-group list-group-flush text-start mb-3">
                                <li class="list-group-item d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-envelope text-primary"></i>
                                    <span class="text-truncate">{{ Auth::user()->email }}</span>
                                </li>
                                <li class="list-group-item d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-phone text-success"></i>
                                    <span>{{ Auth::user()->mobile_number }}</span>
                                </li>
                                <li class="list-group-item text-center">
                                    <span class="badge bg-info fs-6 px-3 py-2 rounded-pill">
                                        ID : {{ Auth::user()->admission_number }}
                                    </span>
                                </li>
                                <li class="list-group-item text-center">
                                    <span class="fw-semibold fs-5">{{ $TotalSubject }}</span><br>
                                    <small class="text-muted">Cours suivis cette année</small>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Carte Cours -->
                    <div class="col-md-8">
                        <div class="card shadow-sm rounded-4 border-0 p-4">
                            <h5 class="fw-bold mb-3 text-primary">
                                <i class="fa-solid fa-book-open-reader me-2"></i> Mes Cours
                            </h5>
                            <p class="mb-4 text-secondary">
                                Voici la liste de tous vos cours dans votre promotion pour cette année académique :
                                <strong>{{ $TotalSubject }}</strong>
                            </p>

                            <!-- Liste des cours -->
                            <ul class="list-group">
                                @foreach ($getRecord as $course)
                                    <li class="list-group-item d-flex align-items-center gap-3">
                                        <div class="course-icon bg-primary text-white rounded-circle d-flex justify-content-center align-items-center"
                                            style="width: 36px; height: 36px;">
                                            <i class="fa-solid fa-book-open-reader"></i>
                                        </div>
                                        <span class="flex-grow-1">{{ $course->subject_name }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
@endsection
@section('script')
    <script>
        document.getElementById('academicYearSelect').addEventListener('change', function() {
            this.form.submit();
        });
    </script>
@endsection
