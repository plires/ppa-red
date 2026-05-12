import { useMemo, useState } from 'react';
import { Link, usePage } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import DataTable from '@/Components/DataTable';
import StatusBadge from '@/Components/StatusBadge';
import { createColumnHelper } from '@tanstack/react-table';
import { Bell, Eye, MessageSquare } from 'lucide-react';

const col = createColumnHelper();

export default function Index({ formSubmissions }) {
    const { auth } = usePage().props;
    const isAdmin = auth.user.role === 'admin';

    const columns = useMemo(
        () => [
            col.accessor(
                (row) => {
                    try {
                        const d = JSON.parse(row.data);
                        return d?.name ?? '—';
                    } catch {
                        return '—';
                    }
                },
                {
                    id: 'nombre',
                    header: 'Nombre',
                    cell: ({ row, getValue }) => {
                        const hasComments = row.original.has_unread_comments;
                        const hasNotifs = row.original.has_unread_notifications;
                        return (
                            <span className="flex items-center gap-1.5">
                                {hasComments && (
                                    <MessageSquare className="h-3.5 w-3.5 flex-shrink-0 text-[#FF7500]" />
                                )}
                                <span className={hasComments ? 'font-semibold' : ''}>{getValue()}</span>
                                {hasNotifs && (
                                    <Bell className="h-3.5 w-3.5 flex-shrink-0 text-[#FF7500]" />
                                )}
                            </span>
                        );
                    },
                },
            ),
            col.accessor('locality.name', {
                id: 'locality',
                header: 'Localidad',
                cell: ({ getValue }) => getValue() ?? '—',
            }),
            ...(isAdmin
                ? [
                      col.accessor('user.name', {
                          id: 'partner',
                          header: 'Partner',
                          cell: ({ getValue }) => getValue() ?? '—',
                      }),
                  ]
                : []),
            col.accessor('status.name', {
                id: 'status',
                header: 'Estado',
                cell: ({ getValue }) => <StatusBadge status={getValue()} />,
            }),
            col.accessor('created_at', {
                header: 'Fecha',
                cell: ({ getValue }) =>
                    new Date(getValue()).toLocaleDateString('es-AR'),
            }),
            col.display({
                id: 'actions',
                header: 'Acciones',
                size: 80,
                cell: ({ row }) => (
                    <Link
                        href={route('form_submissions.show', row.original.id)}
                        className="flex items-center gap-1 rounded px-2 py-1 text-sm text-[#FF7500] hover:bg-orange-50"
                    >
                        <Eye className="h-4 w-4" />
                        Ver
                    </Link>
                ),
            }),
        ],
        [isAdmin],
    );

    return (
        <AuthenticatedLayout header="Formularios">
            <div className="space-y-4">
                <div className="flex items-center justify-between">
                    <h1 className="text-xl font-semibold text-gray-800">
                        Formularios enviados
                    </h1>
                </div>

                <DataTable data={formSubmissions} columns={columns} />
            </div>
        </AuthenticatedLayout>
    );
}
