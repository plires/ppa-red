import { useState } from 'react';
import { Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import StatusBadge from '@/Components/StatusBadge';
import {
    PieChart,
    Pie,
    Cell,
    Tooltip,
    Legend,
    ResponsiveContainer,
} from 'recharts';
import { Search, Eye, BarChart2 } from 'lucide-react';

const COLORS = [
    '#6366f1', '#10b981', '#ef4444', '#f59e0b', '#6b7280', '#1f2937',
];

export default function StatusChart({ partners }) {
    const [filters, setFilters] = useState({ start_date: '', end_date: '', partner_id: '' });
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

    return (
        <AuthenticatedLayout header="Reportes / Estado de Forms">
            <div className="space-y-6">
                <h1 className="text-xl font-semibold text-gray-800">Estado de Formularios</h1>

                {/* Filtros */}
                <div className="flex flex-wrap gap-3 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div className="flex flex-col gap-1">
                        <label className="text-xs font-medium text-gray-500">Desde</label>
                        <input
                            type="date"
                            value={filters.start_date}
                            onChange={(e) => setFilters((f) => ({ ...f, start_date: e.target.value }))}
                            className="rounded-md border border-gray-300 px-3 py-1.5 text-sm"
                        />
                    </div>
                    <div className="flex flex-col gap-1">
                        <label className="text-xs font-medium text-gray-500">Hasta</label>
                        <input
                            type="date"
                            value={filters.end_date}
                            onChange={(e) => setFilters((f) => ({ ...f, end_date: e.target.value }))}
                            className="rounded-md border border-gray-300 px-3 py-1.5 text-sm"
                        />
                    </div>
                    <div className="flex flex-col gap-1">
                        <label className="text-xs font-medium text-gray-500">Partner</label>
                        <select
                            value={filters.partner_id}
                            onChange={(e) => setFilters((f) => ({ ...f, partner_id: e.target.value }))}
                            className="rounded-md border border-gray-300 px-3 py-1.5 text-sm"
                        >
                            <option value="">Todos</option>
                            {partners.map((p) => (
                                <option key={p.id} value={p.id}>{p.name}</option>
                            ))}
                        </select>
                    </div>
                    <div className="flex items-end">
                        <button
                            onClick={fetchData}
                            disabled={loading}
                            className="flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-60"
                        >
                            <Search className="h-4 w-4" />
                            {loading ? 'Cargando...' : 'Consultar'}
                        </button>
                    </div>
                </div>

                {/* Gráfico */}
                {chartData.length > 0 && (
                    <div className="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                        <h2 className="mb-1 text-sm font-semibold text-gray-700">
                            Distribución por estado
                            {selectedPartnerName && (
                                <span className="ml-2 font-normal text-gray-400">— {selectedPartnerName}</span>
                            )}
                        </h2>
                        <p className="mb-4 text-xs text-gray-400">Hacé click en una sección para ver el detalle</p>
                        <ResponsiveContainer width="100%" height={340}>
                            <PieChart>
                                <Pie
                                    data={chartData}
                                    cx="50%"
                                    cy="50%"
                                    outerRadius={120}
                                    dataKey="value"
                                    onClick={(entry) => fetchDetail(entry.statusId, entry.name)}
                                    cursor="pointer"
                                    label={({ percent }) => `${(percent * 100).toFixed(0)}%`}
                                >
                                    {chartData.map((entry, i) => (
                                        <Cell
                                            key={i}
                                            fill={COLORS[i % COLORS.length]}
                                            opacity={detail && detail.statusId !== entry.statusId ? 0.4 : 1}
                                        />
                                    ))}
                                </Pie>
                                <Tooltip formatter={(value, name) => [value, name]} />
                                <Legend />
                            </PieChart>
                        </ResponsiveContainer>
                    </div>
                )}

                {/* Detalle */}
                {(detail || loadingDetail) && (
                    <div className="rounded-xl border border-gray-200 bg-white shadow-sm">
                        <div className="flex items-center gap-2 border-b border-gray-100 px-6 py-4">
                            <BarChart2 className="h-4 w-4 text-indigo-500" />
                            <h2 className="font-semibold text-gray-800">
                                {detail ? (
                                    <>
                                        <StatusBadge status={detail.statusName} />
                                    </>
                                ) : 'Cargando...'}
                            </h2>
                            {detail && (
                                <span className="ml-auto rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-medium text-indigo-600">
                                    {detail.items.length}
                                </span>
                            )}
                        </div>

                        {loadingDetail ? (
                            <p className="px-6 py-8 text-center text-sm text-gray-400">Cargando formularios...</p>
                        ) : detail.items.length === 0 ? (
                            <p className="px-6 py-8 text-center text-sm text-gray-400">
                                Sin formularios en el período seleccionado.
                            </p>
                        ) : (
                            <div className="overflow-x-auto">
                                <table className="w-full text-sm">
                                    <thead>
                                        <tr className="border-b border-gray-100 bg-gray-50 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                                            <th className="px-6 py-3">Solicitante</th>
                                            <th className="px-6 py-3">Localidad</th>
                                            <th className="px-6 py-3">Partner</th>
                                            <th className="px-6 py-3">Estado</th>
                                            <th className="px-6 py-3">Fecha</th>
                                            <th className="px-6 py-3"></th>
                                        </tr>
                                    </thead>
                                    <tbody className="divide-y divide-gray-50">
                                        {detail.items.map((item) => (
                                            <tr key={item.id} className="hover:bg-gray-50">
                                                <td className="px-6 py-3 font-medium text-gray-800">
                                                    {item.end_user_name}
                                                </td>
                                                <td className="px-6 py-3 text-gray-500">
                                                    {item.locality ?? '—'}
                                                </td>
                                                <td className="px-6 py-3 text-gray-500">
                                                    {item.partner ?? '—'}
                                                </td>
                                                <td className="px-6 py-3">
                                                    <StatusBadge status={item.status} />
                                                </td>
                                                <td className="px-6 py-3 text-gray-500">
                                                    {new Date(item.created_at).toLocaleDateString('es-AR')}
                                                </td>
                                                <td className="px-6 py-3">
                                                    <Link
                                                        href={route('form_submissions.show', item.id)}
                                                        className="inline-flex items-center gap-1 rounded px-2 py-1 text-sm text-indigo-600 hover:bg-indigo-50"
                                                    >
                                                        <Eye className="h-4 w-4" />
                                                        Ver
                                                    </Link>
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        )}
                    </div>
                )}

                {chartData.length === 0 && !loading && (
                    <div className="rounded-xl border border-dashed border-gray-300 bg-white p-12 text-center text-gray-400">
                        Usá los filtros y presioná "Consultar" para ver el reporte
                    </div>
                )}
            </div>
        </AuthenticatedLayout>
    );
}
