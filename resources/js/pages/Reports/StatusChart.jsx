import { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';
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
    PieChart,
    Pie,
    Cell,
    Tooltip,
    Legend,
    ResponsiveContainer,
} from 'recharts';
import { Search, Eye, BarChart2, Users, FileText } from 'lucide-react';

const BRAND_COLORS = [
    '#FF7500', '#FD3C00', '#10b981', '#f59e0b',
    '#6b7280', '#1f2937', '#FF9500', '#E65C00',
];

const CustomTooltip = ({ active, payload }) => {
    if (!active || !payload?.length) return null;
    return (
        <div className="rounded-lg border border-gray-200 bg-white px-3 py-2 shadow-lg text-sm">
            <p className="font-semibold text-gray-800">{payload[0].name}</p>
            <p style={{ color: payload[0].payload.fill }}>{payload[0].value} formularios</p>
        </div>
    );
};

const renderCustomLabel = ({ cx, cy, midAngle, innerRadius, outerRadius, percent }) => {
    if (percent < 0.04) return null;
    const RADIAN = Math.PI / 180;
    const radius = innerRadius + (outerRadius - innerRadius) * 0.5;
    const x = cx + radius * Math.cos(-midAngle * RADIAN);
    const y = cy + radius * Math.sin(-midAngle * RADIAN);
    return (
        <text x={x} y={y} fill="white" textAnchor="middle" dominantBaseline="central" fontSize={12} fontWeight={600}>
            {`${(percent * 100).toFixed(0)}%`}
        </text>
    );
};

export default function StatusChart({ partners }) {
    const { auth } = usePage().props
    const isPartner = auth.user.role === 'partner'
    const [filters, setFilters] = useState({
        start_date: '',
        end_date: '',
        partner_id: isPartner ? String(auth.user.id) : '',
    })
    const [chartData, setChartData] = useState([]);
    const [loading, setLoading] = useState(false);
    const [detail, setDetail] = useState(null);
    const [loadingDetail, setLoadingDetail] = useState(false);

    async function fetchData() {
        setLoading(true);
        setDetail(null);

        const params = new URLSearchParams();
        if (filters.start_date) params.append('start_date', filters.start_date);
        if (filters.end_date) params.append('end_date', filters.end_date);
        if (filters.partner_id) params.append('partner_id', filters.partner_id);

        try {
            const res = await fetch(route('reports.form_status_chart') + '?' + params.toString());
            const json = await res.json();

            setChartData(
                json.data.map((value, i) => ({
                    name: json.labels[i],
                    value,
                    statusId: json.status_ids[i],
                    fill: BRAND_COLORS[i % BRAND_COLORS.length],
                })),
            );
        } finally {
            setLoading(false);
        }
    }

    async function fetchDetail(statusId, statusName) {
        setLoadingDetail(true);
        const partnerId = filters.partner_id || 'null';
        const start = filters.start_date || '2000-01-01';
        const end = filters.end_date || new Date().toISOString().slice(0, 10);

        try {
            const res = await fetch(
                route('reports.form_status_chart_detail', {
                    user_id: partnerId,
                    status_id: statusId,
                    start,
                    end,
                }),
            );
            const data = await res.json();
            setDetail({ statusId, statusName, items: data });
        } finally {
            setLoadingDetail(false);
        }
    }

    const selectedPartnerName = partners.find((p) => String(p.id) === filters.partner_id)?.name;
    const totalForms = chartData.reduce((acc, d) => acc + d.value, 0);

    return (
        <AuthenticatedLayout header="Reportes / Estado de Forms">
            <div className="space-y-6">

                {/* Header */}
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-xl font-semibold text-gray-900">Estado de Formularios</h1>
                        <p className="mt-0.5 text-sm text-gray-500">Distribución de formularios por estado</p>
                    </div>
                    {isPartner ? (
                        <Link
                            href={route('form_submissions.index')}
                            className="flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-600 shadow-sm hover:border-[#FF7500] hover:text-[#FF7500]"
                        >
                            <FileText className="h-4 w-4" />
                            Ver mis formularios
                        </Link>
                    ) : (
                        <Link
                            href={route('reports.index')}
                            className="flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-600 shadow-sm hover:border-[#FF7500] hover:text-[#FF7500]"
                        >
                            <Users className="h-4 w-4" />
                            Ver por partner
                        </Link>
                    )}
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
                                disabled={isPartner}
                                className="min-w-[180px] disabled:cursor-not-allowed disabled:opacity-60"
                            >
                                {!isPartner && <option value="">Todos los partners</option>}
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

                {/* Stats + Gráfico en grid */}
                {chartData.length > 0 && (
                    <div className="grid gap-6 lg:grid-cols-3">
                        {/* Stats */}
                        <div className="flex flex-col gap-4">
                            <div className="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                                <p className="text-xs font-medium text-gray-500">Total formularios</p>
                                <p className="mt-1 text-3xl font-bold text-gray-900">{totalForms}</p>
                                {selectedPartnerName && (
                                    <p className="mt-2 text-xs text-gray-400">— {selectedPartnerName}</p>
                                )}
                            </div>
                            <div className="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                                <p className="mb-3 text-xs font-semibold uppercase tracking-wider text-gray-400">
                                    Breakdown por estado
                                </p>
                                <div className="space-y-2.5">
                                    {chartData.map((entry, i) => (
                                        <button
                                            key={i}
                                            onClick={() => fetchDetail(entry.statusId, entry.name)}
                                            className="flex w-full items-center gap-2 rounded-md px-2 py-1.5 text-left hover:bg-gray-50"
                                        >
                                            <span
                                                className="h-2.5 w-2.5 flex-shrink-0 rounded-full"
                                                style={{ background: entry.fill }}
                                            />
                                            <span className="flex-1 truncate text-xs text-gray-600">{entry.name}</span>
                                            <span className="text-xs font-semibold text-gray-800">{entry.value}</span>
                                        </button>
                                    ))}
                                </div>
                            </div>
                        </div>

                        {/* Gráfico */}
                        <div className="rounded-xl border border-gray-200 bg-white p-6 shadow-sm lg:col-span-2">
                            <div className="mb-4">
                                <h2 className="text-sm font-semibold text-gray-800">Distribución por estado</h2>
                                <p className="text-xs text-gray-400">Hacé clic en una sección para ver el detalle</p>
                            </div>
                            <ResponsiveContainer width="100%" height={300}>
                                <PieChart>
                                    <Pie
                                        data={chartData}
                                        cx="50%"
                                        cy="50%"
                                        outerRadius={120}
                                        dataKey="value"
                                        onClick={(entry) => fetchDetail(entry.statusId, entry.name)}
                                        cursor="pointer"
                                        labelLine={false}
                                        label={renderCustomLabel}
                                    >
                                        {chartData.map((entry, i) => (
                                            <Cell
                                                key={i}
                                                fill={entry.fill}
                                                opacity={detail && detail.statusId !== entry.statusId ? 0.35 : 1}
                                                stroke="white"
                                                strokeWidth={2}
                                            />
                                        ))}
                                    </Pie>
                                    <Tooltip content={<CustomTooltip />} />
                                </PieChart>
                            </ResponsiveContainer>
                        </div>
                    </div>
                )}

                {/* Detalle por estado */}
                {(detail || loadingDetail) && (
                    <div className="rounded-xl border border-gray-200 bg-white shadow-sm">
                        <div className="flex items-center gap-3 border-b border-gray-100 px-6 py-4">
                            <BarChart2 className="h-4 w-4 text-[#FF7500]" />
                            <div>
                                <h2 className="font-semibold text-gray-800">
                                    {detail ? (
                                        <StatusBadge status={detail.statusName} />
                                    ) : 'Cargando...'}
                                </h2>
                                <p className="mt-0.5 text-xs text-gray-400">Detalle de formularios</p>
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
                                            <TableHead className="bg-gray-50 px-6 text-xs font-semibold uppercase tracking-wider text-gray-500">Partner</TableHead>
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
                                                <TableCell className="px-6 py-3 text-gray-500">{item.partner ?? '—'}</TableCell>
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
