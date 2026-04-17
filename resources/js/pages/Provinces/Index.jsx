import { useMemo, useState } from 'react';
import { Link, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import DataTable from '@/Components/DataTable';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal';
import { createColumnHelper } from '@tanstack/react-table';
import { Pencil, Trash2, Plus, Eye } from 'lucide-react';

const col = createColumnHelper();

export default function Index({ provinces }) {
    const [deleting, setDeleting] = useState(null);

    const columns = useMemo(
        () => [
            col.accessor('name', { header: 'Nombre' }),
            col.display({
                id: 'actions',
                header: 'Acciones',
                size: 140,
                cell: ({ row }) => (
                    <div className="flex items-center gap-2">
                        <Link
                            href={route('provinces.show', row.original.id)}
                            className="rounded p-1 text-gray-500 hover:bg-gray-100 hover:text-gray-700"
                            title="Ver"
                        >
                            <Eye className="h-4 w-4" />
                        </Link>
                        <Link
                            href={route('provinces.edit', row.original.id)}
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
        router.delete(route('provinces.destroy', deleting.id), {
            onFinish: () => setDeleting(null),
        });
    }

    return (
        <AuthenticatedLayout header="Provincias">
            <div className="space-y-4">
                <div className="flex items-center justify-between">
                    <h1 className="text-xl font-semibold text-gray-800">Provincias</h1>
                    <div className="flex gap-2">
                        <Link
                            href={route('provinces.trashed')}
                            className="rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-600 hover:bg-gray-50"
                        >
                            Papelera
                        </Link>
                        <Link
                            href={route('provinces.create')}
                            className="flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700"
                        >
                            <Plus className="h-4 w-4" />
                            Nueva Provincia
                        </Link>
                    </div>
                </div>

                <DataTable data={provinces} columns={columns} />
            </div>

            <ConfirmDeleteModal
                show={!!deleting}
                onClose={() => setDeleting(null)}
                onConfirm={handleDelete}
                title="Eliminar provincia"
                message={`¿Eliminar la provincia "${deleting?.name}"? Los datos asociados podrían verse afectados.`}
            />
        </AuthenticatedLayout>
    );
}
