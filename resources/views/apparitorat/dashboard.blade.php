@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <h1>{{ $header_title }}</h1>
        </div>
        <section class="content container-fluid">
            <div class="small-box bg-primary mb-3">
                <div class="inner">
                    <h3>{{ $TotalStudent }}</h3>
                    <p>Total des étudiants</p>
                </div>
                <div class="icon"><i class="fas fa-users"></i></div>
            </div>

            <div>
                <h3>Liste complète des étudiants</h3>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#ID</th>
                            <th>Nom</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $student)
                            <tr>
                                <td>{{ $student->id }}</td>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->email }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
