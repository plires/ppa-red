import { Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { ArrowLeft, Pencil, MapPin, Map, Globe, User2, Mail, Phone } from 'lucide-react';

export default function Show({ locality }) {
    return (
        <AuthenticatedLayout header="Localidades / Detalle">
            <div className="mx-auto max-w-3xl space-y-5">
                <Link
                    href={route('localities.index')}
                    className="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700"
                >
                    <ArrowLeft className="h-4 w-4" /> Volver
                </Link>

                {/* Header card */}
                <div className="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div className="flex items-start justify-between gap-4">
                        <div className="flex items-center gap-3">
                            <div className="flex h-11 w-11 items-center justify-center rounded-full bg-emerald-100">
                                <MapPin className="h-5 w-5 text-emerald-600" />
                            </div>
                            <div>
                                <p className="text-xs font-medium uppercase tracking-wide text-gray-400">Localidad</p>
                                <h1 className="text-xl font-bold text-gray-900">{locality.name}</h1>
                            </div>
                        </div>
                        <Link
                            href={route('localities.edit', locality.id)}
                            className="flex items-center gap-1.5 rounded-lg border border-gray-300 px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-50"
                        >
                            <Pencil className="h-3.5 w-3.5" />
                            Editar
                        </Link>
                    </div>

                    {/* Jerarquía geográfica */}
                    <div className="mt-5 grid grid-cols-2 gap-3 border-t border-gray-100 pt-5">
                        <div className="flex items-center gap-3 rounded-lg bg-gray-50 px-4 py-3">
                            <Globe className="h-5 w-5 text-indigo-500" />
                            <div>
                                <p className="text-xs text-gray-500">Provincia</p>
                                {locality.province ? (
                                    <Link
                                        href={route('provinces.show', locality.province.id)}
                                        className="text-sm font-semibold text-indigo-600 hover:underline"
                                    >
                                        {locality.province.name}
                                    </Link>
                                ) : (
                                    <p className="text-sm font-semibold text-gray-400">—</p>
                                )}
                            </div>
                        </div>
                        <div className="flex items-center gap-3 rounded-lg bg-gray-50 px-4 py-3">
                            <Map className="h-5 w-5 text-blue-500" />
                            <div>
                                <p className="text-xs text-gray-500">Zona</p>
                                {locality.zone ? (
                                    <Link
                                        href={route('zones.show', locality.zone.id)}
                                        className="text-sm font-semibold text-indigo-600 hover:underline"
                                    >
                                        {locality.zone.name}
                                    </Link>
                                ) : (
                                    <p className="text-sm font-semibold text-gray-400">—</p>
                                )}
                            </div>
                        </div>
                    </div>
                </div>

                {/* Partner asignado */}
                <div className="rounded-xl border border-gray-200 bg-white shadow-sm">
                    <div className="flex items-center gap-2 border-b border-gray-100 px-6 py-4">
                        <User2 className="h-4 w-4 text-indigo-500" />
                        <h2 className="font-semibold text-gray-800">Partner asignado</h2>
                    </div>

                    {locality.user ? (
                        <div className="flex items-center gap-4 px-6 py-5">
                            <div className="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-indigo-600 text-sm font-bold text-white">
                                {locality.user.name.charAt(0).toUpperCase()}
                            </div>
                            <div className="min-w-0 flex-1">
                                <p className="font-semibold text-gray-900">{locality.user.name}</p>
                                <div className="mt-1 flex flex-wrap gap-4">
                                    {locality.user.email && (
                                        <span className="flex items-center gap-1 text-xs text-gray-500">
                                            <Mail className="h-3 w-3" />
                                            {locality.user.email}
                                        </span>
                                    )}
                                    {locality.user.phone && (
                                        <span className="flex items-center gap-1 text-xs text-gray-500">
                                            <Phone className="h-3 w-3" />
                                            {locality.user.phone}
                                        </span>
                                    )}
                                </div>
                            </div>
                            <Link
                                href={route('partners.show', locality.user.id)}
                                className="text-xs text-indigo-500 hover:underline"
                            >
                                Ver perfil
                            </Link>
                        </div>
                    ) : (
                        <div className="flex items-center gap-3 px-6 py-5">
                            <div className="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-gray-100">
                                <User2 className="h-5 w-5 text-gray-400" />
                            </div>
                            <p className="text-sm text-gray-400">Sin partner asignado</p>
                        </div>
                    )}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
