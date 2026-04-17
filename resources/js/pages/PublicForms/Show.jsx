import { Head, useForm, usePage } from '@inertiajs/react';
import { useState } from 'react';
import { MapPin, Phone, Mail, Calendar, Send, MessageCircle } from 'lucide-react';

function StatusBadge({ name }) {
    const colors = {
        'Pendiente de Respuesta Del Partner': 'bg-yellow-100 text-yellow-800',
        'Respondido Por El Partner': 'bg-green-100 text-green-800',
        'Demorado - Sin Respuesta Del Partner (48h)': 'bg-orange-100 text-orange-800',
        'Cerrado - Sin Respuesta Del Partner': 'bg-red-100 text-red-800',
        'Cerrado - Sin Respuesta Del Usuario': 'bg-red-100 text-red-800',
        'Cerrado Por El Partner': 'bg-gray-100 text-gray-800',
    };
    return (
        <span className={`inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ${colors[name] ?? 'bg-gray-100 text-gray-600'}`}>
            {name}
        </span>
    );
}

export default function PublicFormShow({ formSubmission, formData, isClosed, closedByPartner }) {
    const { flash } = usePage().props;
    const [showFlash, setShowFlash] = useState(true);

    const { data, setData, post, processing, errors, reset } = useForm({
        message: '',
        form_submission_id: formSubmission.id,
        user_id: formSubmission.user_id,
    });

    function submit(e) {
        e.preventDefault();
        post(route('public.form_responses.store'), {
            onSuccess: () => reset('message'),
        });
    }

    const responses = formSubmission.form_responses ?? [];

    return (
        <>
            <Head title="Detalle de consulta — PPA RED" />
            <div className="min-h-screen bg-gray-50">
                {/* Header */}
                <header className="bg-white shadow-sm">
                    <div className="mx-auto max-w-4xl px-4 py-4 sm:px-6 flex items-center">
                        <span className="text-xl font-bold text-indigo-700">PPA RED</span>
                        <span className="ml-3 text-sm text-gray-500">— Detalle de tu consulta</span>
                    </div>
                </header>

                <main className="mx-auto max-w-4xl px-4 py-8 sm:px-6 space-y-6">
                    {/* Flash */}
                    {flash?.success && showFlash && (
                        <div className="flex items-center justify-between rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-green-800 text-sm">
                            <span>{flash.success}</span>
                            <button onClick={() => setShowFlash(false)} className="ml-4 text-green-600 hover:text-green-800 font-bold">×</button>
                        </div>
                    )}
                    {flash?.error && showFlash && (
                        <div className="flex items-center justify-between rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-red-800 text-sm">
                            <span>{flash.error}</span>
                            <button onClick={() => setShowFlash(false)} className="ml-4 text-red-600 hover:text-red-800 font-bold">×</button>
                        </div>
                    )}

                    {/* Estado y fecha */}
                    <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                        <div className="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <h1 className="text-lg font-semibold text-gray-900">
                                    Consulta de <span className="text-indigo-700">{formData?.name}</span>
                                </h1>
                                <p className="text-sm text-gray-500 mt-0.5 flex items-center gap-1">
                                    <Calendar className="h-4 w-4" />
                                    {formSubmission.formatted_date ?? new Date(formSubmission.created_at).toLocaleDateString('es-AR')}
                                </p>
                            </div>
                            <StatusBadge name={formSubmission.status?.name} />
                        </div>
                    </div>

                    {/* Perfiles */}
                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {/* Usuario */}
                        <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                            <h2 className="text-sm font-semibold text-gray-700 mb-3">Tu información</h2>
                            <p className="font-medium text-gray-900">{formData?.name}</p>
                            <ul className="mt-2 space-y-1 text-sm text-gray-600">
                                <li className="flex items-center gap-2"><Mail className="h-4 w-4 text-gray-400" />{formData?.email}</li>
                                <li className="flex items-center gap-2"><Phone className="h-4 w-4 text-gray-400" />{formData?.phone}</li>
                                <li className="flex items-center gap-2">
                                    <MapPin className="h-4 w-4 text-gray-400" />
                                    {formSubmission.locality?.name}
                                    {formSubmission.zone && `, ${formSubmission.zone.name}`}
                                    {`, ${formSubmission.province?.name}`}
                                </li>
                            </ul>
                        </div>

                        {/* Partner */}
                        <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                            <h2 className="text-sm font-semibold text-gray-700 mb-3">Tu partner PPA RED</h2>
                            <p className="font-medium text-gray-900">{formSubmission.user?.name}</p>
                            <ul className="mt-2 space-y-1 text-sm text-gray-600">
                                <li className="flex items-center gap-2"><Mail className="h-4 w-4 text-gray-400" />{formSubmission.user?.email}</li>
                                {formSubmission.user?.phone && (
                                    <li className="flex items-center gap-2"><Phone className="h-4 w-4 text-gray-400" />{formSubmission.user.phone}</li>
                                )}
                                <li className="flex items-center gap-2">
                                    <MapPin className="h-4 w-4 text-gray-400" />
                                    {formSubmission.locality?.name}
                                    {formSubmission.zone && `, ${formSubmission.zone.name}`}
                                    {`, ${formSubmission.province?.name}`}
                                </li>
                            </ul>
                        </div>
                    </div>

                    {/* Consulta original */}
                    {formData?.message && (
                        <div className="bg-indigo-50 border border-indigo-200 rounded-xl p-5">
                            <h2 className="text-sm font-semibold text-indigo-700 mb-2 flex items-center gap-2">
                                <MessageCircle className="h-4 w-4" /> Consulta original
                            </h2>
                            <p className="text-sm text-gray-700 whitespace-pre-line">{formData.message}</p>
                        </div>
                    )}

                    {/* Conversación */}
                    <div className="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div className="px-5 py-4 border-b border-gray-100">
                            <h2 className="text-sm font-semibold text-gray-700">Conversación</h2>
                        </div>
                        <div className="p-5 space-y-4 min-h-[120px]">
                            {responses.length === 0 ? (
                                <p className="text-sm text-gray-400 text-center py-4">Aún no hay respuestas.</p>
                            ) : (
                                responses.map(response => {
                                    const isPartner = !!response.is_system;
                                    return (
                                        <div key={response.id} className={`flex ${isPartner ? 'justify-start' : 'justify-end'}`}>
                                            <div className={`max-w-[75%] rounded-2xl px-4 py-3 text-sm shadow-sm ${
                                                isPartner
                                                    ? 'bg-gray-100 text-gray-800 rounded-tl-none'
                                                    : 'bg-indigo-600 text-white rounded-tr-none'
                                            }`}>
                                                <p className="whitespace-pre-line">{response.message}</p>
                                                <p className={`text-xs mt-1 ${isPartner ? 'text-gray-400' : 'text-indigo-200'}`}>
                                                    {isPartner ? (response.user?.name ?? 'Partner') : formData?.name}
                                                    {' · '}
                                                    {new Date(response.created_at).toLocaleDateString('es-AR', {
                                                        day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit',
                                                    })}
                                                </p>
                                            </div>
                                        </div>
                                    );
                                })
                            )}
                        </div>

                        {/* Formulario de respuesta o mensaje de cierre */}
                        <div className="px-5 pb-5 border-t border-gray-100 pt-4">
                            {!isClosed ? (
                                <form onSubmit={submit} className="flex gap-2">
                                    <div className="flex-1">
                                        <input
                                            type="text"
                                            value={data.message}
                                            onChange={e => setData('message', e.target.value)}
                                            placeholder="Escribí tu mensaje..."
                                            className="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                        />
                                        {errors.message && (
                                            <p className="text-xs text-red-600 mt-1">{errors.message}</p>
                                        )}
                                    </div>
                                    <button
                                        type="submit"
                                        disabled={processing || !data.message.trim()}
                                        className="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                    >
                                        <Send className="h-4 w-4" />
                                        Enviar
                                    </button>
                                </form>
                            ) : (
                                <div className="text-sm text-gray-500">
                                    <p className="font-medium text-gray-700">Esta consulta fue cerrada.</p>
                                    {!closedByPartner && formSubmission.closure_reason && (
                                        <p className="mt-1">Motivo: {formSubmission.closure_reason}</p>
                                    )}
                                    <p className="mt-2">Si necesitás volver a contactarte, podés hacerlo directamente con tu partner asignado.</p>
                                </div>
                            )}
                        </div>
                    </div>
                </main>
            </div>
        </>
    );
}
