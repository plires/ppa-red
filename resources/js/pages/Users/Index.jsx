import { useMemo, useState } from 'react';
import { Link, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import DataTable from '@/Components/DataTable';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal';
import { createColumnHelper } from '@tanstack/react-table';
import { Pencil, Trash2, Plus } from 'lucide-react';

const col = createColumnHelper();

export default function Index({ users }) {
    const [deleting, setDeleting] = useState(null);

    const columns = useMemo(
        () => [
            col.accessor('name', { header: 'Nombre' }),
            col.accessor('email', { header: 'Email' }),
            col.accessor('phone', { header: 'Teléfono' }),
            col.accessor('role', {
                header: 'Rol',
                cell: ({ getValue }) => (
                    <span className="capitalize">{getValue()}</span>
                ),
            }),
            col.display({
                id: 'actions',
                header: 'Acciones',
                size: 100,
                cell: ({ row }) => (
                    <div className="flex items-center gap-2">
                        <Link
                            href={route('users.edit', row.original.id)}
                            className="rounded p-1 text-blue-500 hover:bg-blue-50 hover:text-blue-700"
                            title="Editar"
                        >
                            <Pencil className="h-4 w-4" />
                        </Link>
                        <button
                            onClick={() => setDeleting(row.original)}
                            className="rounded p-1 text-red-400 hover:bg-red-50 hover:text-red-600"
                            title="Eliminar"
                        >
                            <Trash2 className="h-4 w-4" />
                        </button>
                    </div>
                ),
            }),
        ],
        [],
    );

    function handleDelete() {
        router.delete(route('users.destroy', deleting.id), {
            onFinish: () => setDeleting(null),
        });
    }

    return (
        <AuthenticatedLayout header="Usuarios">
            <div className="space-y-4">
                <div className="flex items-center justify-between">
                    <h1 className="text-xl font-semibold text-gray-800">Usuarios</h1>
                    <div className="flex gap-2">
                        <Link
                            href={route('users.trashed')}
                            className="rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-600 hover:bg-gray-50"
                        >
                            Papelera
                        </Link>
                        <Link
                            href={route('users.create')}
                            className="flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-white hover:opacity-90"
                            style={{ background: 'linear-gradient(90deg, #FD3C00, #FF7500)' }}
                        >
                            <Plus className="h-4 w-4" />
                            Nuevo Usuario
                        </Link>
                    </div>
                </div>

                <DataTable data={users} columns={columns} />
            </div>

            <ConfirmDeleteModal
                show={!!deleting}
                onClose={() => setDeleting(null)}
                onConfirm={handleDelete}
                title="Eliminar usuario"
                message={`¿Eliminar al usuario "${deleting?.name}"?`}
            />
        </AuthenticatedLayout>
    );
}
