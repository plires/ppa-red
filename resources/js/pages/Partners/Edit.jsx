import { useState } from 'react';
import { useForm, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import InputLabel from '@/Components/InputLabel';
import TextInput from '@/Components/TextInput';
import InputError from '@/Components/InputError';
import PrimaryButton from '@/Components/PrimaryButton';
import { ArrowLeft } from 'lucide-react';

export default function Edit({ partner }) {
    const [changePassword, setChangePassword] = useState(false);

    const { data, setData, put, processing, errors, setError } = useForm({
        name: partner.name,
        email: partner.email,
        phone: partner.phone ?? '',
        role: partner.role,
        password: '',
        password_confirmation: '',
    });

    function handleTogglePassword(checked) {
        setChangePassword(checked);
        if (!checked) {
            setData({ ...data, password: '', password_confirmation: '' });
        }
    }

    function submit(e) {
        e.preventDefault();

        if (changePassword) {
            if (!data.password) {
                setError('password', 'El campo contraseña es obligatorio.');
                return;
            }
            if (!data.password_confirmation) {
                setError('password_confirmation', 'El campo confirmar contraseña es obligatorio.');
                return;
            }
        }

        put(route('partners.update', partner.id));
    }

    return (
        <AuthenticatedLayout header="Partners / Editar">
            <div className="mx-auto max-w-xl">
                <Link
                    href={route('partners.index')}
                    className="mb-4 inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700"
                >
                    <ArrowLeft className="h-4 w-4" /> Volver
                </Link>

                <div className="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 className="mb-6 text-lg font-semibold text-gray-800">
                        Editar Partner:{' '}
                        <span className="text-indigo-600">{partner.name}</span>
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

                        {/* Toggle cambiar contraseña */}
                        <div className="flex items-center gap-2 pt-1">
                            <input
                                id="change_password"
                                type="checkbox"
                                checked={changePassword}
                                onChange={(e) => handleTogglePassword(e.target.checked)}
                                className="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                            />
                            <label htmlFor="change_password" className="text-sm text-gray-700">
                                Cambiar contraseña
                            </label>
                        </div>

                        {changePassword && (
                            <>
                                <div>
                                    <InputLabel htmlFor="password" value="Nueva contraseña" />
                                    <TextInput
                                        id="password"
                                        type="password"
                                        value={data.password}
                                        onChange={(e) => setData('password', e.target.value)}
                                        className="mt-1 w-full"
                                        autoComplete="new-password"
                                    />
                                    <InputError message={errors.password} className="mt-1" />
                                </div>

                                <div>
                                    <InputLabel htmlFor="password_confirmation" value="Confirmar contraseña" />
                                    <TextInput
                                        id="password_confirmation"
                                        type="password"
                                        value={data.password_confirmation}
                                        onChange={(e) => setData('password_confirmation', e.target.value)}
                                        className="mt-1 w-full"
                                        autoComplete="new-password"
                                    />
                                    <InputError message={errors.password_confirmation} className="mt-1" />
                                </div>
                            </>
                        )}

                        <div className="flex justify-end gap-3 pt-2">
                            <Link
                                href={route('partners.index')}
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
