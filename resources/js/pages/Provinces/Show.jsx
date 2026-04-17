import { Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { ArrowLeft, Pencil, Globe, Map, MapPin, CheckCircle, User2 } from 'lucide-react';

export default function Show({ province }) {
    const zones = province.zones ?? [];
    const localities = province.localities ?? [];

    return (
        <AuthenticatedLayout header="Provincias / Detalle">
            <div className="mx-auto max-w-3xl space-y-5">
                <Link
                    href={route('provinces.index')}
                    className="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700"
                >
                    <ArrowLeft className="h-4 w-4" /> Volver
                </Link>

                {/* Header card */}
                <div className="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div className="flex items-start justify-between gap-4">
                        <div className="flex items-center gap-3">
                            <div className="flex h-11 w-11 items-center justify-center rounded-full bg-indigo-100">
                                <Globe className="h-5 w-5 text-indigo-600" />
                            </div>
                            <div>
                                <p className="text-xs font-medium uppercase tracking-wide text-gray-400">Provincia</p>
                                <h1 className="text-xl font-bold text-gray-900">{province.name}</h1>
                            </div>
                        </div>
                        <Link
                            href={route('provinces.edit', province.id)}
                            className="flex items-center gap-1.5 rounded-lg border border-gray-300 px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-50"
                        >
                            <Pencil className="h-3.5 w-3.5" />
                            Editar
                        </Link>
                    </div>

                    {/* Stats row */}
                    <div className="mt-5 grid grid-cols-2 gap-3 border-t border-gray-100 pt-5">
                        <div className="flex items-center gap-3 rounded-lg bg-gray-50 px-4 py-3">
                            <Map className="h-5 w-5 text-blue-500" />
                            <div>
                                <p className="text-xs text-gray-500">Zonas</p>
                                <p className="text-lg font-semibold text-gray-800">{zones.length}</p>
                            </div>
                        </div>
                        <div className="flex items-center gap-3 rounded-lg bg-gray-50 px-4 py-3">
                            <MapPin className="h-5 w-5 text-emerald-500" />
                            <div>
                                <p className="text-xs text-gray-500">Localidades</p>
                                <p className="text-lg font-semibold text-gray-800">{localities.length}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Zonas */}
                <div className="rounded-xl border border-gray-200 bg-white shadow-sm">
                    <div className="flex items-center gap-2 border-b border-gray-100 px-6 py-4">
                        <Map className="h-4 w-4 text-blue-500" />
                        <h2 className="font-semibold text-gray-800">Zonas</h2>
                        <span className="ml-auto rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-600">
                            {zones.length}
                        </span>
                    </div>

                    {zones.length === 0 ? (
                        <p className="px-6 py-5 text-sm text-gray-400">Esta provincia no tiene zonas asignadas.</p>
                    ) : (
                        <ul className="divide-y divide-gray-50">
                            {zones.map((zone) => (
                                <li key={zone.id} className="flex items-center gap-3 px-6 py-3">
                                    <CheckCircle className="h-4 w-4 flex-shrink-0 text-blue-400" />
                                    <span className="text-sm text-gray-700">{zone.name}</span>
                                    <Link
                                        href={route('zones.show', zone.id)}
                                        className="ml-auto text-xs text-indigo-500 hover:underline"
                                    >
                                        Ver
                                    </Link>
                                </li>
                            ))}
                        </ul>
                    )}
                </div>

                {/* Localidades */}
                <div className="rounded-xl border border-gray-200 bg-white shadow-sm">
                    <div className="flex items-center gap-2 border-b border-gray-100 px-6 py-4">
                        <MapPin className="h-4 w-4 text-emerald-500" />
                        <h2 className="font-semibold text-gray-800">Localidades</h2>
                        <span className="ml-auto rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-600">
                            {localities.length}
                        </span>
                    </div>

                    {localities.length === 0 ? (
                        <p className="px-6 py-5 text-sm text-gray-400">Esta provincia no tiene localidades asignadas.</p>
                    ) : (
                        <ul className="divide-y divide-gray-50">
                            {localities.map((locality) => (
                                <li key={locality.id} className="flex items-center gap-3 px-6 py-3">
                                    <CheckCircle className="h-4 w-4 flex-shrink-0 text-emerald-400" />
                                    <span className="text-sm font-medium text-gray-700">{locality.name}</span>

                                    {locality.user ? (
                                        <span className="ml-4 flex items-center gap-1.5 rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs text-indigo-700">
                                            <User2 className="h-3 w-3" />
                                            {locality.user.name}
                                        </span>
                                    ) : (
                                        <span className="ml-4 flex items-center gap-1.5 rounded-full bg-gray-100 px-2.5 py-0.5 text-xs text-gray-400">
                                            <User2 className="h-3 w-3" />
                                            Sin partner
                                        </span>
                                    )}

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
            </div>
        </AuthenticatedLayout>
    );
}
