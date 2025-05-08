@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Add New Class</h1>
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
                                            <option value="">Sélectionner une année</option>
                                            @foreach ($academicYears as $year)
                                                <option value="{{ $year->id }}">{{ $year->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Class Name</label>
                                        <input type="text" class="form-control" name="name" required
                                            placeholder="Class Name">
                                    </div>

                                    <div class="form-group">
                                        <label>Option</label>
                                        <select class="form-control" name="opt">
                                            <option value="">Sélectionnez une option</option>
                                            <option value="Administration Réseau et Telecommunication">Administration Réseau
                                                et Télécommunication</option>
                                            <option value="Administration des Bases de données">Administration des Bases de
                                                données</option>
                                            <option value="Intelligence artificielle">Intelligence artificielle</option>
                                            <option value="Génie Logiciel">Génie Logiciel</option>
                                            <option value="Fiscalité">Fiscalité</option>
                                            <option value="Gestion Financière">Gestion Financière</option>
                                            <option value="Commerce extérieur">Commerce extérieur</option>
                                            <option value="Communication numérique">Communication numérique</option>
                                            <option value="Science informatique">Science informatique</option>

                                        </select>

                                    </div>


                                    <div class="form-group">
                                        <label>Amount ($)</label>
                                        <input type="number" class="form-control" name="amount" required
                                            placeholder="Amount">
                                    </div>


                                    <div class="form-group">
                                        <label>Status</label>
                                        <select class="form-control" name="status">
                                            <option value="0">Active</option>
                                            <option value="1">Inactive</option>
                                        </select>

                                    </div>


                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
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
