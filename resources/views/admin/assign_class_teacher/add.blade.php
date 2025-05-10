@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Assigner une classe à un enseignant</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            @include('_message')
                            <form id="assignClassForm" method="POST"
                                action="{{ route('admin.assign_class_teacher.add') }}">
                                {{ csrf_field() }}
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Année académique</label>
                                        <select name="academic_year_id" id="academicYear" class="form-control" required>
                                            <option value="">Sélectionner une année</option>
                                            @foreach ($academicYears as $year)
                                                <option value="{{ $year->id }}">{{ $year->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- <div class="form-group">
                                        <label>Classe</label>
                                        <select name="class_id" id="classSelect" class="form-control" required disabled>
                                            <option value="">Choisissez d'abord une année</option>
                                        </select>
                                    </div> --}}

                                    <div class="form-group">
                                        <label>Classe</label>
                                        <select name="class_id" id="classSelect" class="form-control" required>
                                            <option value="">Choisissez d'abord une année</option>
                                        </select>
                                    </div>


                                    <!-- Sélectionner un ou plusieurs enseignants -->
                                    <div class="form-group">
                                        <label for="teacher_id">Sélectionner un enseignant</label>
                                        @foreach ($getTeacher as $teacher)
                                            <div>
                                                <label style="font-weight: normal;">
                                                    <input type="checkbox" value="{{ $teacher->id }}" name="teacher_id[]">
                                                    {{ $teacher->name }} {{ $teacher->last_name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Statut -->
                                    <div class="form-group">
                                        <label for="status">Statut</label>
                                        <select class="form-control" name="status">
                                            <option value="0">Actif</option>
                                            <option value="1">Inactif</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Assigner la classe</button>

                                </div>
                            </form>


                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('script')
    <script>
        document.getElementById('academicYear').addEventListener('change', function() {
            const yearId = this.value;
            const classSelect = document.getElementById('classSelect');

            classSelect.innerHTML = '<option value="">Chargement...</option>';
            classSelect.disabled = true;

            if (!yearId) {
                classSelect.innerHTML = '<option value="">Veuillez d\'abord choisir une année</option>';
                return;
            }

            // Génération dynamique de l'URL avec la fonction Laravel url()
            const url = "{{ url('/admin/assign_class_teacher/get-classes') }}/" + yearId;

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur HTTP : ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    classSelect.innerHTML = '<option value="">Sélectionner une classe</option>';
                    data.forEach(cls => {
                        classSelect.innerHTML +=
                            `<option value="${cls.id}">${cls.name} ${cls.opt ? '(' + cls.opt + ')' : ''}</option>`;
                    });
                    classSelect.disabled = false;
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des classes :', error);
                    classSelect.innerHTML = `<option value="">Erreur de chargement (${error.message})</option>`;
                    classSelect.disabled = true;
                });
        });
    </script>
@endsection
