@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <h1>{{ $notice->title }}</h1>
                <p class="text-muted">{{ date('d-m-Y', strtotime($notice->notice_date)) }}</p>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        {!! $notice->message !!}
                    </div>
                    <div class="card-footer">
                        <a href="{{ url('student/my_notice_board') }}" class="btn btn-secondary">Retour à la liste</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
