import { useMemo } from 'react';
import { Link, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import DataTable from '@/Components/DataTable';
import { createColumnHelper } from '@tanstack/react-table';
import { RotateCcw, ArrowLeft } from 'lucide-react';

const col = createColumnHelper();

export default function Trashed({ users }) {
    const columns = useMemo(
        () => [
            col.accessor('name', { header: 'Nombre' }),
            col.accessor('email', { header: 'Email' }),
            col.accessor('role', { header: 'Rol', cell: ({ getValue }) => <span className="capitalize">{getValue()}</span> }),
            col.display({
                id: 'actions',
                header: 'Acciones',
                size: 120,
                cell: ({ row }) => (
                    <button
                        onClick={() =>
                            router.patch(route('users.restore', row.original.id))
                        }
                        className="flex items-center gap-1 rounded px-2 py-1 text-sm text-green-600 hover:bg-green-50"
                    >
                        <RotateCcw className="h-4 w-4" />
                        Restaurar
                    </button>
                ),
            }),
        ],
        [],
    );

    return (
        <AuthenticatedLayout header="Usuarios / Papelera">
            <div className="space-y-4">
                <div className="flex items-center justify-between">
                    <h1 className="text-xl font-semibold text-gray-800">Usuarios eliminados</h1>
                    <Link
                        href={route('users.index')}
                        className="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700"
                    >
                        <ArrowLeft className="h-4 w-4" /> Volver
                    </Link>
                </div>

                <DataTable
                    data={users}
                    columns={columns}
                    emptyText="No hay usuarios eliminados"
                />
            </div>
        </AuthenticatedLayout>
    );
}
