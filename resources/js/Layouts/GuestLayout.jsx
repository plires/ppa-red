import ApplicationLogo from '@/Components/ApplicationLogo';
import { Link } from '@inertiajs/react';

export default function GuestLayout({ children }) {
    return (
        <div className="flex min-h-screen">
            {/* Left brand panel — hidden on mobile */}
            <div className="relative hidden overflow-hidden lg:flex lg:w-[480px] lg:flex-shrink-0 lg:flex-col lg:items-center lg:justify-center"
                style={{ background: 'linear-gradient(135deg, #FD3C00 0%, #FF7500 100%)' }}>

                {/* Decorative circles */}
                <div className="absolute -right-24 -top-24 h-72 w-72 rounded-full bg-white/10" />
                <div className="absolute -bottom-32 -left-20 h-96 w-96 rounded-full bg-black/10" />
                <div className="absolute right-12 bottom-40 h-40 w-40 rounded-full bg-white/5" />
                <div className="absolute left-8 top-1/3 h-20 w-20 rounded-full bg-white/10" />

                {/* Content */}
                <div className="relative z-10 flex flex-col items-center px-16 text-center">
                    <Link href="/" className="block">
                        <ApplicationLogo className="h-52 w-auto text-white drop-shadow-lg" />
                    </Link>

                    <div className="mt-10 space-y-3">
                        <div className="mx-auto h-px w-20 bg-white/40" />
                        <p className="text-sm font-light uppercase tracking-[0.3em] text-white/80">
                            Sistema de Gestión
                        </p>
                    </div>
                </div>
            </div>

            {/* Right form panel */}
            <div className="flex flex-1 flex-col items-center justify-center bg-[#F2F2F2] px-6 py-12">

                {/* Mobile brand header */}
                <div className="mb-8 flex flex-col items-center lg:hidden">
                    <Link href="/">
                        <div className="flex h-20 w-20 items-center justify-center rounded-2xl"
                            style={{ background: 'linear-gradient(135deg, #FD3C00 0%, #FF7500 100%)' }}>
                            <ApplicationLogo className="h-12 w-auto text-white" />
                        </div>
                    </Link>
                    <p className="mt-3 text-xs font-light uppercase tracking-widest text-gray-500">
                        Sistema de Gestión
                    </p>
                </div>

                {/* Form card */}
                <div className="w-full max-w-md">
                    <div className="rounded-2xl bg-white px-8 py-10 shadow-sm ring-1 ring-black/5">
                        {children}
                    </div>
                    <p className="mt-6 text-center text-xs text-gray-400">
                        PPA RED &mdash; Panel de Administración
                    </p>
                </div>
            </div>
        </div>
    );
}
