import { useRef } from 'react';
import Modal from '@/Components/Modal';
import DangerButton from '@/Components/DangerButton';
import SecondaryButton from '@/Components/SecondaryButton';
import { AlertTriangle } from 'lucide-react';

/**
 * Modal de confirmación para eliminar un recurso.
 * Props:
 *   show       — boolean, si el modal está abierto
 *   onClose    — función para cerrar el modal sin confirmar
 *   onConfirm  — función llamada al confirmar la eliminación
 *   title      — título del modal (default: "Confirmar eliminación")
 *   message    — mensaje descriptivo (default: genérico)
 *   processing — boolean, deshabilita el botón mientras se procesa
 */
export default function ConfirmDeleteModal({
    show,
    onClose,
    onConfirm,
    title = 'Confirmar eliminación',
    message = '¿Estás seguro de que deseas eliminar este registro? Esta acción no se puede deshacer fácilmente.',
    processing = false,
}) {
    const cancelButtonRef = useRef(null);

    return (
        <Modal show={show} onClose={onClose} initialFocus={cancelButtonRef}>
            <div className="p-6">
                <div className="flex items-start gap-4">
                    <div className="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-red-100">
                        <AlertTriangle className="h-5 w-5 text-red-600" />
                    </div>
                    <div>
                        <h2 className="text-lg font-semibold text-gray-900">{title}</h2>
                        <p className="mt-1 text-sm text-gray-600">{message}</p>
                    </div>
                </div>

                <div className="mt-6 flex justify-end gap-3">
                    <SecondaryButton ref={cancelButtonRef} onClick={onClose}>
                        Cancelar
                    </SecondaryButton>
                    <DangerButton onClick={onConfirm} disabled={processing}>
                        {processing ? 'Eliminando...' : 'Eliminar'}
                    </DangerButton>
                </div>
            </div>
        </Modal>
    );
}
