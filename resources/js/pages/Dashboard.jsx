import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import { Link } from '@inertiajs/react';
import { FileText, MapPin, Map, Globe, Users, BarChart2 } from 'lucide-react';

export default function Dashboard() {
    const cards = [
        { label: 'Formularios', icon: FileText, href: route('form_submissions.index'), color: 'bg-indigo-50 text-indigo-600' },
        { label: 'Provincias', icon: Globe, href: route('provinces.index'), color: 'bg-purple-50 text-purple-600' },
        { label: 'Zonas', icon: Map, href: route('zones.index'), color: 'bg-cyan-50 text-cyan-600' },
        { label: 'Localidades', icon: MapPin, href: route('localities.index'), color: 'bg-green-50 text-green-600' },
        { label: 'Partners', icon: Users, href: route('partners.index'), color: 'bg-yellow-50 text-yellow-600' },
        { label: 'Reportes', icon: BarChart2, href: route('reports.index'), color: 'bg-rose-50 text-rose-600' },
    ];

    return (
        <AuthenticatedLayout header="Dashboard">
            <Head title="Dashboard" />

            <div className="space-y-6">
                <h1 className="text-xl font-semibold text-gray-800">Panel de administración</h1>

                <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    {cards.map((card) => {
                        const Icon = card.icon;
                        return (
                            <Link
                                key={card.label}
                                href={card.href}
                                className="flex items-center gap-4 rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:shadow-md"
                            >
                                <div className={`flex h-12 w-12 items-center justify-center rounded-lg ${card.color}`}>
                                    <Icon className="h-6 w-6" />
                                </div>
                                <span className="text-base font-medium text-gray-700">
                                    {card.label}
                                </span>
                            </Link>
                        );
                    })}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
