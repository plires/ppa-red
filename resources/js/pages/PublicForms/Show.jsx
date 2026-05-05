import { Head, useForm, usePage } from '@inertiajs/react';
import { useEffect, useRef, useState } from 'react';
import { MapPin, Phone, Mail, Calendar, Send, MessageCircle, CheckCircle, XCircle } from 'lucide-react';
import ApplicationLogo from '@/Components/ApplicationLogo';

const STATUS_COLORS = {
    'Pendiente de Respuesta Del Partner': 'bg-yellow-100 text-yellow-800 border-yellow-200',
    'Respondido Por El Partner':          'bg-green-100 text-green-800 border-green-200',
    'Demorado - Sin Respuesta Del Partner (48h)': 'bg-orange-100 text-orange-800 border-orange-200',
    'Cerrado - Sin Respuesta Del Partner':  'bg-red-100 text-red-800 border-red-200',
    'Cerrado - Sin Respuesta Del Usuario':  'bg-red-100 text-red-800 border-red-200',
    'Cerrado Por El Partner':               'bg-gray-100 text-gray-700 border-gray-200',
};

function StatusBadge({ name }) {
    return (
        <span className={`inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold ${STATUS_COLORS[name] ?? 'bg-gray-100 text-gray-600 border-gray-200'}`}>
            {name ?? '—'}
        </span>
    );
}

export default function PublicFormShow({ formSubmission, formData, isClosed, closedByPartner }) {
    const { flash } = usePage().props;
    const [showFlash, setShowFlash] = useState(true);
    const messagesEndRef = useRef(null);

    const { data, setData, post, processing, errors, reset } = useForm({
        message: '',
        form_submission_id: formSubmission.id,
        user_id: formSubmission.user_id,
    });

    const responses = formSubmission.form_responses ?? [];

    useEffect(() => {
        messagesEndRef.current?.scrollIntoView({ behavior: 'smooth' });
    }, [responses]);

    function submit(e) {
        e.preventDefault();
        post(route('public.form_responses.store'), {
            onSuccess: () => reset('message'),
        });
    }

    const locality = formSubmission.locality;
    const locationParts = [
        locality?.name,
        formSubmission.zone?.name,
        formSubmission.province?.name,
    ].filter(Boolean).join(', ');

    const partnerInitial = formSubmission.user?.name?.charAt(0)?.toUpperCase() ?? 'P';

    return (
        <>
            <Head title="Seguimiento de consulta — PPA RED" />
            <div className="min-h-screen bg-[#F2F2F2]">

                {/* ── Header institucional ── */}
                <header style={{ background: 'linear-gradient(135deg, #FD3C00 0%, #FF7500 100%)' }}>
                    <div className="mx-auto max-w-4xl px-4 py-5 sm:px-6">
                        <div className="flex items-center gap-4">
                            <a href="/" aria-label="Inicio">
                                <ApplicationLogo className="h-12 w-auto text-white drop-shadow" />
                            </a>
                            <div>
                                <p className="text-xs font-light uppercase tracking-widest text-white/70">
                                    Seguimiento de consulta
                                </p>
                            </div>
                        </div>
                    </div>
                </header>

                <main className="mx-auto max-w-4xl px-4 py-8 sm:px-6 space-y-5">

                    {/* ── Flash messages ── */}
                    {flash?.success && showFlash && (
                        <div className="flex items-start gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                            <CheckCircle className="mt-0.5 h-4 w-4 flex-shrink-0 text-green-500" />
                            <span className="flex-1">{flash.success}</span>
                            <button onClick={() => setShowFlash(false)} className="text-green-500 hover:text-green-700 font-bold leading-none">×</button>
                        </div>
                    )}
                    {flash?.error && showFlash && (
                        <div className="flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                            <XCircle className="mt-0.5 h-4 w-4 flex-shrink-0 text-red-500" />
                            <span className="flex-1">{flash.error}</span>
                            <button onClick={() => setShowFlash(false)} className="text-red-500 hover:text-red-700 font-bold leading-none">×</button>
                        </div>
                    )}

                    {/* ── Estado de la consulta ── */}
                    <div className="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                        <div className="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <h1 className="text-lg font-semibold text-gray-900">
                                    Consulta de{' '}
                                    <span style={{ color: '#FF7500' }}>{formData?.name}</span>
                                </h1>
                                <p className="mt-1 flex items-center gap-1.5 text-sm text-gray-500">
                                    <Calendar className="h-4 w-4" />
                                    {formSubmission.formatted_date ?? new Date(formSubmission.created_at).toLocaleDateString('es-AR')}
                                </p>
                            </div>
                            <StatusBadge name={formSubmission.status?.name} />
                        </div>
                    </div>

                    {/* ── Info cards ── */}
                    <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        {/* Tu información */}
                        <div className="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                            <h2 className="mb-3 text-xs font-semibold uppercase tracking-wide text-gray-400">Tu información</h2>
                            <p className="font-semibold text-gray-900">{formData?.name}</p>
                            <ul className="mt-2 space-y-1.5 text-sm text-gray-600">
                                {formData?.email && (
                                    <li className="flex items-center gap-2">
                                        <Mail className="h-4 w-4 flex-shrink-0 text-gray-400" />
                                        {formData.email}
                                    </li>
                                )}
                                {formData?.phone && (
                                    <li className="flex items-center gap-2">
                                        <Phone className="h-4 w-4 flex-shrink-0 text-gray-400" />
                                        {formData.phone}
                                    </li>
                                )}
                                {locationParts && (
                                    <li className="flex items-center gap-2">
                                        <MapPin className="h-4 w-4 flex-shrink-0 text-gray-400" />
                                        {locationParts}
                                    </li>
                                )}
                            </ul>
                        </div>

                        {/* Partner asignado */}
                        <div className="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                            <h2 className="mb-3 text-xs font-semibold uppercase tracking-wide text-gray-400">Tu partner PPA RED</h2>
                            <div className="flex items-center gap-3">
                                <div
                                    className="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full text-base font-bold text-white"
                                    style={{ background: 'linear-gradient(135deg, #FD3C00, #FF7500)' }}
                                >
                                    {partnerInitial}
                                </div>
                                <div>
                                    <p className="font-semibold text-gray-900">{formSubmission.user?.name ?? '—'}</p>
                                    {formSubmission.user?.email && (
                                        <p className="text-xs text-gray-500">{formSubmission.user.email}</p>
                                    )}
                                </div>
                            </div>
                            {formSubmission.user?.phone && (
                                <ul className="mt-3 space-y-1 text-sm text-gray-600">
                                    <li className="flex items-center gap-2">
                                        <Phone className="h-4 w-4 flex-shrink-0 text-gray-400" />
                                        {formSubmission.user.phone}
                                    </li>
                                </ul>
                            )}
                        </div>
                    </div>

                    {/* ── Consulta original ── */}
                    {formData?.message && (
                        <div className="rounded-2xl border border-orange-200 bg-orange-50 p-5">
                            <h2 className="mb-2 flex items-center gap-2 text-sm font-semibold" style={{ color: '#FF7500' }}>
                                <MessageCircle className="h-4 w-4" />
                                Consulta original
                            </h2>
                            <p className="text-sm leading-relaxed text-gray-700 whitespace-pre-line">{formData.message}</p>
                        </div>
                    )}

                    {/* ── Conversación tipo chat ── */}
                    <div className="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

                        {/* Chat header */}
                        <div className="flex items-center gap-3 px-5 py-4" style={{ background: 'linear-gradient(90deg, #FD3C00, #FF7500)' }}>
                            <div className="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-white/20 text-sm font-bold text-white">
                                {partnerInitial}
                            </div>
                            <div>
                                <p className="text-sm font-semibold text-white">{formSubmission.user?.name ?? 'Partner PPA RED'}</p>
                                <p className="text-xs text-white/70">Partner PPA RED</p>
                            </div>
                            {isClosed && (
                                <span className="ml-auto rounded-full bg-white/20 px-2.5 py-0.5 text-xs font-medium text-white">
                                    Cerrada
                                </span>
                            )}
                        </div>

                        {/* Bubbles area */}
                        <div className="min-h-[180px] max-h-[420px] overflow-y-auto space-y-3 bg-[#F2F2F2] px-4 py-5">
                            {responses.length === 0 ? (
                                <div className="flex flex-col items-center justify-center py-10 text-center">
                                    <MessageCircle className="h-8 w-8 text-gray-300 mb-2" />
                                    <p className="text-sm text-gray-400">Tu consulta fue recibida.</p>
                                    <p className="text-xs text-gray-400 mt-0.5">Tu partner te responderá pronto.</p>
                                </div>
                            ) : (
                                responses.map((response) => {
                                    // is_system = 1 → mensaje del partner (izquierda, gris)
                                    // is_system = 0 → mensaje del usuario (derecha, naranja)
                                    if (!response.user_id) {
                                        return (
                                            <div key={response.id} className="flex justify-center py-1">
                                                <span className="rounded-full bg-white px-3 py-1 text-xs text-gray-400 shadow-sm border border-gray-200">
                                                    {response.message}
                                                </span>
                                            </div>
                                        );
                                    }

                                    const isPartner = !!response.is_system;
                                    const time = new Date(response.created_at).toLocaleDateString('es-AR', {
                                        day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit',
                                    });

                                    return (
                                        <div key={response.id} className={`flex ${isPartner ? 'justify-start' : 'justify-end'}`}>
                                            <div
                                                className={`max-w-[78%] rounded-2xl px-4 py-3 text-sm shadow-sm ${
                                                    isPartner
                                                        ? 'rounded-tl-none bg-white text-gray-800'
                                                        : 'rounded-tr-none text-white'
                                                }`}
                                                style={!isPartner ? { background: 'linear-gradient(135deg, #FD3C00, #FF7500)' } : {}}
                                            >
                                                <p className="whitespace-pre-line leading-relaxed">{response.message}</p>
                                                <p className={`mt-1.5 text-xs ${isPartner ? 'text-gray-400' : 'text-white/70'}`}>
                                                    {isPartner ? (response.user?.name ?? 'Partner') : (formData?.name ?? 'Vos')}
                                                    {' · '}{time}
                                                </p>
                                            </div>
                                        </div>
                                    );
                                })
                            )}
                            <div ref={messagesEndRef} />
                        </div>

                        {/* Input de respuesta */}
                        <div className="border-t border-gray-100 bg-white px-4 py-3">
                            {!isClosed ? (
                                <form onSubmit={submit} className="flex items-center gap-2">
                                    <input
                                        type="text"
                                        value={data.message}
                                        onChange={(e) => setData('message', e.target.value)}
                                        onKeyDown={(e) => {
                                            if (e.key === 'Enter') { e.preventDefault(); submit(e); }
                                        }}
                                        placeholder="Escribí tu mensaje..."
                                        className="flex-1 rounded-full border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm outline-none transition focus:border-transparent focus:ring-2"
                                        style={{ '--tw-ring-color': '#FF7500' }}
                                    />
                                    <button
                                        type="submit"
                                        disabled={processing || !data.message.trim()}
                                        className="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full text-white shadow transition disabled:cursor-not-allowed disabled:opacity-50 hover:opacity-90"
                                        style={{ background: 'linear-gradient(135deg, #FD3C00, #FF7500)' }}
                                        title="Enviar"
                                    >
                                        <Send className="h-4 w-4" />
                                    </button>
                                </form>
                            ) : (
                                <div className="flex items-start gap-3 rounded-xl bg-gray-50 p-4 text-sm">
                                    <XCircle className="mt-0.5 h-4 w-4 flex-shrink-0 text-gray-400" />
                                    <div>
                                        <p className="font-medium text-gray-700">Esta consulta fue cerrada.</p>
                                        {!closedByPartner && formSubmission.closure_reason && (
                                            <p className="mt-0.5 text-gray-500">Motivo: {formSubmission.closure_reason}</p>
                                        )}
                                        <p className="mt-1 text-gray-500">Si necesitás más ayuda, contactá directamente a tu partner.</p>
                                    </div>
                                </div>
                            )}
                            {errors.message && (
                                <p className="mt-1 px-1 text-xs text-red-600">{errors.message}</p>
                            )}
                        </div>
                    </div>

                    {/* ── Footer ── */}
                    <div className="pb-4 text-center text-xs text-gray-400">
                        PPA RED &mdash; Sistema de gestión de consultas
                    </div>
                </main>
            </div>
        </>
    );
}
