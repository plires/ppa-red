import { useEffect, useRef, useState } from 'react';
import { useForm, Link, usePage } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import InputError from '@/Components/InputError';
import DangerButton from '@/Components/DangerButton';
import Modal from '@/Components/Modal';
import SecondaryButton from '@/Components/SecondaryButton';
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
} from 'lucide-react';

const STATUS_COLORS = {
    'Pendiente de Respuesta Del Partner': 'bg-yellow-100 text-yellow-800',
    'Respondido Por El Partner': 'bg-green-100 text-green-800',
    'Demorado - Sin Respuesta Del Partner (48h)': 'bg-orange-100 text-orange-800',
    'Cerrado - Sin Respuesta Del Partner': 'bg-red-100 text-red-800',
    'Cerrado - Sin Respuesta Del Usuario': 'bg-red-100 text-red-800',
    'Cerrado Por El Partner': 'bg-gray-100 text-gray-800',
};

function StatusBadge({ status }) {
    return (
        <span className={`inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ${STATUS_COLORS[status] ?? 'bg-gray-100 text-gray-600'}`}>
            {status ?? '—'}
        </span>
    );
}

export default function Show({ formSubmission, formData, responses }) {
    const { auth } = usePage().props;
    const user = auth.user;
    const isPartner = user.role === 'partner';
    const isClosed = formSubmission.status?.name?.startsWith('Cerrado');
    const messagesEndRef = useRef(null);
    const [showCloseModal, setShowCloseModal] = useState(false);

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

    const locality = formSubmission.locality;
    const locationText = [locality?.name, locality?.zone?.name, locality?.province?.name]
        .filter(Boolean)
        .join(', ');

    return (
        <AuthenticatedLayout header="Formularios / Detalle">
            <div className="mx-auto max-w-4xl space-y-5">

                {/* Back + status */}
                <div className="flex items-center justify-between">
                    <Link
                        href={route('form_submissions.index')}
                        className="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700"
                    >
                        <ArrowLeft className="h-4 w-4" /> Volver
                    </Link>
                    <StatusBadge status={formSubmission.status?.name} />
                </div>

                {/* Header card */}
                <div className="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <div className="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <h1 className="text-lg font-semibold text-gray-900">
                                Consulta de{' '}
                                <span className="text-indigo-700">{formData?.name}</span>
                            </h1>
                            <p className="mt-0.5 flex items-center gap-1 text-sm text-gray-500">
                                <Calendar className="h-4 w-4" />
                                {new Date(formSubmission.created_at).toLocaleDateString('es-AR')}
                            </p>
                        </div>
                        <a
                            href={route('public.form_submission.show', formSubmission.secure_token)}
                            target="_blank"
                            rel="noopener"
                            className="flex items-center gap-1.5 rounded-lg border border-gray-200 px-3 py-1.5 text-xs text-gray-500 hover:bg-gray-50"
                        >
                            <ExternalLink className="h-3.5 w-3.5" />
                            Ver versión pública
                        </a>
                    </div>
                </div>

                {/* Profile cards */}
                <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    {/* Usuario final */}
                    <div className="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                        <h2 className="mb-3 text-sm font-semibold text-gray-700">Información del solicitante</h2>
                        <p className="font-medium text-gray-900">{formData?.name}</p>
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

                    {/* Partner */}
                    <div className="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                        <h2 className="mb-3 text-sm font-semibold text-gray-700">Partner PPA RED asignado</h2>
                        <p className="font-medium text-gray-900">{formSubmission.user?.name ?? '—'}</p>
                        <ul className="mt-2 space-y-1.5 text-sm text-gray-600">
                            {formSubmission.user?.email && (
                                <li className="flex items-center gap-2">
                                    <Mail className="h-4 w-4 flex-shrink-0 text-gray-400" />
                                    {formSubmission.user.email}
                                </li>
                            )}
                            {formSubmission.user?.phone && (
                                <li className="flex items-center gap-2">
                                    <Phone className="h-4 w-4 flex-shrink-0 text-gray-400" />
                                    {formSubmission.user.phone}
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
                </div>

                {/* Consulta original */}
                {formData?.message && (
                    <div className="rounded-xl border border-indigo-200 bg-indigo-50 p-5">
                        <h2 className="mb-2 flex items-center gap-2 text-sm font-semibold text-indigo-700">
                            <MessageCircle className="h-4 w-4" /> Consulta original
                        </h2>
                        <p className="whitespace-pre-line text-sm text-gray-700">{formData.message}</p>
                    </div>
                )}

                {/* Conversación */}
                <div className="rounded-xl border border-gray-200 bg-white shadow-sm">
                    <div className="border-b border-gray-100 px-5 py-4">
                        <h2 className="text-sm font-semibold text-gray-700">Conversación</h2>
                    </div>

                    {/* Bubbles */}
                    <div className="space-y-4 p-5 min-h-[200px] max-h-[460px] overflow-y-auto">
                        {responses.length === 0 ? (
                            <p className="py-6 text-center text-sm text-gray-400">Aún no hay mensajes.</p>
                        ) : (
                            responses.map((resp) => (
                                <MessageBubble key={resp.id} response={resp} />
                            ))
                        )}
                        <div ref={messagesEndRef} />
                    </div>

                    {/* Input */}
                    <div className="border-t border-gray-100 px-5 py-4">
                        {!isClosed ? (
                            <form onSubmit={submitResponse} className="flex gap-2">
                                <div className="flex-1">
                                    <input
                                        type="text"
                                        value={responseForm.data.message}
                                        onChange={(e) => responseForm.setData('message', e.target.value)}
                                        onKeyDown={(e) => {
                                            if (e.key === 'Enter') {
                                                e.preventDefault();
                                                submitResponse(e);
                                            }
                                        }}
                                        placeholder="Escribí tu mensaje..."
                                        className="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-transparent focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    />
                                    <InputError message={responseForm.errors.message} className="mt-1" />
                                </div>
                                <button
                                    type="submit"
                                    disabled={responseForm.processing || !responseForm.data.message.trim()}
                                    className="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-50"
                                >
                                    <Send className="h-4 w-4" />
                                    Enviar
                                </button>
                            </form>
                        ) : (
                            <div className="text-sm text-gray-500">
                                <p className="font-medium text-gray-700">Esta consulta está cerrada.</p>
                                {formSubmission.closure_reason && (
                                    <p className="mt-1">Motivo: {formSubmission.closure_reason}</p>
                                )}
                            </div>
                        )}
                    </div>
                </div>

                {/* Cerrar consulta — solo partner */}
                {isPartner && !isClosed && (
                    <div className="flex justify-end">
                        <button
                            onClick={() => setShowCloseModal(true)}
                            className="flex items-center gap-2 rounded-lg border border-red-300 bg-red-50 px-4 py-2.5 text-sm font-medium text-red-700 hover:bg-red-100"
                        >
                            <XCircle className="h-4 w-4" />
                            Cerrar consulta
                        </button>
                    </div>
                )}
            </div>

            {/* Modal cierre */}
            <Modal show={showCloseModal} onClose={() => setShowCloseModal(false)}>
                <div className="p-6">
                    <h2 className="mb-1 text-lg font-semibold text-gray-800">Cerrar consulta</h2>
                    <p className="mb-4 text-sm text-gray-500">Describí el motivo del cierre para registrarlo en el historial.</p>
                    <form onSubmit={submitClose} className="space-y-4">
                        <div>
                            <textarea
                                value={closeForm.data.closure_reason}
                                onChange={(e) => closeForm.setData('closure_reason', e.target.value)}
                                rows={4}
                                className="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
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
        </AuthenticatedLayout>
    );
}

function MessageBubble({ response }) {
    // is_system = 1 → mensaje del partner (derecha, indigo)
    // is_system = 0 → mensaje del usuario público (izquierda, gris)
    // sin user_id    → mensaje automático del sistema (centrado)
    if (!response.user_id) {
        return (
            <div className="flex justify-center py-1">
                <span className="rounded-full bg-gray-100 px-3 py-1 text-xs text-gray-500">
                    {response.message}
                </span>
            </div>
        );
    }

    const isPartner = !!response.is_system;

    const time = new Date(response.created_at).toLocaleDateString('es-AR', {
        day: '2-digit',
        month: 'short',
        hour: '2-digit',
        minute: '2-digit',
    });

    return (
        <div className={`flex ${isPartner ? 'justify-end' : 'justify-start'}`}>
            <div
                className={`max-w-[75%] rounded-2xl px-4 py-3 text-sm shadow-sm ${
                    isPartner
                        ? 'rounded-tr-none bg-indigo-600 text-white'
                        : 'rounded-tl-none bg-gray-100 text-gray-800'
                }`}
            >
                <p className="whitespace-pre-line">{response.message}</p>
                <p className={`mt-1 text-xs ${isPartner ? 'text-indigo-200' : 'text-gray-400'}`}>
                    {response.user?.name ?? 'Usuario'} · {time}
                </p>
            </div>
        </div>
    );
}
