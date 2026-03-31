import { AppContent } from '@/components/ui/app/app-content';
import { AppShell } from '@/components/ui/app/app-shell';
import { AppSidebar } from '@/components/ui/app/app-sidebar';
import { AppSidebarHeader } from '@/components/ui/app/app-sidebar-header';
import type { AppLayoutProps } from '@/types';

export default function AppSidebarLayout({
    children,
    breadcrumbs = [],
    withSearch = false,
    collectionName,
}: AppLayoutProps) {
    return (
        <AppShell variant="sidebar">
            <AppSidebar />
            <AppContent variant="sidebar" className="overflow-x-hidden">
                <AppSidebarHeader breadcrumbs={breadcrumbs} withSearch={withSearch} collectionName={collectionName} />
                <div className="mx-auto w-full max-w-7xl px-4 py-6">
                    {children}
                </div>
            </AppContent>
        </AppShell>
    );
}
