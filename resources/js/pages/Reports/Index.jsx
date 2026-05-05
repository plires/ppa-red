import { useState } from 'react';
import { Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import StatusBadge from '@/Components/StatusBadge';
import NativeSelect from '@/Components/NativeSelect';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/Components/ui/table';
import {
    BarChart,
    Bar,
    XAxis,
    YAxis,
    CartesianGrid,
    Tooltip,
    ResponsiveContainer,
    Cell,
} from 'recharts';
import { Search, Eye, User2, BarChart2 } from 'lucide-react';

const BRAND_COLORS = [
    '#FF7500', '#FD3C00', '#FF9500', '#E65C00',
    '#FFB347', '#CC3300', '#FF6600', '#D45500',
];

const CustomTooltip = ({ active, payload, label }) => {
    if (!active || !payload?.length) return null;
    return (
        <div className="rounded-lg border border-gray-200 bg-white px-3 py-2 shadow-lg text-sm">
            <p className="font-semibold text-gray-800">{label}</p>
            <p className="text-[#FF7500]">{payload[0].value} formularios</p>
        </div>
    );
};

export default function Index({ partners }) {
    const [filters, setFilters] = useState({ start_date: '', end_date: '', partner_id: '' });
    const [chartData, setChartData] = useState([]);
    const [detail, setDetail] = useState(null);
    const [loading, setLoading] = useState(false);
    const [loadingDetail, setLoadingDetail] = useState(false);

    async function fetchData() {
        setLoading(true);
        setDetail(null);

        const params = new URLSearchParams();
        if (filters.start_date) params.append('start_date', filters.start_date);
        if (filters.end_date) params.append('end_date', filters.end_date);
        if (filters.partner_id) params.append('partner_id', filters.partner_id);

        try {
            const res = await fetch(route('reports.form_submissions_by_partner') + '?' + params.toString());
            const json = await res.json();

            setChartData(
                json.labels.map((label, i) => ({
                    id: json.id[i],
                    name: label,
                    total: json.data[i],
                })),
            );
        } finally {
            setLoading(false);
        }
    }

    async function fetchDetail(partnerId, partnerName) {
        setLoadingDetail(true);
        const start = filters.start_date || '2000-01-01';
        const end = filters.end_date || new Date().toISOString().slice(0, 10);

        try {
            const res = await fetch(
                route('reportes.form_submissionsDetail', { user_id: partnerId, start, end }),
            );
            const data = await res.json();
            setDetail({ partnerId, partnerName, items: data });
        } finally {
            setLoadingDetail(false);
        }
    }

    const totalForms = chartData.reduce((acc, d) => acc + d.total, 0);

    return (
        <AuthenticatedLayout header="Reportes / Forms por Partner">
            <div className="space-y-6">

                {/* Header */}
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-xl font-semibold text-gray-900">Formularios por Partner</h1>
                        <p className="mt-0.5 text-sm text-gray-500">Análisis de envíos por período y partner</p>
                    </div>
                    <Link
                        href={route('reports.status_chart')}
                        className="flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-600 shadow-sm hover:border-[#FF7500] hover:text-[#FF7500]"
                    >
                        <BarChart2 className="h-4 w-4" />
                        Ver por estado
                    </Link>
                </div>

                {/* Panel de filtros */}
                <div className="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p className="mb-4 text-xs font-semibold uppercase tracking-wider text-gray-400">Filtros</p>
                    <div className="flex flex-wrap items-end gap-4">
                        <div className="flex flex-col gap-1.5">
                            <label className="text-xs font-medium text-gray-600">Desde</label>
                            <input
                                type="date"
                                value={filters.start_date}
                                onChange={(e) => setFilters((f) => ({ ...f, start_date: e.target.value }))}
                                className="rounded-lg border border-gray-200 px-3 py-2 text-sm shadow-sm focus:border-[#FF7500] focus:outline-none focus:ring-1 focus:ring-[#FF7500]"
                            />
                        </div>
                        <div className="flex flex-col gap-1.5">
                            <label className="text-xs font-medium text-gray-600">Hasta</label>
                            <input
                                type="date"
                                value={filters.end_date}
                                onChange={(e) => setFilters((f) => ({ ...f, end_date: e.target.value }))}
                                className="rounded-lg border border-gray-200 px-3 py-2 text-sm shadow-sm focus:border-[#FF7500] focus:outline-none focus:ring-1 focus:ring-[#FF7500]"
                            />
                        </div>
                        <div className="flex flex-col gap-1.5">
                            <label className="text-xs font-medium text-gray-600">Partner</label>
                            <NativeSelect
                                value={filters.partner_id}
                                onChange={(e) => setFilters((f) => ({ ...f, partner_id: e.target.value }))}
                                className="min-w-[180px]"
                            >
                                <option value="">Todos los partners</option>
                                {partners.map((p) => (
                                    <option key={p.id} value={p.id}>{p.name}</option>
                                ))}
                            </NativeSelect>
                        </div>
                        <button
                            onClick={fetchData}
                            disabled={loading}
                            className="flex items-center gap-2 rounded-lg px-5 py-2 text-sm font-medium text-white hover:opacity-90 disabled:opacity-60"
                            style={{ background: 'linear-gradient(90deg, #FD3C00, #FF7500)' }}
                        >
                            <Search className="h-4 w-4" />
                            {loading ? 'Consultando...' : 'Consultar'}
                        </button>
                    </div>
                </div>

                {/* Stats rápidas */}
                {chartData.length > 0 && (
                    <div className="grid grid-cols-2 gap-4 sm:grid-cols-3">
                        <div className="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                            <p className="text-xs font-medium text-gray-500">Total formularios</p>
                            <p className="mt-1 text-2xl font-bold text-gray-900">{totalForms}</p>
                        </div>
                        <div className="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                            <p className="text-xs font-medium text-gray-500">Partners con actividad</p>
                            <p className="mt-1 text-2xl font-bold text-gray-900">{chartData.filter(d => d.total > 0).length}</p>
                        </div>
                        <div className="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                            <p className="text-xs font-medium text-gray-500">Promedio por partner</p>
                            <p className="mt-1 text-2xl font-bold text-gray-900">
                                {chartData.length ? (totalForms / chartData.length).toFixed(1) : '0'}
                            </p>
                        </div>
                    </div>
                )}

                {/* Gráfico */}
                {chartData.length > 0 && (
                    <div className="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                        <div className="mb-4 flex items-start justify-between">
                            <div>
                                <h2 className="text-sm font-semibold text-gray-800">Distribución por partner</h2>
                                <p className="text-xs text-gray-400">Hacé clic en una barra para ver el detalle</p>
                            </div>
                            {detail && (
                                <span className="rounded-full border border-orange-200 bg-orange-50 px-2.5 py-0.5 text-xs font-medium text-[#FF7500]">
                                    Seleccionado: {detail.partnerName}
                                </span>
                            )}
                        </div>
                        <ResponsiveContainer width="100%" height={280}>
                            <BarChart data={chartData} barCategoryGap="30%">
                                <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#f0f0f0" />
                                <XAxis dataKey="name" tick={{ fontSize: 12, fill: '#6b7280' }} axisLine={false} tickLine={false} />
                                <YAxis allowDecimals={false} tick={{ fontSize: 12, fill: '#6b7280' }} axisLine={false} tickLine={false} />
                                <Tooltip content={<CustomTooltip />} />
                                <Bar
                                    dataKey="total"
                                    radius={[6, 6, 0, 0]}
                                    cursor="pointer"
                                    onClick={(entry) => fetchDetail(entry.id, entry.name)}
                                >
                                    {chartData.map((entry, i) => (
                                        <Cell
                                            key={i}
                                            fill={detail?.partnerId === entry.id ? '#FD3C00' : BRAND_COLORS[i % BRAND_COLORS.length]}
                                            opacity={detail && detail.partnerId !== entry.id ? 0.5 : 1}
                                        />
                                    ))}
                                </Bar>
                            </BarChart>
                        </ResponsiveContainer>
                    </div>
                )}

                {/* Detalle de partner */}
                {(detail || loadingDetail) && (
                    <div className="rounded-xl border border-gray-200 bg-white shadow-sm">
                        <div className="flex items-center gap-3 border-b border-gray-100 px-6 py-4">
                            <div
                                className="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full text-xs font-bold text-white"
                                style={{ background: 'linear-gradient(135deg, #FD3C00, #FF7500)' }}
                            >
                                <User2 className="h-4 w-4" />
                            </div>
                            <div>
                                <h2 className="font-semibold text-gray-800">
                                    {detail ? detail.partnerName : 'Cargando...'}
                                </h2>
                                <p className="text-xs text-gray-400">Detalle de formularios</p>
                            </div>
                            {detail && (
                                <span className="ml-auto rounded-full border border-orange-200 bg-orange-50 px-2.5 py-0.5 text-xs font-medium text-[#FF7500]">
                                    {detail.items.length} {detail.items.length === 1 ? 'formulario' : 'formularios'}
                                </span>
                            )}
                        </div>

                        {loadingDetail ? (
                            <div className="flex items-center justify-center px-6 py-12">
                                <div className="h-5 w-5 animate-spin rounded-full border-2 border-[#FF7500] border-t-transparent" />
                                <span className="ml-3 text-sm text-gray-400">Cargando formularios...</span>
                            </div>
                        ) : detail?.items.length === 0 ? (
                            <p className="px-6 py-10 text-center text-sm text-gray-400">
                                Sin formularios en el período seleccionado.
                            </p>
                        ) : (
                            <div className="overflow-x-auto">
                                <Table>
                                    <TableHeader>
                                        <TableRow className="border-b border-gray-100 hover:bg-transparent">
                                            <TableHead className="bg-gray-50 px-6 text-xs font-semibold uppercase tracking-wider text-gray-500">Solicitante</TableHead>
                                            <TableHead className="bg-gray-50 px-6 text-xs font-semibold uppercase tracking-wider text-gray-500">Localidad</TableHead>
                                            <TableHead className="bg-gray-50 px-6 text-xs font-semibold uppercase tracking-wider text-gray-500">Estado</TableHead>
                                            <TableHead className="bg-gray-50 px-6 text-xs font-semibold uppercase tracking-wider text-gray-500">Fecha</TableHead>
                                            <TableHead className="bg-gray-50 px-6"></TableHead>
                                        </TableRow>
                                    </TableHeader>
                                    <TableBody>
                                        {detail.items.map((item) => (
                                            <TableRow key={item.id} className="border-b border-gray-50 hover:bg-orange-50/30">
                                                <TableCell className="px-6 py-3 font-medium text-gray-800">{item.end_user_name}</TableCell>
                                                <TableCell className="px-6 py-3 text-gray-500">{item.locality ?? '—'}</TableCell>
                                                <TableCell className="px-6 py-3">
                                                    <StatusBadge status={item.status} />
                                                </TableCell>
                                                <TableCell className="px-6 py-3 text-gray-500">
                                                    {new Date(item.created_at).toLocaleDateString('es-AR')}
                                                </TableCell>
                                                <TableCell className="px-6 py-3">
                                                    <Link
                                                        href={route('form_submissions.show', item.id)}
                                                        className="inline-flex items-center gap-1 rounded-md px-2 py-1 text-sm text-[#FF7500] hover:bg-orange-50"
                                                    >
                                                        <Eye className="h-4 w-4" />
                                                        Ver
                                                    </Link>
                                                </TableCell>
                                            </TableRow>
                                        ))}
                                    </TableBody>
                                </Table>
                            </div>
                        )}
                    </div>
                )}

                {/* Empty state */}
                {chartData.length === 0 && !loading && (
                    <div className="flex flex-col items-center justify-center rounded-xl border border-dashed border-gray-200 bg-white py-16 text-center">
                        <div
                            className="mb-4 flex h-12 w-12 items-center justify-center rounded-full"
                            style={{ background: 'linear-gradient(135deg, #FD3C00, #FF7500)' }}
                        >
                            <BarChart2 className="h-6 w-6 text-white" />
                        </div>
                        <p className="text-sm font-medium text-gray-600">Sin datos para mostrar</p>
                        <p className="mt-1 text-xs text-gray-400">Usá los filtros y presioná "Consultar" para ver el reporte</p>
                    </div>
                )}
            </div>
        </AuthenticatedLayout>
    );
}
