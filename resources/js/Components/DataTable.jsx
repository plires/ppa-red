import { useState } from 'react';
import {
    useReactTable,
    getCoreRowModel,
    getPaginationRowModel,
    getFilteredRowModel,
    getSortedRowModel,
    flexRender,
} from '@tanstack/react-table';
import { ChevronUp, ChevronDown, ChevronsUpDown, Search, ChevronLeft, ChevronRight, ChevronsLeft, ChevronsRight } from 'lucide-react';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/Components/ui/table';
import { cn } from '@/lib/utils';

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

    const totalRows = table.getFilteredRowModel().rows.length;
    const pageIndex = table.getState().pagination.pageIndex;
    const pageCount = table.getPageCount();
    const from = pageIndex * pageSize + 1;
    const to = Math.min((pageIndex + 1) * pageSize, totalRows);

    return (
        <div className="space-y-4">
            {/* Barra superior: búsqueda + contador */}
            {searchable && (
                <div className="flex items-center justify-between gap-4">
                    <div className="relative max-w-xs flex-1">
                        <Search className="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" />
                        <input
                            type="text"
                            value={globalFilter}
                            onChange={(e) => setGlobalFilter(e.target.value)}
                            placeholder="Buscar..."
                            className="w-full rounded-lg border border-gray-200 bg-white py-2 pl-9 pr-3 text-sm shadow-sm transition focus:border-[#FF7500] focus:outline-none focus:ring-1 focus:ring-[#FF7500]"
                        />
                    </div>
                    <span className="shrink-0 text-xs text-gray-400">
                        {totalRows} {totalRows === 1 ? 'registro' : 'registros'}
                    </span>
                </div>
            )}

            {/* Tabla */}
            <div className="rounded-xl border border-gray-200 bg-white shadow-sm">
                <Table>
                    <TableHeader>
                        {table.getHeaderGroups().map((headerGroup) => (
                            <TableRow key={headerGroup.id} className="hover:bg-transparent border-b border-gray-200">
                                {headerGroup.headers.map((header) => (
                                    <TableHead
                                        key={header.id}
                                        className="bg-gray-50 px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500 first:rounded-tl-xl last:rounded-tr-xl"
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
                                    </TableHead>
                                ))}
                            </TableRow>
                        ))}
                    </TableHeader>
                    <TableBody>
                        {table.getRowModel().rows.length === 0 ? (
                            <TableRow>
                                <TableCell
                                    colSpan={columns.length}
                                    className="py-12 text-center text-sm text-gray-400"
                                >
                                    {emptyText}
                                </TableCell>
                            </TableRow>
                        ) : (
                            table.getRowModel().rows.map((row) => (
                                <TableRow
                                    key={row.id}
                                    className="border-b border-gray-100 hover:bg-orange-50/40 transition-colors"
                                >
                                    {row.getVisibleCells().map((cell) => (
                                        <TableCell key={cell.id} className="px-4 py-3 text-sm text-gray-700">
                                            {flexRender(cell.column.columnDef.cell, cell.getContext())}
                                        </TableCell>
                                    ))}
                                </TableRow>
                            ))
                        )}
                    </TableBody>
                </Table>
            </div>

            {/* Paginación */}
            {pageCount > 1 && (
                <div className="flex items-center justify-between text-sm text-gray-500">
                    <p className="text-xs">
                        Mostrando <span className="font-medium text-gray-700">{from}–{to}</span> de{' '}
                        <span className="font-medium text-gray-700">{totalRows}</span> registros
                    </p>
                    <div className="flex items-center gap-1">
                        <PageBtn onClick={() => table.setPageIndex(0)} disabled={!table.getCanPreviousPage()} title="Primera">
                            <ChevronsLeft className="h-3.5 w-3.5" />
                        </PageBtn>
                        <PageBtn onClick={() => table.previousPage()} disabled={!table.getCanPreviousPage()} title="Anterior">
                            <ChevronLeft className="h-3.5 w-3.5" />
                        </PageBtn>
                        <span className="px-3 py-1 text-xs font-medium">
                            {pageIndex + 1} / {pageCount}
                        </span>
                        <PageBtn onClick={() => table.nextPage()} disabled={!table.getCanNextPage()} title="Siguiente">
                            <ChevronRight className="h-3.5 w-3.5" />
                        </PageBtn>
                        <PageBtn onClick={() => table.setPageIndex(pageCount - 1)} disabled={!table.getCanNextPage()} title="Última">
                            <ChevronsRight className="h-3.5 w-3.5" />
                        </PageBtn>
                    </div>
                </div>
            )}
        </div>
    );
}

function SortIcon({ sorted }) {
    if (!sorted) return <ChevronsUpDown className="h-3 w-3 text-gray-400" />;
    return sorted === 'asc' ? (
        <ChevronUp className="h-3 w-3 text-[#FF7500]" />
    ) : (
        <ChevronDown className="h-3 w-3 text-[#FF7500]" />
    );
}

function PageBtn({ children, onClick, disabled, title }) {
    return (
        <button
            onClick={onClick}
            disabled={disabled}
            title={title}
            className={cn(
                'flex h-7 w-7 items-center justify-center rounded-md border border-gray-200 bg-white text-gray-600 transition',
                'hover:border-[#FF7500] hover:text-[#FF7500]',
                'disabled:cursor-not-allowed disabled:opacity-40 disabled:hover:border-gray-200 disabled:hover:text-gray-600',
            )}
        >
            {children}
        </button>
    );
}
