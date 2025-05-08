@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Class</h1>
                    </div>

                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <form method="post" action="">
                                {{ csrf_field() }}
                                <div class="card-body">

                                    <div class="mb-3">
                                        <label class="form-label">Année Académique</label>
                                        <select name="academic_year_id" class="form-select" required>
                                            @foreach ($academicYears as $year)
                                                <option value="{{ $year->id }}"
                                                    {{ $getRecord->academic_year_id == $year->id ? 'selected' : '' }}>
                                                    {{ $year->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Class Name</label>
                                        <input type="text" class="form-control" value="{{ $getRecord->name }}"
                                            name="name" required placeholder="Class Name">
                                    </div>

                                    <div class="form-group">
                                        <label>Option</label>
                                        <select class="form-control" name="opt">
                                            <option
                                                {{ $getRecord->opt == 'Administration Réseau et Telecommunication' ? 'selected' : '' }}
                                                value="Administration Réseau et Telecommunication">Administration Réseau et
                                                Télécommunication</option>
                                            <option
                                                {{ $getRecord->opt == 'Administration des Bases de données' ? 'selected' : '' }}
                                                value="Administration des Bases de données">Administration des Bases de
                                                données</option>
                                            <option {{ $getRecord->opt == 'Intelligence artificielle' ? 'selected' : '' }}
                                                value="Intelligence artificielle">Intelligence artificielle</option>
                                            <option {{ $getRecord->opt == 'Génie Logiciel' ? 'selected' : '' }}
                                                value="Génie Logiciel">Génie Logiciel</option>
                                            <option {{ $getRecord->opt == 'Fiscalité' ? 'selected' : '' }}
                                                value="Fiscalite">Fiscalité</option>
                                            <option {{ $getRecord->opt == 'Gestion Financière' ? 'selected' : '' }}
                                                value="Gestion Financiere">Gestion Financière</option>
                                            <option {{ $getRecord->opt == 'Commerce extérieur' ? 'selected' : '' }}
                                                value="Commerce extérieur">Commerce extérieur</option>
                                            <option {{ $getRecord->opt == 'Communication numérique' ? 'selected' : '' }}
                                                value="Communication numérique">Communication numérique</option>
                                            <option {{ $getRecord->opt == 'Science informatique' ? 'selected' : '' }}
                                                value="Science informatique">Science informatique</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Amount ($)</label>
                                        <input type="number" class="form-control" name="amount"
                                            value="{{ $getRecord->amount }}" required placeholder="Amount">
                                    </div>

                                    <div class="form-group">
                                        <label>Status</label>
                                        <select class="form-control" name="status">
                                            <option {{ $getRecord->status == 0 ? 'selected' : '' }} value="0">Active
                                            </option>
                                            <option {{ $getRecord->status == 1 ? 'selected' : '' }} value="1">
                                                Inactive</option>
                                        </select>

                                    </div>


                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>


                    </div>
                    <!--/.col (left) -->
                    <!-- right column -->

                    <!--/.col (right) -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
