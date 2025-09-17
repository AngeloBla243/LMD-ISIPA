@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <h1>{{ $header_title }}</h1>
        </div>
        <section class="content">
            <div class="container-fluid">

                <!-- Carte total étudiants dans ce département -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $TotalStudent }}</h3>
                        <p>Total Étudiants dans ce département</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <a href="{{ url('admin/student/list') }}" class="small-box-footer">
                        Voir étudiants <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>

                <!-- Ajouter d'autres cartes/statistiques selon besoin -->

            </div>
        </section>
    </div>
@endsection
