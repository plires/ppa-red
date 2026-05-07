import { useEffect, useRef, useState } from 'react';
import { useForm, Link, usePage } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import InputError from '@/Components/InputError';
import DangerButton from '@/Components/DangerButton';
import Modal from '@/Components/Modal';
import SecondaryButton from '@/Components/SecondaryButton';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/Components/ui/select';
import {
    ArrowLeft,
    Send,
    XCircle,
    MapPin,
    Phone,
    Mail,
    Calendar,
    MessageCircle,
    ExternalLink,
    UserRoundCog,
} from 'lucide-react';

const STATUS_COLORS = {
    'Pendiente de Respuesta Del Partner':         'bg-yellow-100 text-yellow-800 border-yellow-200',
    'Respondido Por El Partner':                  'bg-green-100 text-green-800 border-green-200',
    'Demorado - Sin Respuesta Del Partner (48h)': 'bg-orange-100 text-orange-800 border-orange-200',
    'Cerrado - Sin Respuesta Del Partner':        'bg-red-100 text-red-800 border-red-200',
    'Cerrado - Sin Respuesta Del Usuario':        'bg-red-100 text-red-800 border-red-200',
    'Cerrado Por El Partner':                     'bg-gray-100 text-gray-700 border-gray-200',
};

function StatusBadge({ status }) {
    return (
        <span className={`inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold ${STATUS_COLORS[status] ?? 'bg-gray-100 text-gray-600 border-gray-200'}`}>
            {status ?? '—'}
        </span>
    );
}

export default function Show({ formSubmission, formData, responses, partners = [] }) {
    const { auth } = usePage().props;
    const user = auth.user;
    const isPartner = user.role === 'partner';
    const isAdmin = user.role === 'admin';
    const isClosed = formSubmission.status?.name?.startsWith('Cerrado');
    const messagesEndRef = useRef(null);
    const [showCloseModal, setShowCloseModal] = useState(false);
    const [showReassignModal, setShowReassignModal] = useState(false);

    const responseForm = useForm({
        message: '',
        form_submission_id: formSubmission.id,
        user_id: formSubmission.user_id,
    });

    const closeForm = useForm({
        closure_reason: '',
        form_submission_id: formSubmission.id,
        user_id: formSubmission.user_id,
    });

    const reassignForm = useForm({ partner_id: '' });

    useEffect(() => {
        messagesEndRef.current?.scrollIntoView({ behavior: 'smooth' });
    }, [responses]);

    function submitResponse(e) {
        e.preventDefault();
        responseForm.post(route('form_responses.store'), {
            onSuccess: () => responseForm.reset('message'),
        });
    }

    function submitClose(e) {
        e.preventDefault();
        closeForm.put(route('form_submissions.update', formSubmission.id), {
            onSuccess: () => setShowCloseModal(false),
        });
    }

    function submitReassign(e) {
        e.preventDefault();
        reassignForm.patch(route('form_submissions.reassign', formSubmission.id), {
            onSuccess: () => {
                setShowReassignModal(false);
                reassignForm.reset();
            },
        });
    }

    const locality = formSubmission.locality;
    const locationText = [locality?.name, locality?.zone?.name, locality?.province?.name]
        .filter(Boolean)
        .join(', ');

    // Chat header: muestra el solicitante (el admin habla CON él)
    const requesterInitial = formData?.name?.charAt(0)?.toUpperCase() ?? '?';
    // Card del partner
    const partnerInitial = formSubmission.user?.name?.charAt(0)?.toUpperCase() ?? 'P';

    return (
        <AuthenticatedLayout header="Formularios / Detalle">
            <div className="mx-auto max-w-4xl space-y-5">

                {/* ── Navegación superior ── */}
                <div className="flex items-center justify-between">
                    <Link
                        href={route('form_submissions.index')}
                        className="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800"
                    >
                        <ArrowLeft className="h-4 w-4" />
                        Volver al listado
                    </Link>
                    <a
                        href={route('public.form_submission.show', formSubmission.secure_token)}
                        target="_blank"
                        rel="noopener"
                        className="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs text-gray-500 shadow-sm hover:bg-gray-50"
                    >
                        <ExternalLink className="h-3.5 w-3.5" />
                        Ver versión pública
                    </a>
                </div>

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
                                {new Date(formSubmission.created_at).toLocaleDateString('es-AR')}
                            </p>
                        </div>
                        <StatusBadge status={formSubmission.status?.name} />
                    </div>
                </div>

                {/* ── Info cards ── */}
                <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    {/* Solicitante */}
                    <div className="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                        <h2 className="mb-3 text-xs font-semibold uppercase tracking-wide text-gray-400">
                            Solicitante
                        </h2>
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
                            {locationText && (
                                <li className="flex items-center gap-2">
                                    <MapPin className="h-4 w-4 flex-shrink-0 text-gray-400" />
                                    {locationText}
                                </li>
                            )}
                        </ul>
                    </div>

                    {/* Partner asignado */}
                    <div className="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                        <h2 className="mb-3 text-xs font-semibold uppercase tracking-wide text-gray-400">
                            Partner PPA RED asignado
                        </h2>
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
                        <p className="text-sm leading-relaxed text-gray-700 whitespace-pre-line">
                            {formData.message}
                        </p>
                    </div>
                )}

                {/* ── Conversación tipo chat ── */}
                <div className="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

                    {/* Chat header — muestra el solicitante */}
                    <div className="flex items-center gap-3 px-5 py-4" style={{ background: 'linear-gradient(90deg, #FD3C00, #FF7500)' }}>
                        <div className="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-white/20 text-sm font-bold text-white">
                            {requesterInitial}
                        </div>
                        <div>
                            <p className="text-sm font-semibold text-white">{formData?.name ?? 'Solicitante'}</p>
                            <p className="text-xs text-white/70">Solicitante</p>
                        </div>
                        {isClosed && (
                            <span className="ml-auto rounded-full bg-white/20 px-2.5 py-0.5 text-xs font-medium text-white">
                                Cerrada
                            </span>
                        )}
                    </div>

                    {/* Bubbles area */}
                    <div className="min-h-[200px] max-h-[460px] overflow-y-auto space-y-3 bg-[#F2F2F2] px-4 py-5">
                        {responses.length === 0 ? (
                            <div className="flex flex-col items-center justify-center py-10 text-center">
                                <MessageCircle className="mb-2 h-8 w-8 text-gray-300" />
                                <p className="text-sm text-gray-400">Sin mensajes aún.</p>
                                <p className="mt-0.5 text-xs text-gray-400">Escribí el primer mensaje para iniciar la conversación.</p>
                            </div>
                        ) : (
                            responses.map((resp) => {
                                // Sin user_id → mensaje automático del sistema
                                if (!resp.user_id) {
                                    return (
                                        <div key={resp.id} className="flex justify-center py-1">
                                            <span className="rounded-full border border-gray-200 bg-white px-3 py-1 text-xs text-gray-400 shadow-sm">
                                                {resp.message}
                                            </span>
                                        </div>
                                    );
                                }

                                // is_system = 1 → partner (derecha, degradado)
                                // is_system = 0 → usuario público (izquierda, blanco)
                                const isPartnerMsg = !!resp.is_system;
                                const time = new Date(resp.created_at).toLocaleDateString('es-AR', {
                                    day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit',
                                });

                                return (
                                    <div key={resp.id} className={`flex ${isPartnerMsg ? 'justify-end' : 'justify-start'}`}>
                                        <div
                                            className={`max-w-[78%] rounded-2xl px-4 py-3 text-sm shadow-sm ${
                                                isPartnerMsg
                                                    ? 'rounded-tr-none text-white'
                                                    : 'rounded-tl-none bg-white text-gray-800'
                                            }`}
                                            style={isPartnerMsg ? { background: 'linear-gradient(135deg, #FD3C00, #FF7500)' } : {}}
                                        >
                                            <p className="whitespace-pre-line leading-relaxed">{resp.message}</p>
                                            <p className={`mt-1.5 text-xs ${isPartnerMsg ? 'text-white/70' : 'text-gray-400'}`}>
                                                {resp.user?.name ?? (isPartnerMsg ? 'Partner' : 'Usuario')} · {time}
                                            </p>
                                        </div>
                                    </div>
                                );
                            })
                        )}
                        <div ref={messagesEndRef} />
                    </div>

                    {/* Input */}
                    <div className="border-t border-gray-100 bg-white px-4 py-3">
                        {!isClosed && isPartner ? (
                            <form onSubmit={submitResponse} className="flex items-center gap-2">
                                <input
                                    type="text"
                                    value={responseForm.data.message}
                                    onChange={(e) => responseForm.setData('message', e.target.value)}
                                    onKeyDown={(e) => {
                                        if (e.key === 'Enter') { e.preventDefault(); submitResponse(e); }
                                    }}
                                    placeholder="Escribí tu mensaje..."
                                    className="flex-1 rounded-full border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm outline-none transition focus:border-transparent focus:ring-2 focus:ring-[#FF7500]"
                                />
                                <button
                                    type="submit"
                                    disabled={responseForm.processing || !responseForm.data.message.trim()}
                                    className="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full text-white shadow transition hover:opacity-90 disabled:cursor-not-allowed disabled:opacity-50"
                                    style={{ background: 'linear-gradient(135deg, #FD3C00, #FF7500)' }}
                                    title="Enviar"
                                >
                                    <Send className="h-4 w-4" />
                                </button>
                            </form>
                        ) : isClosed ? (
                            <div className="flex items-start gap-3 rounded-xl bg-gray-50 p-4 text-sm">
                                <XCircle className="mt-0.5 h-4 w-4 flex-shrink-0 text-gray-400" />
                                <div>
                                    <p className="font-medium text-gray-700">Esta consulta está cerrada.</p>
                                    {formSubmission.closure_reason && (
                                        <p className="mt-0.5 text-gray-500">Motivo: {formSubmission.closure_reason}</p>
                                    )}
                                </div>
                            </div>
                        ) : null}
                        {responseForm.errors.message && (
                            <p className="mt-1 px-1 text-xs text-red-600">{responseForm.errors.message}</p>
                        )}
                    </div>
                </div>

                {/* ── Reasignar partner (solo admin, consulta vigente) ── */}
                {isAdmin && !isClosed && (
                    <div className="flex justify-end">
                        <button
                            onClick={() => setShowReassignModal(true)}
                            className="flex items-center gap-2 rounded-xl border border-orange-200 bg-orange-50 px-4 py-2.5 text-sm font-medium text-orange-700 hover:bg-orange-100"
                        >
                            <UserRoundCog className="h-4 w-4" />
                            Reasignar partner
                        </button>
                    </div>
                )}

                {/* ── Cerrar consulta (solo partner) ── */}
                {isPartner && !isClosed && (
                    <div className="flex justify-end">
                        <button
                            onClick={() => setShowCloseModal(true)}
                            className="flex items-center gap-2 rounded-xl border border-red-200 bg-red-50 px-4 py-2.5 text-sm font-medium text-red-700 hover:bg-red-100"
                        >
                            <XCircle className="h-4 w-4" />
                            Cerrar consulta
                        </button>
                    </div>
                )}
            </div>

            {/* ── Modal de cierre ── */}
            <Modal show={showCloseModal} onClose={() => setShowCloseModal(false)}>
                <div className="p-6">
                    <h2 className="mb-1 text-lg font-semibold text-gray-800">Cerrar consulta</h2>
                    <p className="mb-4 text-sm text-gray-500">
                        Describí el motivo del cierre para registrarlo en el historial.
                    </p>
                    <form onSubmit={submitClose} className="space-y-4">
                        <div>
                            <textarea
                                value={closeForm.data.closure_reason}
                                onChange={(e) => closeForm.setData('closure_reason', e.target.value)}
                                rows={4}
                                className="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm focus:border-[#FF7500] focus:outline-none focus:ring-1 focus:ring-[#FF7500]"
                                placeholder="Motivo del cierre (mínimo 10 caracteres)..."
                            />
                            <InputError message={closeForm.errors.closure_reason} className="mt-1" />
                        </div>
                        <div className="flex justify-end gap-3">
                            <SecondaryButton type="button" onClick={() => setShowCloseModal(false)}>
                                Cancelar
                            </SecondaryButton>
                            <DangerButton type="submit" disabled={closeForm.processing}>
                                {closeForm.processing ? 'Cerrando...' : 'Cerrar consulta'}
                            </DangerButton>
                        </div>
                    </form>
                </div>
            </Modal>
            {/* ── Modal de reasignación ── */}
            <Modal show={showReassignModal} onClose={() => setShowReassignModal(false)}>
                <div className="p-6">
                    <div className="mb-4 flex items-center gap-3">
                        <div
                            className="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full"
                            style={{ background: 'linear-gradient(135deg, #FD3C00, #FF7500)' }}
                        >
                            <UserRoundCog className="h-4 w-4 text-white" />
                        </div>
                        <div>
                            <h2 className="text-lg font-semibold text-gray-800">Reasignar partner</h2>
                            <p className="text-xs text-gray-500">
                                Partner actual: <span className="font-medium text-gray-700">{formSubmission.user?.name ?? '—'}</span>
                            </p>
                        </div>
                    </div>
                    <p className="mb-5 text-sm text-gray-500">
                        Seleccioná el nuevo partner responsable de esta consulta.
                        Se notificará por email al partner saliente, al entrante y al solicitante.
                    </p>
                    <form onSubmit={submitReassign} className="space-y-4">
                        <div>
                            <Select
                                value={reassignForm.data.partner_id}
                                onValueChange={(val) => reassignForm.setData('partner_id', val)}
                            >
                                <SelectTrigger className="w-full">
                                    <SelectValue placeholder="Seleccioná un partner…" />
                                </SelectTrigger>
                                <SelectContent>
                                    {partners
                                        .filter((p) => p.id !== formSubmission.user_id)
                                        .map((p) => (
                                            <SelectItem key={p.id} value={String(p.id)}>
                                                {p.name}
                                                <span className="ml-1 text-xs text-gray-400">({p.email})</span>
                                            </SelectItem>
                                        ))}
                                </SelectContent>
                            </Select>
                            <InputError message={reassignForm.errors.partner_id} className="mt-1" />
                        </div>
                        <div className="flex justify-end gap-3">
                            <SecondaryButton type="button" onClick={() => setShowReassignModal(false)}>
                                Cancelar
                            </SecondaryButton>
                            <button
                                type="submit"
                                disabled={reassignForm.processing || !reassignForm.data.partner_id}
                                className="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold text-white shadow transition hover:opacity-90 disabled:cursor-not-allowed disabled:opacity-50"
                                style={{ background: 'linear-gradient(135deg, #FD3C00, #FF7500)' }}
                            >
                                <UserRoundCog className="h-4 w-4" />
                                {reassignForm.processing ? 'Reasignando…' : 'Confirmar reasignación'}
                            </button>
                        </div>
                    </form>
                </div>
            </Modal>
        </AuthenticatedLayout>
    );
}
