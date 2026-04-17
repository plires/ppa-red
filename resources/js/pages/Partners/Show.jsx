import { Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {
    ArrowLeft,
    Pencil,
    Users,
    Mail,
    Phone,
    MapPin,
    Map,
    Globe,
    FileText,
    Eye,
    ChevronRight,
} from 'lucide-react';

const STATUS_COLORS = {
    'Pendiente de Respuesta Del Partner': 'bg-yellow-100 text-yellow-700',
    'Respondido Por El Partner': 'bg-blue-100 text-blue-700',
    'Demorado - Sin Respuesta Del Partner (48h)': 'bg-orange-100 text-orange-700',
    'Cerrado - Sin Respuesta Del Partner': 'bg-red-100 text-red-700',
    'Cerrado - Sin Respuesta Del Usuario': 'bg-red-100 text-red-700',
    'Cerrado Por El Partner': 'bg-green-100 text-green-700',
};

function StatusBadge({ status }) {
    const color = STATUS_COLORS[status] ?? 'bg-gray-100 text-gray-600';
    return (
        <span className={`inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ${color}`}>
            {status ?? '—'}
        </span>
    );
}

export default function Show({ partner, recentSubmissions }) {
    const localities = partner.localities ?? [];

    return (
        <AuthenticatedLayout header="Partners / Detalle">
            <div className="mx-auto max-w-4xl space-y-5">
                <Link
                    href={route('partners.index')}
                    className="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700"
                >
                    <ArrowLeft className="h-4 w-4" /> Volver
                </Link>

                {/* Header card */}
                <div className="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div className="flex items-start justify-between gap-4">
                        <div className="flex items-center gap-4">
                            <div className="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-full bg-indigo-600 text-xl font-bold text-white">
                                {partner.name.charAt(0).toUpperCase()}
                            </div>
                            <div>
                                <p className="text-xs font-medium uppercase tracking-wide text-gray-400">Partner</p>
                                <h1 className="text-xl font-bold text-gray-900">{partner.name}</h1>
                                <span className="mt-0.5 inline-block rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-medium capitalize text-indigo-600">
                                    {partner.role}
                                </span>
                            </div>
                        </div>
                        <Link
                            href={route('partners.edit', partner.id)}
                            className="flex items-center gap-1.5 rounded-lg border border-gray-300 px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-50"
                        >
                            <Pencil className="h-3.5 w-3.5" />
                            Editar
                        </Link>
                    </div>

                    {/* Contacto */}
                    <div className="mt-5 flex flex-wrap gap-5 border-t border-gray-100 pt-5">
                        <div className="flex items-center gap-2 text-sm text-gray-600">
                            <Mail className="h-4 w-4 text-gray-400" />
                            {partner.email}
                        </div>
                        {partner.phone && (
                            <div className="flex items-center gap-2 text-sm text-gray-600">
                                <Phone className="h-4 w-4 text-gray-400" />
                                {partner.phone}
                            </div>
                        )}
                        <div className="flex items-center gap-2 text-sm text-gray-600">
                            <MapPin className="h-4 w-4 text-gray-400" />
                            {localities.length} localidad{localities.length !== 1 ? 'es' : ''} asignada{localities.length !== 1 ? 's' : ''}
                        </div>
                        <div className="flex items-center gap-2 text-sm text-gray-600">
                            <FileText className="h-4 w-4 text-gray-400" />
                            {recentSubmissions.length} formulario{recentSubmissions.length !== 1 ? 's' : ''} reciente{recentSubmissions.length !== 1 ? 's' : ''}
                        </div>
                    </div>
                </div>

                {/* Localidades */}
                <div className="rounded-xl border border-gray-200 bg-white shadow-sm">
                    <div className="flex items-center gap-2 border-b border-gray-100 px-6 py-4">
                        <MapPin className="h-4 w-4 text-emerald-500" />
                        <h2 className="font-semibold text-gray-800">Localidades asignadas</h2>
                        <span className="ml-auto rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-600">
                            {localities.length}
                        </span>
                    </div>

                    {localities.length === 0 ? (
                        <p className="px-6 py-5 text-sm text-gray-400">Este partner no tiene localidades asignadas.</p>
                    ) : (
                        <ul className="divide-y divide-gray-50">
                            {localities.map((locality) => (
                                <li key={locality.id} className="flex items-center gap-3 px-6 py-3">
                                    <MapPin className="h-4 w-4 flex-shrink-0 text-emerald-400" />
                                    <span className="text-sm font-medium text-gray-700">{locality.name}</span>

                                    <div className="ml-4 flex items-center gap-1.5 text-xs text-gray-400">
                                        {locality.zone && (
                                            <>
                                                <Map className="h-3 w-3 text-blue-400" />
                                                <span className="text-blue-600">{locality.zone.name}</span>
                                                <ChevronRight className="h-3 w-3" />
                                            </>
                                        )}
                                        {locality.province && (
                                            <>
                                                <Globe className="h-3 w-3 text-indigo-400" />
                                                <span className="text-indigo-600">{locality.province.name}</span>
                                            </>
                                        )}
                                    </div>

                                    <Link
                                        href={route('localities.show', locality.id)}
                                        className="ml-auto text-xs text-indigo-500 hover:underline"
                                    >
                                        Ver
                                    </Link>
                                </li>
                            ))}
                        </ul>
                    )}
                </div>

                {/* Últimos formularios */}
                <div className="rounded-xl border border-gray-200 bg-white shadow-sm">
                    <div className="flex items-center gap-2 border-b border-gray-100 px-6 py-4">
                        <FileText className="h-4 w-4 text-indigo-500" />
                        <h2 className="font-semibold text-gray-800">Últimos formularios recibidos</h2>
                        <span className="ml-auto rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-medium text-indigo-600">
                            {recentSubmissions.length}
                        </span>
                    </div>

                    {recentSubmissions.length === 0 ? (
                        <p className="px-6 py-5 text-sm text-gray-400">Este partner no tiene formularios registrados.</p>
                    ) : (
                        <div className="overflow-x-auto">
                            <table className="w-full text-sm">
                                <thead>
                                    <tr className="border-b border-gray-100 bg-gray-50 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                                        <th className="px-6 py-3">Usuario final</th>
                                        <th className="px-6 py-3">Localidad</th>
                                        <th className="px-6 py-3">Estado</th>
                                        <th className="px-6 py-3">Fecha</th>
                                        <th className="px-6 py-3"></th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-gray-50">
                                    {recentSubmissions.map((submission) => (
                                        <tr key={submission.id} className="hover:bg-gray-50">
                                            <td className="px-6 py-3 font-medium text-gray-800">
                                                {submission.end_user_name}
                                            </td>
                                            <td className="px-6 py-3 text-gray-500">
                                                {submission.locality ?? '—'}
                                            </td>
                                            <td className="px-6 py-3">
                                                <StatusBadge status={submission.status} />
                                            </td>
                                            <td className="px-6 py-3 text-gray-500">
                                                {submission.date}
                                            </td>
                                            <td className="px-6 py-3">
                                                <Link
                                                    href={route('form_submissions.show', submission.id)}
                                                    className="inline-flex items-center gap-1 rounded p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600"
                                                    title="Ver formulario"
                                                >
                                                    <Eye className="h-4 w-4" />
                                                </Link>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    )}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
