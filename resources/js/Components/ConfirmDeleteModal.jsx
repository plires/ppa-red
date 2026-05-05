import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogDescription,
    DialogFooter,
} from '@/Components/ui/dialog';
import { AlertTriangle } from 'lucide-react';

export default function ConfirmDeleteModal({
    show,
    onClose,
    onConfirm,
    title = 'Confirmar eliminación',
    message = '¿Estás seguro de que deseas eliminar este registro? Esta acción no se puede deshacer fácilmente.',
    processing = false,
}) {
    return (
        <Dialog open={show} onOpenChange={(open) => { if (!open) onClose(); }}>
            <DialogContent className="sm:max-w-md">
                <DialogHeader>
                    <div className="flex items-start gap-4">
                        <div className="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-red-100">
                            <AlertTriangle className="h-5 w-5 text-red-600" />
                        </div>
                        <div className="flex-1 pt-0.5">
                            <DialogTitle>{title}</DialogTitle>
                            <DialogDescription className="mt-1">{message}</DialogDescription>
                        </div>
                    </div>
                </DialogHeader>

                <DialogFooter className="mt-2 gap-2 sm:gap-2">
                    <button
                        onClick={onClose}
                        className="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50"
                    >
                        Cancelar
                    </button>
                    <button
                        onClick={onConfirm}
                        disabled={processing}
                        className="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 disabled:opacity-60"
                    >
                        {processing ? 'Eliminando...' : 'Eliminar'}
                    </button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    );
}
