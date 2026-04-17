import { useState } from 'react';
import { Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import StatusBadge from '@/Components/StatusBadge';
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
import { Search, Eye, User2 } from 'lucide-react';

const COLORS = [
    '#6366f1', '#8b5cf6', '#06b6d4', '#10b981',
    '#f59e0b', '#ef4444', '#ec4899', '#14b8a6',
];

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

    return (
        <AuthenticatedLayout header="Reportes / Forms por Partner">
            <div className="space-y-6">
                <h1 className="text-xl font-semibold text-gray-800">Formularios por Partner</h1>

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
                            Formularios por Partner
                        </h2>
                        <p className="mb-4 text-xs text-gray-400">Hacé click en una barra para ver el detalle</p>
                        <ResponsiveContainer width="100%" height={300}>
                            <BarChart data={chartData}>
                                <CartesianGrid strokeDasharray="3 3" vertical={false} />
                                <XAxis dataKey="name" tick={{ fontSize: 12 }} />
                                <YAxis allowDecimals={false} tick={{ fontSize: 12 }} />
                                <Tooltip />
                                <Bar
                                    dataKey="total"
                                    radius={[4, 4, 0, 0]}
                                    cursor="pointer"
                                    onClick={(entry) => fetchDetail(entry.id, entry.name)}
                                >
                                    {chartData.map((entry, i) => (
                                        <Cell
                                            key={i}
                                            fill={detail?.partnerId === entry.id ? '#4338ca' : COLORS[i % COLORS.length]}
                                        />
                                    ))}
                                </Bar>
                            </BarChart>
                        </ResponsiveContainer>
                    </div>
                )}

                {/* Detalle */}
                {(detail || loadingDetail) && (
                    <div className="rounded-xl border border-gray-200 bg-white shadow-sm">
                        <div className="flex items-center gap-2 border-b border-gray-100 px-6 py-4">
                            <User2 className="h-4 w-4 text-indigo-500" />
                            <h2 className="font-semibold text-gray-800">
                                {detail ? `Formularios de ${detail.partnerName}` : 'Cargando...'}
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
