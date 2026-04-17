import { useState } from 'react';
import {
    useReactTable,
    getCoreRowModel,
    getPaginationRowModel,
    getFilteredRowModel,
    getSortedRowModel,
    flexRender,
} from '@tanstack/react-table';
import { ChevronUp, ChevronDown, ChevronsUpDown, Search, ChevronLeft, ChevronRight } from 'lucide-react';

/**
 * Tabla reutilizable basada en TanStack Table v8.
 * Props:
 *   data        — array de objetos
 *   columns     — definición de columnas (TanStack Table format)
 *   searchable  — boolean, muestra campo de búsqueda global (default: true)
 *   pageSize    — tamaño inicial de página (default: 10)
 *   emptyText   — texto cuando no hay resultados (default: "Sin resultados")
 */
export default function DataTable({
    data = [],
    columns = [],
    searchable = true,
    pageSize = 10,
    emptyText = 'Sin resultados',
}) {
    const [globalFilter, setGlobalFilter] = useState('');
    const [sorting, setSorting] = useState([]);

    const table = useReactTable({
        data,
        columns,
        state: { globalFilter, sorting },
        onGlobalFilterChange: setGlobalFilter,
        onSortingChange: setSorting,
        getCoreRowModel: getCoreRowModel(),
        getFilteredRowModel: getFilteredRowModel(),
        getSortedRowModel: getSortedRowModel(),
        getPaginationRowModel: getPaginationRowModel(),
        initialState: { pagination: { pageSize } },
    });

    return (
        <div className="space-y-4">
            {searchable && (
                <div className="relative max-w-sm">
                    <Search className="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" />
                    <input
                        type="text"
                        value={globalFilter}
                        onChange={(e) => setGlobalFilter(e.target.value)}
                        placeholder="Buscar..."
                        className="w-full rounded-lg border border-gray-300 bg-white py-2 pl-9 pr-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                    />
                </div>
            )}

            <div className="overflow-x-auto rounded-lg border border-gray-200 bg-white">
                <table className="min-w-full divide-y divide-gray-200 text-sm">
                    <thead className="bg-gray-50">
                        {table.getHeaderGroups().map((headerGroup) => (
                            <tr key={headerGroup.id}>
                                {headerGroup.headers.map((header) => (
                                    <th
                                        key={header.id}
                                        className="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500"
                                        style={{ width: header.column.columnDef.size }}
                                    >
                                        {header.isPlaceholder ? null : header.column.getCanSort() ? (
                                            <button
                                                onClick={header.column.getToggleSortingHandler()}
                                                className="flex items-center gap-1 hover:text-gray-700"
                                            >
                                                {flexRender(header.column.columnDef.header, header.getContext())}
                                                <SortIcon sorted={header.column.getIsSorted()} />
                                            </button>
                                        ) : (
                                            flexRender(header.column.columnDef.header, header.getContext())
                                        )}
                                    </th>
                                ))}
                            </tr>
                        ))}
                    </thead>
                    <tbody className="divide-y divide-gray-100 bg-white">
                        {table.getRowModel().rows.length === 0 ? (
                            <tr>
                                <td
                                    colSpan={columns.length}
                                    className="px-4 py-8 text-center text-gray-400"
                                >
                                    {emptyText}
                                </td>
                            </tr>
                        ) : (
                            table.getRowModel().rows.map((row) => (
                                <tr key={row.id} className="hover:bg-gray-50">
                                    {row.getVisibleCells().map((cell) => (
                                        <td key={cell.id} className="px-4 py-3 text-gray-700">
                                            {flexRender(cell.column.columnDef.cell, cell.getContext())}
                                        </td>
                                    ))}
                                </tr>
                            ))
                        )}
                    </tbody>
                </table>
            </div>

            {/* Pagination */}
            {table.getPageCount() > 1 && (
                <div className="flex items-center justify-between text-sm text-gray-600">
                    <p>
                        Mostrando{' '}
                        {table.getState().pagination.pageIndex *
                            table.getState().pagination.pageSize +
                            1}{' '}
                        -{' '}
                        {Math.min(
                            (table.getState().pagination.pageIndex + 1) *
                                table.getState().pagination.pageSize,
                            table.getFilteredRowModel().rows.length,
                        )}{' '}
                        de {table.getFilteredRowModel().rows.length} registros
                    </p>
                    <div className="flex items-center gap-1">
                        <PageButton
                            onClick={() => table.previousPage()}
                            disabled={!table.getCanPreviousPage()}
                        >
                            <ChevronLeft className="h-4 w-4" />
                        </PageButton>
                        <span className="px-2">
                            Página {table.getState().pagination.pageIndex + 1} de{' '}
                            {table.getPageCount()}
                        </span>
                        <PageButton
                            onClick={() => table.nextPage()}
                            disabled={!table.getCanNextPage()}
                        >
                            <ChevronRight className="h-4 w-4" />
                        </PageButton>
                    </div>
                </div>
            )}
        </div>
    );
}

function SortIcon({ sorted }) {
    if (!sorted) return <ChevronsUpDown className="h-3 w-3 text-gray-400" />;
    return sorted === 'asc' ? (
        <ChevronUp className="h-3 w-3" />
    ) : (
        <ChevronDown className="h-3 w-3" />
    );
}

function PageButton({ children, onClick, disabled }) {
    return (
        <button
            onClick={onClick}
            disabled={disabled}
            className="rounded border border-gray-300 p-1 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-40"
        >
            {children}
        </button>
    );
}
