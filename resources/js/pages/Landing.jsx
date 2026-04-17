import { Head, Link, useForm } from '@inertiajs/react';
import { useState, useEffect } from 'react';
import axios from 'axios';
import { MapPin, Phone, Mail, User, MessageSquare, ChevronRight, CheckCircle } from 'lucide-react';

function InputField({ label, id, error, children }) {
    return (
        <div>
            <label htmlFor={id} className="block text-sm font-medium text-gray-700 mb-1">
                {label}
            </label>
            {children}
            {error && <p className="mt-1 text-xs text-red-600">{error}</p>}
        </div>
    );
}

function Select({ id, value, onChange, disabled, children, placeholder }) {
    return (
        <select
            id={id}
            value={value}
            onChange={onChange}
            disabled={disabled}
            className="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed transition"
        >
            <option value="">{placeholder}</option>
            {children}
        </select>
    );
}

export default function Landing() {
    const [provinces, setProvinces] = useState([]);
    const [zones, setZones] = useState([]);
    const [localities, setLocalities] = useState([]);
    const [loadingZones, setLoadingZones] = useState(false);
    const [loadingLocalities, setLoadingLocalities] = useState(false);

    const { data, setData, post, processing, errors, wasSuccessful } = useForm({
        name: '',
        email: '',
        phone: '',
        message: '',
        province_id: '',
        zone_id: '',
        locality_id: '',
    });

    useEffect(() => {
        axios.get('/api/provinces')
            .then(r => setProvinces(r.data))
            .catch(() => {});
    }, []);

    function handleProvinceChange(e) {
        const provinceId = e.target.value;
        setData(prev => ({ ...prev, province_id: provinceId, zone_id: '', locality_id: '' }));
        setZones([]);
        setLocalities([]);

        if (!provinceId) return;

        setLoadingZones(true);
        axios.get(`/api/zones?province_id=${provinceId}`)
            .then(r => {
                setZones(r.data);
                if (r.data.length === 0) {
                    setLoadingLocalities(true);
                    return axios.get(`/api/localities?province_id=${provinceId}`);
                }
            })
            .then(r => r && setLocalities(r.data))
            .catch(() => {})
            .finally(() => { setLoadingZones(false); setLoadingLocalities(false); });
    }

    function handleZoneChange(e) {
        const zoneId = e.target.value;
        setData(prev => ({ ...prev, zone_id: zoneId, locality_id: '' }));
        setLocalities([]);

        if (!zoneId) return;

        setLoadingLocalities(true);
        axios.get(`/api/localities?zone_id=${zoneId}`)
            .then(r => setLocalities(r.data))
            .catch(() => {})
            .finally(() => setLoadingLocalities(false));
    }

    function submit(e) {
        e.preventDefault();
        post(route('public.form_submission.store'));
    }

    return (
        <>
            <Head title="PPA RED — Contacto" />
            <div className="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-blue-50">

                {/* Navbar */}
                <header className="bg-white/80 backdrop-blur-sm border-b border-gray-100 sticky top-0 z-10">
                    <div className="mx-auto max-w-5xl px-4 sm:px-6 py-4 flex items-center justify-between">
                        <div className="flex items-center gap-2">
                            <div className="h-8 w-8 rounded-lg bg-indigo-600 flex items-center justify-center">
                                <span className="text-white text-xs font-bold">P</span>
                            </div>
                            <span className="text-lg font-bold text-gray-900">PPA RED</span>
                        </div>
                        <Link
                            href={route('login')}
                            className="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors"
                        >
                            Iniciar sesión
                        </Link>
                    </div>
                </header>

                {/* Hero */}
                <section className="mx-auto max-w-5xl px-4 sm:px-6 pt-16 pb-12 text-center">
                    <span className="inline-block rounded-full bg-indigo-100 px-4 py-1 text-xs font-semibold text-indigo-700 mb-4 tracking-wide uppercase">
                        Red de partners
                    </span>
                    <h1 className="text-4xl sm:text-5xl font-extrabold text-gray-900 leading-tight">
                        Conectate con tu{' '}
                        <span className="text-indigo-600">partner local</span>
                    </h1>
                    <p className="mt-4 text-lg text-gray-500 max-w-2xl mx-auto">
                        Completá el formulario y un representante de tu zona te responderá a la brevedad.
                    </p>
                </section>

                {/* Cómo funciona */}
                <section className="mx-auto max-w-5xl px-4 sm:px-6 pb-14">
                    <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        {[
                            { icon: MapPin, title: 'Elegí tu localidad', desc: 'Seleccioná provincia, zona y localidad para encontrar tu partner.' },
                            { icon: MessageSquare, title: 'Enviá tu consulta', desc: 'Completá tus datos y escribí tu mensaje. Es rápido y simple.' },
                            { icon: CheckCircle, title: 'Recibí una respuesta', desc: 'Tu partner local te contactará a través de esta plataforma.' },
                        ].map(({ icon: Icon, title, desc }) => (
                            <div key={title} className="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col items-start gap-3">
                                <div className="h-10 w-10 rounded-xl bg-indigo-50 flex items-center justify-center">
                                    <Icon className="h-5 w-5 text-indigo-600" />
                                </div>
                                <h3 className="font-semibold text-gray-900">{title}</h3>
                                <p className="text-sm text-gray-500">{desc}</p>
                            </div>
                        ))}
                    </div>
                </section>

                {/* Formulario */}
                <section className="mx-auto max-w-2xl px-4 sm:px-6 pb-20" id="formulario">
                    <div className="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                        <h2 className="text-xl font-bold text-gray-900 mb-1">Enviá tu consulta</h2>
                        <p className="text-sm text-gray-500 mb-6">Todos los campos son obligatorios.</p>

                        <form onSubmit={submit} className="space-y-5">

                            {/* Ubicación */}
                            <div className="rounded-xl bg-indigo-50 border border-indigo-100 p-4 space-y-4">
                                <p className="text-xs font-semibold text-indigo-700 uppercase tracking-wide flex items-center gap-1.5">
                                    <MapPin className="h-3.5 w-3.5" /> Tu ubicación
                                </p>

                                <InputField label="Provincia" id="province_id" error={errors.province_id}>
                                    <Select
                                        id="province_id"
                                        value={data.province_id}
                                        onChange={handleProvinceChange}
                                        placeholder={provinces.length === 0 ? 'Cargando...' : 'Seleccioná una provincia'}
                                    >
                                        {provinces.map(p => (
                                            <option key={p.id} value={p.id}>{p.name}</option>
                                        ))}
                                    </Select>
                                </InputField>

                                {(zones.length > 0 || loadingZones) && (
                                    <InputField label="Zona" id="zone_id" error={errors.zone_id}>
                                        <Select
                                            id="zone_id"
                                            value={data.zone_id}
                                            onChange={handleZoneChange}
                                            disabled={loadingZones}
                                            placeholder={loadingZones ? 'Cargando zonas...' : 'Seleccioná una zona'}
                                        >
                                            {zones.map(z => (
                                                <option key={z.id} value={z.id}>{z.name}</option>
                                            ))}
                                        </Select>
                                    </InputField>
                                )}

                                <InputField label="Localidad" id="locality_id" error={errors.locality_id}>
                                    <Select
                                        id="locality_id"
                                        value={data.locality_id}
                                        onChange={e => setData('locality_id', e.target.value)}
                                        disabled={loadingLocalities || localities.length === 0}
                                        placeholder={
                                            !data.province_id
                                                ? 'Primero seleccioná una provincia'
                                                : loadingLocalities
                                                ? 'Cargando localidades...'
                                                : localities.length === 0
                                                ? zones.length > 0 && !data.zone_id
                                                    ? 'Primero seleccioná una zona'
                                                    : 'Sin localidades disponibles'
                                                : 'Seleccioná una localidad'
                                        }
                                    >
                                        {localities.map(l => (
                                            <option key={l.id} value={l.id}>{l.name}</option>
                                        ))}
                                    </Select>
                                </InputField>
                            </div>

                            {/* Datos personales */}
                            <div className="rounded-xl bg-gray-50 border border-gray-100 p-4 space-y-4">
                                <p className="text-xs font-semibold text-gray-500 uppercase tracking-wide flex items-center gap-1.5">
                                    <User className="h-3.5 w-3.5" /> Tus datos
                                </p>

                                <InputField label="Nombre completo" id="name" error={errors.name}>
                                    <input
                                        id="name"
                                        type="text"
                                        value={data.name}
                                        onChange={e => setData('name', e.target.value)}
                                        placeholder="Juan García"
                                        className="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                    />
                                </InputField>

                                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <InputField label="Email" id="email" error={errors.email}>
                                        <input
                                            id="email"
                                            type="email"
                                            value={data.email}
                                            onChange={e => setData('email', e.target.value)}
                                            placeholder="juan@email.com"
                                            className="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                        />
                                    </InputField>
                                    <InputField label="Teléfono" id="phone" error={errors.phone}>
                                        <input
                                            id="phone"
                                            type="tel"
                                            value={data.phone}
                                            onChange={e => setData('phone', e.target.value)}
                                            placeholder="+54 11 1234-5678"
                                            className="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                        />
                                    </InputField>
                                </div>
                            </div>

                            {/* Mensaje */}
                            <InputField label="Tu consulta" id="message" error={errors.message}>
                                <textarea
                                    id="message"
                                    rows={4}
                                    value={data.message}
                                    onChange={e => setData('message', e.target.value)}
                                    placeholder="Describí tu consulta con el mayor detalle posible..."
                                    className="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition resize-none"
                                />
                            </InputField>

                            <button
                                type="submit"
                                disabled={processing}
                                className="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white hover:bg-indigo-700 active:bg-indigo-800 disabled:opacity-50 disabled:cursor-not-allowed transition-colors shadow-sm"
                            >
                                {processing ? 'Enviando...' : (
                                    <>Enviar consulta <ChevronRight className="h-4 w-4" /></>
                                )}
                            </button>
                        </form>
                    </div>
                </section>

                {/* Footer */}
                <footer className="border-t border-gray-100 bg-white py-6 text-center text-xs text-gray-400">
                    © {new Date().getFullYear()} PPA RED. Todos los derechos reservados.
                </footer>
            </div>
        </>
    );
}
