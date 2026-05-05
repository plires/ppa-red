import InputError from '@/Components/InputError';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link, useForm } from '@inertiajs/react';

export default function ForgotPassword({ status }) {
    const { data, setData, post, processing, errors } = useForm({
        email: '',
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('password.email'));
    };

    return (
        <GuestLayout>
            <Head title="Recuperar contraseña" />

            <div className="mb-6">
                <h1 className="text-2xl font-bold text-gray-900">Recuperar contraseña</h1>
                <p className="mt-2 text-sm text-gray-500">
                    Ingresá tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.
                </p>
            </div>

            {status && (
                <div className="mb-4 text-sm font-medium text-green-600">
                    {status}
                </div>
            )}

            <form onSubmit={submit} className="space-y-5">
                <div>
                    <TextInput
                        id="email"
                        type="email"
                        name="email"
                        value={data.email}
                        className="block w-full"
                        placeholder="tu@correo.com"
                        isFocused={true}
                        onChange={(e) => setData('email', e.target.value)}
                    />
                    <InputError message={errors.email} className="mt-2" />
                </div>

                <PrimaryButton className="w-full justify-center py-2.5" disabled={processing}>
                    Enviar enlace de recuperación
                </PrimaryButton>

                <div className="text-center">
                    <Link
                        href={route('login')}
                        className="text-sm text-gray-500 underline hover:text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#FF7500] focus:ring-offset-2 rounded"
                    >
                        Volver al inicio de sesión
                    </Link>
                </div>
            </form>
        </GuestLayout>
    );
}
