<!-- Modal de confirmacion de borrado de entidad -->
<div class="modal fade" id="modalConfirm" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content modal-content bg-danger">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Esta acción moverá el recurso a la papelera y dejará de estar visible en el sistema. Sin
                    embargo, como utilizamos eliminación suave o controlada, podrás recuperarla más adelante si
                    es necesario. Para restaurarla, utilizá la opción de
                    restauración disponible en el sistema.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Cancelar</button>

                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-light">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
