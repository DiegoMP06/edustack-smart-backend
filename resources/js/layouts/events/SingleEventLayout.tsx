import { CalendarDays, UserCog } from 'lucide-react';
import type { EventData } from '@/generated/types/App/Modules/Events/DTOs';
import AppLayoutTemplate from '@/layouts/app/app-sidebar-layout';
import events from '@/routes/events';
import type { AppLayoutProps, NavItem } from '@/types';

type SingleEventLayoutProps = AppLayoutProps & {
    event: EventData;
};

const singleEventLinks = (event: EventData): NavItem[] => [
    {
        title: 'Eventos',
        href: events.index(),
        icon: CalendarDays,
    },
    {
        title: 'Colaboradores',
        href: events.collaborators.index(event.id),
        icon: UserCog,
    },
];

export default ({
    children,
    breadcrumbs,
    event,
    ...props
}: SingleEventLayoutProps) => {
    const SidebarLabel =
        event.name.length > 25 ? event.name.slice(0, 25) + '...' : event.name;

    return (
        <AppLayoutTemplate
            breadcrumbs={breadcrumbs}
            {...props}
            sidebarLinks={singleEventLinks(event)}
            sidebarLabel={SidebarLabel}
        >
            {children}
        </AppLayoutTemplate>
    );
};
