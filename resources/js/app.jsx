import '../css/app.css';
import './bootstrap';

import { createInertiaApp, router } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot } from 'react-dom/client';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// Inertia can throw unhandled promise rejections when a navigation response
// arrives with a missing or malformed `url` field (e.g. during concurrent
// requests right after a post-login redirect). Catch these and do a hard
// reload so the user lands on a working page instead of a broken SPA state.
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
