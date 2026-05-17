import { CalendarDays } from 'lucide-react';
import AppLayoutTemplate from '@/layouts/app/app-sidebar-layout';
import events from '@/routes/events';
import type { AppLayoutProps, NavItem } from '@/types';

const eventsLinks: NavItem[] = [
    {
        title: 'Eventos',
        href: events.index(),
        icon: CalendarDays,
    },
];

export default ({ children, breadcrumbs, ...props }: AppLayoutProps) => (
    <AppLayoutTemplate
        breadcrumbs={breadcrumbs}
        {...props}
        sidebarLinks={eventsLinks}
        sidebarLabel="Eventos"
    >
        {children}
    </AppLayoutTemplate>
);
