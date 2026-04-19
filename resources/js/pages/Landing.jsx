import { Head, useForm } from '@inertiajs/react';
import { useState, useEffect } from 'react';
import axios from 'axios';
import Hero from '@/Components/Hero';

export default function Landing() {
    const [provinces, setProvinces] = useState([]);
    const [zones, setZones] = useState([]);
    const [localities, setLocalities] = useState([]);
    const [loadingZones, setLoadingZones] = useState(false);
    const [loadingLocalities, setLoadingLocalities] = useState(false);

    const { data, setData, post, processing, errors } = useForm({
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
            <Head title="PPA RED — Presupuesto Gratis" />
            <Hero
                data={data}
                setData={setData}
                errors={errors}
                processing={processing}
                provinces={provinces}
                zones={zones}
                localities={localities}
                loadingZones={loadingZones}
                loadingLocalities={loadingLocalities}
                handleProvinceChange={handleProvinceChange}
                handleZoneChange={handleZoneChange}
                onSubmit={submit}
            />
        </>
    );
}
