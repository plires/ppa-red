import '../css/app.css';
import './bootstrap';

import { createInertiaApp, router } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot } from 'react-dom/client';

const appName = __APP_NAME__;

// Recovery net for the Inertia navigation TypeError: reload so the user lands
// on a working page instead of a broken SPA state.
//
// This only recovers, it does not diagnose. The response that caused the crash
// is captured by the axios interceptor in bootstrap.js and reported to the
// server, so each of these reloads leaves a trace in the Laravel log instead of
// disappearing silently.
window.addEventListener('unhandledrejection', (event) => {
    const err = event.reason;
    if (
        err instanceof TypeError &&
        (err.message?.includes('toString') ||
            err.message?.includes('Cannot read properties of undefined'))
    ) {
        event.preventDefault();
        window.location.reload();
    }
});

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.jsx`,
            import.meta.glob('./pages/**/*.jsx'),
        ),
    setup({ el, App, props }) {
        const root = createRoot(el);

        root.render(<App {...props} />);

        // Inertia-level exception handler as a second safety net
        router.on('exception', (e) => {
            if (e.detail?.exception instanceof TypeError) {
                e.preventDefault();
                window.location.reload();
            }
        });
    },
    progress: {
        color: '#4B5563',
    },
});
