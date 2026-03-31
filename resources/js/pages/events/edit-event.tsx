import { Head, router } from '@inertiajs/react';
import { ChevronLeft } from 'lucide-react';
import { Button } from '@/components/ui/shadcn/button';
import AppLayout from '@/layouts/app-layout';
import events from '@/routes/events';
import type { BreadcrumbItem, Event } from '@/types';
import EditEventForm from './EditEvent/EditEventForm';
import EventOptions from './EditEvent/EventOptions';

type EditEventProps = {
    event: Event;
};

const breadcrumbs = (event: Event): BreadcrumbItem[] => [
    {
        title: 'Eventos',
        href: events.index().url,
    },
    {
        title: event.name,
        href: events.show(event.id).url,
    },
    {
        title: `Editar`,
        href: events.edit(event.id).url,
    },
];

export default function EditEvent({ event }: EditEventProps) {
    return (
        <AppLayout breadcrumbs={breadcrumbs(event)}>
            <Head title={`Editar ${event.name}`} />

            <div className="mb-15">
                <Button onClick={() => router.visit(events.index())}>
                    <ChevronLeft />
                    Volver
                </Button>
            </div>

            <div className="grid grid-cols-1 gap-12 lg:grid-cols-2 lg:gap-10">
                <EditEventForm event={event} />

                <aside className="mx-auto flex w-full max-w-2xl flex-col gap-6">
                    <EventOptions
                        isPublished={event.is_published}
                        eventId={event.id}
                    />
                </aside>
            </div>
        </AppLayout>
    );
}
