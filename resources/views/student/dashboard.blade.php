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
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h1 class="m-0">Dashboard</h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">

                    <!-- Carte Profil Utilisateur -->
                    <div class="card card-body box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle" src="{{ Auth::user()->getProfileDirect() }}"
                                alt="User profile picture">
                        </div>

                        <h3 class="profile-username text-center card-title mt-2">{{ Auth::user()->name }}
                            {{ Auth::user()->last_name }}</h3>

                        <p class="text-muted text-center">Software Engineer</p>

                        <ul class="list-group list-group-unbordered mb-3">
                            <b style="text-align: center"><i class="fa-solid fa-envelope"></i> {{ Auth::user()->email }}</b>
                            <b style="text-align: center"><i class="fa-solid fa-phone"></i>
                                {{ Auth::user()->mobile_number }}</b>

                            <a href="javascript:void(0)"
                                class="mt-2 waves-effect waves-dark btn btn-blue btn-md btn-rounded">
                                ID : {{ Auth::user()->admission_number }}
                            </a>

                            <b style="text-align: center">{{ $TotalSubject }}</b>

                            <p style="text-align: center">Cours suivis pour cette année</p>
                        </ul>
                    </div>

                    <!-- Carte Cours -->
                    <div class="card card-body post clearfix">
                        <h5><i class="fa fa-list-alt"></i> Mes Cours</h5>
                        <p>Voici la liste de tous vos cours dans votre promotion pour cette année académique :
                            {{ $TotalSubject }}
                        </p>

                        <!-- Liste des cours -->
                        <ul class="list-group1">
                            @foreach ($getRecord as $course)
                                <li class="list-group-item1 d-flex align-items-center">
                                    <div class="course-icon">
                                        <i class="fas fa-book"></i>
                                    </div>
                                    <span>{{ $course->subject_name }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            </div>
        </section>
    </div>
@endsection
