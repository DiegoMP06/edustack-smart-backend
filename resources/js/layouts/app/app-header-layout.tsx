import { AppContent } from '@/components/ui/app/app-content';
import { AppHeader } from '@/components/ui/app/app-header';
import { AppShell } from '@/components/ui/app/app-shell';
import type { AppLayoutProps } from '@/types';

export default function AppHeaderLayout({
    children,
    breadcrumbs,
}: AppLayoutProps) {
    return (
        <AppShell variant="header">
            <AppHeader breadcrumbs={breadcrumbs} />
            <AppContent variant="header">{children}</AppContent>
        </AppShell>
    );
}
