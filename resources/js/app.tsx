import '../css/app.css';

import { createInertiaApp } from '@inertiajs/react';
import { configureEcho } from '@laravel/echo-react';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { StrictMode } from 'react';
import { createRoot } from 'react-dom/client';
import { ConfirmDialogProvider } from '@/components/ui/app/confirm-dialog-provider';
import { Toaster } from '@/components/ui/shadcn/sonner';
import { TooltipProvider } from '@/components/ui/shadcn/tooltip';
import { initializeTheme } from '@/hooks/app/use-appearance';

configureEcho({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    wssPort: import.meta.env.VITE_REVERB_PORT,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const queryClient = new QueryClient();

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.tsx`,
            import.meta.glob('./pages/**/*.tsx'),
        ),
    setup({ el, App, props }) {
        const root = createRoot(el);

        root.render(
            <StrictMode>
                <QueryClientProvider client={queryClient}>
                    <ConfirmDialogProvider>
                        <TooltipProvider delayDuration={0}>
                            <App {...props} />
                            <Toaster richColors />
                        </TooltipProvider>
                    </ConfirmDialogProvider>
                </QueryClientProvider>
            </StrictMode >,
        );
    },
    progress: {
        color: '#432dd7',
    },
});

// This will set light / dark mode on load...
initializeTheme();
