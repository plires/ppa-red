<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirmar Envío</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas enviar el siguiente texto?</p>
                <p id="modalText"></p> <!-- Aquí se mostrará el valor del input -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <!-- Botón para enviar el mensaje después de confirmar -->
                <button type="submit" class="btn btn-primary" form="message-form">Confirmar Envío</button>
            </div>
        </div>
    </div>
</div>
