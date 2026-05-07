import { Head, Link } from '@inertiajs/react';
import { SearchX } from 'lucide-react';
import ApplicationLogo from '@/Components/ApplicationLogo';

export default function NotFound() {
    return (
        <>
            <Head title="Formulario no encontrado — PPA RED" />
            <div className="min-h-screen bg-[#F2F2F2] flex flex-col">

                {/* Header institucional */}
                <header style={{ background: 'linear-gradient(135deg, #FD3C00 0%, #FF7500 100%)' }}>
                    <div className="mx-auto max-w-4xl px-4 py-5 sm:px-6">
                        <div className="flex items-center gap-4">
                            <a href="/" aria-label="Inicio">
                                <ApplicationLogo className="h-12 w-auto text-white drop-shadow" />
                            </a>
                            <p className="text-xs font-light uppercase tracking-widest text-white/70">
                                Seguimiento de consulta
                            </p>
                        </div>
                    </div>
                </header>

                {/* Contenido */}
                <main className="flex flex-1 items-center justify-center px-4 py-16 sm:px-6">
                    <div className="w-full max-w-md rounded-2xl border border-gray-200 bg-white p-10 shadow-sm text-center">
                        <div
                            className="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full"
                            style={{ background: 'linear-gradient(135deg, #FD3C00, #FF7500)' }}
                        >
                            <SearchX className="h-8 w-8 text-white" />
                        </div>

                        <h1 className="mb-2 text-xl font-bold text-gray-900">
                            Formulario no encontrado
                        </h1>
                        <p className="mb-8 text-sm leading-relaxed text-gray-500">
                            El enlace que ingresaste no corresponde a ninguna consulta registrada.
                            Es posible que el enlace sea incorrecto o haya expirado.
                        </p>

                        <a
                            href="/"
                            className="inline-flex items-center justify-center rounded-xl px-6 py-3 text-sm font-semibold text-white shadow transition hover:opacity-90"
                            style={{ background: 'linear-gradient(135deg, #FD3C00, #FF7500)' }}
                        >
                            Ir al inicio
                        </a>
                    </div>
                </main>

            </div>
        </>
    );
}
