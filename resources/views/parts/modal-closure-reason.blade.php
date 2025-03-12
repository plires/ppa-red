<div class="modal fade" id="closureReason" tabindex="-1" role="dialog" aria-labelledby="closureReasonLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="closureReasonLabel">Razón de cierre</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="closure-reason" class="form-horizontal" method="POST"
                action="{{ route('form_submissions.update', $formSubmission->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">

                    <input type="hidden" name="form_submission_id" value="{{ $formSubmission->id }}">
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <textarea required class="form-control" name="closure_reason" rows="3"
                        placeholder="Escriba la razón de este cierre... (obligatorio) (no será visible por el usuario)">{{ old('closure_reason') }}</textarea>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" form="closure-reason">Cerrar Formulario</button>
                </div>
            </form>
        </div>
    </div>
</div>
