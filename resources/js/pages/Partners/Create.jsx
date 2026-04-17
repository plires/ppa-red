import { useForm, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import InputLabel from '@/Components/InputLabel';
import TextInput from '@/Components/TextInput';
import InputError from '@/Components/InputError';
import PrimaryButton from '@/Components/PrimaryButton';
import { ArrowLeft } from 'lucide-react';

export default function Create() {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        email: '',
        phone: '',
        password: '',
        role: 'partner',
    });

    function submit(e) {
        e.preventDefault();
        post(route('partners.store'));
    }

    return (
        <AuthenticatedLayout header="Partners / Nuevo">
            <div className="mx-auto max-w-xl">
                <Link
                    href={route('partners.index')}
                    className="mb-4 inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700"
                >
                    <ArrowLeft className="h-4 w-4" /> Volver
                </Link>

                <div className="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 className="mb-6 text-lg font-semibold text-gray-800">Nuevo Partner</h2>

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
                            <InputLabel htmlFor="email" value="Email" />
                            <TextInput
                                id="email"
                                type="email"
                                value={data.email}
                                onChange={(e) => setData('email', e.target.value)}
                                className="mt-1 w-full"
                            />
                            <InputError message={errors.email} className="mt-1" />
                        </div>

                        <div>
                            <InputLabel htmlFor="phone" value="Teléfono" />
                            <TextInput
                                id="phone"
                                type="tel"
                                value={data.phone}
                                onChange={(e) => setData('phone', e.target.value)}
                                className="mt-1 w-full"
                            />
                            <InputError message={errors.phone} className="mt-1" />
                        </div>

                        <div>
                            <InputLabel htmlFor="password" value="Contraseña" />
                            <TextInput
                                id="password"
                                type="password"
                                value={data.password}
                                onChange={(e) => setData('password', e.target.value)}
                                className="mt-1 w-full"
                            />
                            <InputError message={errors.password} className="mt-1" />
                        </div>

                        <div>
                            <InputLabel htmlFor="role" value="Rol" />
                            <select
                                id="role"
                                value={data.role}
                                onChange={(e) => setData('role', e.target.value)}
                                className="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                            >
                                <option value="partner">Partner</option>
                                <option value="admin">Admin</option>
                            </select>
                            <InputError message={errors.role} className="mt-1" />
                        </div>

                        <div className="flex justify-end gap-3 pt-2">
                            <Link
                                href={route('partners.index')}
                                className="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50"
                            >
                                Cancelar
                            </Link>
                            <PrimaryButton disabled={processing}>
                                {processing ? 'Guardando...' : 'Guardar'}
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
