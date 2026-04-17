import { useMemo } from 'react';
import { Link, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import DataTable from '@/Components/DataTable';
import { createColumnHelper } from '@tanstack/react-table';
import { RotateCcw, ArrowLeft } from 'lucide-react';

const col = createColumnHelper();

export default function Trashed({ provinces }) {
    const columns = useMemo(
        () => [
            col.accessor('name', { header: 'Nombre' }),
            col.display({
                id: 'actions',
                header: 'Acciones',
                size: 120,
                cell: ({ row }) => (
                    <button
                        onClick={() =>
                            router.patch(route('provinces.restore', row.original.id))
                        }
                        className="flex items-center gap-1 rounded px-2 py-1 text-sm text-green-600 hover:bg-green-50"
                        title="Restaurar"
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
        <AuthenticatedLayout header="Provincias / Papelera">
            <div className="space-y-4">
                <div className="flex items-center justify-between">
                    <h1 className="text-xl font-semibold text-gray-800">Provincias eliminadas</h1>
                    <Link
                        href={route('provinces.index')}
                        className="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700"
                    >
                        <ArrowLeft className="h-4 w-4" /> Volver a la lista
                    </Link>
                </div>

                <DataTable
                    data={provinces}
                    columns={columns}
                    emptyText="No hay provincias eliminadas"
                />
            </div>
        </AuthenticatedLayout>
    );
}
