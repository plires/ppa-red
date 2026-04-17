import { useState } from 'react';
import { useForm, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import InputLabel from '@/Components/InputLabel';
import TextInput from '@/Components/TextInput';
import InputError from '@/Components/InputError';
import PrimaryButton from '@/Components/PrimaryButton';
import { ArrowLeft } from 'lucide-react';

export default function Edit({ locality, provinces, partners }) {
    const initialProvince = provinces.find(
        (p) => p.id === locality.province_id,
    );

    const [zones, setZones] = useState(initialProvince?.zones ?? []);

    const { data, setData, put, processing, errors } = useForm({
        name: locality.name,
        province_id: locality.province_id ?? '',
        zone_id: locality.zone_id ?? '',
        user_id: locality.user_id ?? '',
    });

    function handleProvinceChange(e) {
        const provinceId = e.target.value;
        setData((prev) => ({ ...prev, province_id: provinceId, zone_id: '' }));

        const province = provinces.find((p) => p.id == provinceId);
        setZones(province?.zones ?? []);
    }

    function submit(e) {
        e.preventDefault();
        put(route('localities.update', locality.id));
    }

    return (
        <AuthenticatedLayout header="Localidades / Editar">
            <div className="mx-auto max-w-xl">
                <Link
                    href={route('localities.index')}
                    className="mb-4 inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700"
                >
                    <ArrowLeft className="h-4 w-4" /> Volver
                </Link>

                <div className="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 className="mb-6 text-lg font-semibold text-gray-800">
                        Editar Localidad:{' '}
                        <span className="text-indigo-600">{locality.name}</span>
                    </h2>

                    <form onSubmit={submit} className="space-y-4">
                        <div>
                            <InputLabel htmlFor="name" value="Nombre" />
                            <TextInput
                                id="name"
                                value={data.name}
                                onChange={(e) => setData('name', e.target.value)}
                                className="mt-1 w-full"
                                autoFocus
                            />
                            <InputError message={errors.name} className="mt-1" />
                        </div>

                        <div>
                            <InputLabel htmlFor="province_id" value="Provincia" />
                            <select
                                id="province_id"
                                value={data.province_id}
                                onChange={handleProvinceChange}
                                className="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                            >
                                <option value="">Seleccioná una provincia</option>
                                {provinces.map((p) => (
                                    <option key={p.id} value={p.id}>
                                        {p.name}
                                    </option>
                                ))}
                            </select>
                            <InputError message={errors.province_id} className="mt-1" />
                        </div>

                        <div>
                            <InputLabel htmlFor="zone_id" value="Zona" />
                            <select
                                id="zone_id"
                                value={data.zone_id}
                                onChange={(e) => setData('zone_id', e.target.value)}
                                disabled={!zones.length}
                                className="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm disabled:opacity-50"
                            >
                                <option value="">Seleccioná una zona</option>
                                {zones.map((z) => (
                                    <option key={z.id} value={z.id}>
                                        {z.name}
                                    </option>
                                ))}
                            </select>
                            <InputError message={errors.zone_id} className="mt-1" />
                        </div>

                        <div>
                            <InputLabel htmlFor="user_id" value="Partner asignado" />
                            <select
                                id="user_id"
                                value={data.user_id}
                                onChange={(e) => setData('user_id', e.target.value)}
                                className="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                            >
                                <option value="">Sin asignar</option>
                                {partners.map((p) => (
                                    <option key={p.id} value={p.id}>
                                        {p.name}
                                    </option>
                                ))}
                            </select>
                            <InputError message={errors.user_id} className="mt-1" />
                        </div>

                        <div className="flex justify-end gap-3 pt-2">
                            <Link
                                href={route('localities.index')}
                                className="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50"
                            >
                                Cancelar
                            </Link>
                            <PrimaryButton disabled={processing}>
                                {processing ? 'Guardando...' : 'Actualizar'}
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
