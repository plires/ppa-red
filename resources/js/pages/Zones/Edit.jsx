import { useForm, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import InputLabel from '@/Components/InputLabel';
import TextInput from '@/Components/TextInput';
import InputError from '@/Components/InputError';
import PrimaryButton from '@/Components/PrimaryButton';
import { ArrowLeft } from 'lucide-react';

export default function Edit({ zone, provinces }) {
    const { data, setData, put, processing, errors } = useForm({
        name: zone.name,
        province_id: zone.province_id ?? '',
    });

    function submit(e) {
        e.preventDefault();
        put(route('zones.update', zone.id));
    }

    return (
        <AuthenticatedLayout header="Zonas / Editar">
            <div className="mx-auto max-w-xl">
                <Link
                    href={route('zones.index')}
                    className="mb-4 inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700"
                >
                    <ArrowLeft className="h-4 w-4" /> Volver
                </Link>

                <div className="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 className="mb-6 text-lg font-semibold text-gray-800">
                        Editar Zona: <span className="text-indigo-600">{zone.name}</span>
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
                                onChange={(e) => setData('province_id', e.target.value)}
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

                        <div className="flex justify-end gap-3 pt-2">
                            <Link
                                href={route('zones.index')}
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
