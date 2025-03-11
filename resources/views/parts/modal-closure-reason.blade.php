<div class="modal fade" id="closureReason" tabindex="-1" role="dialog" aria-labelledby="closureReasonLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="closureReasonLabel">Confirmar Envío</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="closure-reason" class="form-horizontal" method="POST"
                    action="{{ route('form_submissions.update', $formSubmission->id) }}">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="form_submission_id" value="{{ $formSubmission->id }}">
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <input type="text" name="closure_reason" value="{{ old('closure_reason') }}">

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <!-- Botón para enviar el mensaje después de confirmar -->
                <button type="submit" class="btn btn-primary" form="message-form">Cerrar Formulario</button>
            </div>
        </div>
    </div>
</div>
