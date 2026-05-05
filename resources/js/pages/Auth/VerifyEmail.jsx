import PrimaryButton from '@/Components/PrimaryButton';
import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link, useForm } from '@inertiajs/react';

export default function VerifyEmail({ status }) {
    const { post, processing } = useForm({});

    const submit = (e) => {
        e.preventDefault();
        post(route('verification.send'));
    };

    return (
        <GuestLayout>
            <Head title="Verificar correo electrónico" />

            <div className="mb-6">
                <h1 className="text-2xl font-bold text-gray-900">Verificá tu correo</h1>
                <p className="mt-2 text-sm text-gray-500">
                    Te enviamos un enlace de verificación a tu dirección de correo. Si no lo recibiste, podés solicitar uno nuevo.
                </p>
            </div>

            {status === 'verification-link-sent' && (
                <div className="mb-4 text-sm font-medium text-green-600">
                    Se envió un nuevo enlace de verificación a tu correo electrónico.
                </div>
            )}

            <form onSubmit={submit} className="space-y-4">
                <PrimaryButton className="w-full justify-center py-2.5" disabled={processing}>
                    Reenviar enlace de verificación
                </PrimaryButton>

                <div className="text-center">
                    <Link
                        href={route('logout')}
                        method="post"
                        as="button"
                        className="text-sm text-gray-500 underline hover:text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#FF7500] focus:ring-offset-2 rounded"
                    >
                        Cerrar sesión
                    </Link>
                </div>
            </form>
        </GuestLayout>
    );
}
