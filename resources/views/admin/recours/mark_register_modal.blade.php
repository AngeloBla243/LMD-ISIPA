<div class="modal-header">
    <h5 class="modal-title">Note : {{ $subject->subject->name ?? 'Inconnu' }} pour {{ $student->name }}
        {{ $student->last_name }}</< /h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    {{-- Affiche l'ancienne note si elle existe --}}
    <div class="alert alert-info">
        <strong>
            Travaux : {{ $mark->class_work ?? 'Non enregistré' }}
            | Examen : {{ $mark->exam ?? 'Non enregistré' }}
        </strong>
    </div>
    <div class="modal-body">
        <form method="post" action="{{ route('admin.recours.update_mark') }}" id="singleMarkForm">
            @csrf
            <input type="hidden" name="student_id" value="{{ $student->id }}">
            <input type="hidden" name="class_id" value="{{ $subject->class_id }}">
            <input type="hidden" name="exam_id" value="{{ $subject->exam_id }}">
            <input type="hidden" name="subject_id" value="{{ $subject->subject_id }}">
            <input type="hidden" name="academic_year_id"
                value="{{ $subject->academic_year_id ?? ($mark->academic_year_id ?? '') }}">


            <div class="form-group">
                <label for="class_work">Travaux (max 20)</label>
                <input type="number" name="class_work" id="class_work" class="form-control"
                    value="{{ $mark->class_work ?? '' }}" min="0" max="10" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="exam">Examen (max 20)</label>
                <input type="number" name="exam" id="exam" class="form-control"
                    value="{{ $mark->exam ?? '' }}" min="0" max="10" step="0.01" required>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Enregistrer</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </form>
    </div>

    <script>
        $(document).on('submit', '#singleMarkForm', function(e) {
            e.preventDefault();
            let form = $(this);
            $.post(form.attr('action'), form.serialize(), function(response) {
                alert(response.message);
                $('#markModal').modal('hide');
                // Optionnel : recharger la page ou mettre à jour le tableau
            });
        });
    </script>
