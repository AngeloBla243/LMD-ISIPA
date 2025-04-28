@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content">
            <h2>Soumission de mémoire</h2>

            <div class="card">
                <div class="card-header">
                    Formulaire de soumission
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('thesis.submit') }}" enctype="multipart/form-data">
                        @csrf

                        <table class="table">
                            <tr>
                                <th>Sujet du mémoire <span style="color:red">*</span></th>
                                <td><input type="text" class="form-control" name="subject" required></td>
                            </tr>
                            <tr>
                                <th>Fichier PDF <span style="color:red">*</span></th>
                                <td><input type="file" class="form-control" name="thesis_file" accept=".pdf,.docx"
                                        required>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <button type="submit" class="btn btn-primary">Soumettre</button>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger mt-3">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </section>
    </div>
@endsection
