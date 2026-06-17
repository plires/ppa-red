import { useEffect, useRef, useState } from 'react';
import { Link, router, usePage } from '@inertiajs/react';
import axios from 'axios';
import {
    FileText,
    MapPin,
    Map,
    Globe,
    Users,
    BarChart2,
    PieChart,
    LogOut,
    Menu,
    X,
    Bell,
    MessageSquare,
    ChevronDown,
} from 'lucide-react';
import ApplicationLogo from '@/Components/ApplicationLogo';
import FlashMessages from '@/Components/FlashMessages';

const adminMenus = [
    {
        key: 'provinces',
        label: 'Provincias',
        icon: Globe,
        children: [
            { label: 'Listar', href: route('provinces.index'), routeName: 'provinces.index' },
            { label: 'Agregar', href: route('provinces.create'), routeName: 'provinces.create' },
            { label: 'Restaurar', href: route('provinces.trashed'), routeName: 'provinces.trashed' },
        ],
    },
    {
        key: 'zones',
        label: 'Zonas',
        icon: Map,
        children: [
            { label: 'Listar', href: route('zones.index'), routeName: 'zones.index' },
            { label: 'Agregar', href: route('zones.create'), routeName: 'zones.create' },
            { label: 'Restaurar', href: route('zones.trashed'), routeName: 'zones.trashed' },
        ],
    },
    {
        key: 'localities',
        label: 'Localidades',
        icon: MapPin,
        children: [
            { label: 'Listar', href: route('localities.index'), routeName: 'localities.index' },
            { label: 'Agregar', href: route('localities.create'), routeName: 'localities.create' },
            { label: 'Restaurar', href: route('localities.trashed'), routeName: 'localities.trashed' },
        ],
    },
    {
        key: 'partners',
        label: 'Partners',
        icon: Users,
        children: [
            { label: 'Listar', href: route('partners.index'), routeName: 'partners.index' },
            { label: 'Agregar', href: route('partners.create'), routeName: 'partners.create' },
            { label: 'Restaurar', href: route('partners.trashed'), routeName: 'partners.trashed' },
        ],
    },
    {
        key: 'reports',
        label: 'Reportes',
        icon: BarChart2,
        children: [
            { label: 'Forms por Partner', href: route('reports.index'), routeName: 'reports.index' },
            { label: 'Estado de Forms', href: route('reports.status_chart'), routeName: 'reports.status_chart' },
        ],
    },
];

function getActiveMenuKey() {
    const active = adminMenus.find((menu) =>
        menu.children.some((child) => route().current(child.routeName)),
    );
    return active?.key ?? null;
}

export default function AuthenticatedLayout({ header, children }) {
    const { auth, unreadComments, unreadNotifications, unreadCommentsList = [], unreadNotificationsList = [] } = usePage().props;
    const user = auth.user;
    const isAdmin = user.role === 'admin';

    const [sidebarOpen, setSidebarOpen] = useState(false);
    const [openMenu, setOpenMenu] = useState(() => getActiveMenuKey());

    const toggleMenu = (key) => {
        setOpenMenu((prev) => (prev === key ? null : key));
    };

    return (
        <div className="flex h-screen bg-gray-50">
            {/* Mobile overlay */}
            {sidebarOpen && (
                <div
                    className="fixed inset-0 z-20 bg-black/60 lg:hidden"
                    onClick={() => setSidebarOpen(false)}
                />
            )}

            {/* Sidebar */}
            <aside
                className={`fixed inset-y-0 left-0 z-30 flex w-64 flex-col bg-black transition-transform duration-300 ${
                    sidebarOpen ? 'translate-x-0' : '-translate-x-full'
                } lg:static lg:translate-x-0`}
            >
                {/* Brand */}
                <div className="flex h-16 flex-shrink-0 items-center gap-3 border-b border-white/10 px-4">
                    <Link
                        href={route('form_submissions.index')}
                        className="flex items-center gap-3 text-white"
                    >
                        <ApplicationLogo className="h-8 w-auto text-white" />
                        <span
                            className="text-base font-bold tracking-wide"
                            style={{ background: 'linear-gradient(90deg, #FD3C00, #FF7500)', WebkitBackgroundClip: 'text', WebkitTextFillColor: 'transparent' }}
                        >
                            PPA RED
                        </span>
                    </Link>
                    <button
                        className="ms-auto text-white/40 hover:text-white lg:hidden"
                        onClick={() => setSidebarOpen(false)}
                    >
                        <X className="h-5 w-5" />
                    </button>
                </div>

                {/* User panel */}
                <div className="flex-shrink-0 border-b border-white/10 px-4 py-3">
                    <div className="flex items-center gap-3">
                        <div
                            className="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full text-sm font-semibold text-white"
                            style={{ background: 'linear-gradient(135deg, #FD3C00, #FF7500)' }}
                        >
                            {user.name.charAt(0).toUpperCase()}
                        </div>
                        <div className="min-w-0">
                            <p className="truncate text-sm font-medium text-gray-200">{user.name}</p>
                            <p className="text-xs text-gray-500 capitalize">{user.role}</p>
                        </div>
                    </div>
                </div>

                {/* Navigation */}
                <nav className="flex-1 overflow-y-auto px-3 py-4 space-y-0.5">
                    <Link
                        href={route('form_submissions.index')}
                        className={`flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition-colors ${
                            route().current('form_submissions.*')
                                ? 'text-white'
                                : 'text-gray-400 hover:bg-white/5 hover:text-white'
                        }`}
                        style={route().current('form_submissions.*')
                            ? { background: 'linear-gradient(90deg, #FD3C00, #FF7500)' }
                            : {}}
                    >
                        <FileText className="h-4 w-4 flex-shrink-0" />
                        Formularios
                    </Link>

                    {!isAdmin && (
                        <Link
                            href={route('reports.status_chart')}
                            className={`flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition-colors ${
                                route().current('reports.status_chart')
                                    ? 'text-white'
                                    : 'text-gray-400 hover:bg-white/5 hover:text-white'
                            }`}
                            style={route().current('reports.status_chart')
                                ? { background: 'linear-gradient(90deg, #FD3C00, #FF7500)' }
                                : {}}
                        >
                            <PieChart className="h-4 w-4 flex-shrink-0" />
                            Estado de Forms
                        </Link>
                    )}

                    {isAdmin && (
                        <>
                            <div className="my-2 border-t border-white/10" />
                            {adminMenus.map((item) => (
                                <CollapsibleMenu
                                    key={item.key}
                                    item={item}
                                    expanded={openMenu === item.key}
                                    onToggle={() => toggleMenu(item.key)}
                                />
                            ))}
                        </>
                    )}
                </nav>

                {/* Logout */}
                <div className="flex-shrink-0 border-t border-white/10 px-3 py-3">
                    <Link
                        href={route('logout')}
                        method="post"
                        as="button"
                        className="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm text-gray-500 transition-colors hover:bg-white/5 hover:text-white"
                    >
                        <LogOut className="h-4 w-4 flex-shrink-0" />
                        Cerrar Sesión
                    </Link>
                </div>
            </aside>

            {/* Main area */}
            <div className="flex min-w-0 flex-1 flex-col">
                {/* Top navbar */}
                <header className="flex h-16 flex-shrink-0 items-center justify-between border-b border-gray-200 bg-white px-4">
                    <div className="flex items-center gap-3">
                        <button
                            className="rounded-md p-1.5 text-gray-500 hover:bg-gray-100 hover:text-gray-700 lg:hidden"
                            onClick={() => setSidebarOpen(true)}
                        >
                            <Menu className="h-6 w-6" />
                        </button>
                        {header && (
                            <div className="text-sm font-semibold text-gray-700">{header}</div>
                        )}
                    </div>

                    <div className="flex items-center gap-2">
                        {user.role === 'partner' && (
                            <>
                                <NotificationBell
                                    icon={MessageSquare}
                                    count={unreadComments ?? 0}
                                    title="Comentarios sin leer"
                                    items={unreadCommentsList.map((c) => ({
                                        id: c.id,
                                        href: route('form_submissions.show', c.form_submission_id),
                                        markReadHref: route('responses.mark_as_read', c.id),
                                        label: c.message,
                                        time: formatRelativeTime(c.created_at),
                                    }))}
                                    markAllHref={route('responses.mark_as_all_read', user.id)}
                                />
                                <NotificationBell
                                    icon={Bell}
                                    count={unreadNotifications ?? 0}
                                    title="Notificaciones sin leer"
                                    items={unreadNotificationsList.map((n) => ({
                                        id: n.id,
                                        href: route('form_submissions.show', n.form_submission_id),
                                        markReadHref: route('notification.mark_as_read', {
                                            notification: n.id,
                                            formSubmission: n.form_submission_id,
                                        }),
                                        label: n.notification_details || `Estado actualizado: ${n.new_status?.name ?? ''}`,
                                        time: formatRelativeTime(n.created_at),
                                    }))}
                                    markAllHref={route('notification.mark_as_all_read')}
                                />
                            </>
                        )}

                        <Link
                            href={route('profile.edit')}
                            className="flex items-center gap-2 rounded-md px-2 py-1.5 text-sm text-gray-700 hover:bg-gray-100"
                        >
                            <div
                                className="flex h-7 w-7 items-center justify-center rounded-full text-xs font-semibold text-white"
                                style={{ background: 'linear-gradient(135deg, #FD3C00, #FF7500)' }}
                            >
                                {user.name.charAt(0).toUpperCase()}
                            </div>
                            <span className="hidden sm:block">{user.name}</span>
                            <ChevronDown className="h-3 w-3 text-gray-400" />
                        </Link>
                    </div>
                </header>

                <FlashMessages />

                <main className="flex-1 overflow-auto p-6">{children}</main>
            </div>
        </div>
    );
}

function CollapsibleMenu({ item, expanded, onToggle }) {
    const Icon = item.icon;
    const isActive = item.children.some((child) => route().current(child.routeName));

    return (
        <div>
            <button
                onClick={onToggle}
                className={`flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm transition-colors ${
                    isActive
                        ? 'bg-white/10 text-white'
                        : 'text-gray-400 hover:bg-white/5 hover:text-white'
                }`}
            >
                <Icon className="h-4 w-4 flex-shrink-0" />
                <span className="flex-1 text-left">{item.label}</span>
                <ChevronDown
                    className={`h-4 w-4 transition-transform ${expanded ? 'rotate-180' : ''}`}
                />
            </button>

            {expanded && (
                <div className="mt-0.5 ml-4 space-y-0.5 border-l border-white/10 pl-3">
                    {item.children.map((child) => (
                        <Link
                            key={child.routeName}
                            href={child.href}
                            className={`block rounded-md px-3 py-1.5 text-sm transition-colors ${
                                route().current(child.routeName)
                                    ? 'text-white'
                                    : 'text-gray-400 hover:bg-white/5 hover:text-white'
                            }`}
                            style={route().current(child.routeName)
                                ? { background: 'linear-gradient(90deg, #FD3C00, #FF7500)' }
                                : {}}
                        >
                            {child.label}
                        </Link>
                    ))}
                </div>
            )}
        </div>
    );
}

function formatRelativeTime(dateStr) {
    const diff = Date.now() - new Date(dateStr).getTime();
    const mins = Math.floor(diff / 60000);
    if (mins < 1) return 'ahora';
    if (mins < 60) return `hace ${mins}m`;
    const hours = Math.floor(mins / 60);
    if (hours < 24) return `hace ${hours}h`;
    return `hace ${Math.floor(hours / 24)}d`;
}

function NotificationBell({ icon: Icon, count, title, items = [], markAllHref }) {
    const [open, setOpen] = useState(false);
    const ref = useRef(null);

    useEffect(() => {
        if (!open) return;
        function handler(e) {
            if (!ref.current?.contains(e.target)) setOpen(false);
        }
        document.addEventListener('mousedown', handler);
        return () => document.removeEventListener('mousedown', handler);
    }, [open]);

    return (
        <div ref={ref} className="relative">
            <button
                onClick={() => setOpen((v) => !v)}
                title={title}
                className={`relative rounded-full p-2 transition-colors ${open ? 'bg-gray-100 text-gray-700' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700'}`}
            >
                <Icon className="h-5 w-5" />
                {count > 0 && (
                    <span
                        className="absolute -right-0.5 -top-0.5 flex h-4 w-4 items-center justify-center rounded-full text-xs font-medium text-white"
                        style={{ background: 'linear-gradient(135deg, #FD3C00, #FF7500)' }}
                    >
                        {count}
                    </span>
                )}
            </button>

            {open && (
                <div className="absolute right-0 top-full z-50 mt-1 w-80 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-lg">
                    <div className="border-b border-gray-100 px-4 py-2.5">
                        <p className="text-sm font-semibold text-gray-800">{title}</p>
                    </div>

                    {items.length === 0 ? (
                        <p className="px-4 py-8 text-center text-sm text-gray-400">Todo al día</p>
                    ) : (
                        <ul className="max-h-72 divide-y divide-gray-50 overflow-y-auto">
                            {items.map((item) => (
                                <li key={item.id}>
                                    <button
                                        onClick={() => {
                                            setOpen(false);
                                            axios.get(item.markReadHref).catch(() => {});
                                            router.visit(item.href);
                                        }}
                                        className="block w-full px-4 py-3 text-left hover:bg-gray-50"
                                    >
                                        <p className="line-clamp-2 text-sm text-gray-700">{item.label}</p>
                                        <p className="mt-0.5 text-xs text-gray-400">{item.time}</p>
                                    </button>
                                </li>
                            ))}
                        </ul>
                    )}

                    <div className="border-t border-gray-100 px-4 py-2.5">
                        <Link
                            href={markAllHref}
                            method="post"
                            as="button"
                            onClick={() => setOpen(false)}
                            className="text-xs font-medium text-gray-500 hover:text-[#FF7500] transition-colors"
                        >
                            Marcar todo como leído
                        </Link>
                    </div>
                </div>
            )}
        </div>
    );
}
