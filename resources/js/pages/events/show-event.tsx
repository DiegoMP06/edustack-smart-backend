import { Head, router } from '@inertiajs/react';
import { Render } from '@puckeditor/core';
import { ChevronLeft, Pencil } from 'lucide-react';
import { Button } from '@/components/ui/shadcn/button';
import AppLayout from '@/layouts/app-layout';
import EventLayout from '@/layouts/events/EventLayout';
import { puckConfig } from '@/lib/puck';
import events from '@/routes/events';
import type { BreadcrumbItem, Event } from '@/types';

type ShowEventProps = {
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
];

export default function ShowEvent({ event }: ShowEventProps) {
    return (
        <AppLayout breadcrumbs={breadcrumbs(event)}>
            <Head title={event.name} />

            <div className="mb-15 flex items-center justify-between">
                <Button
                    variant="default"
                    onClick={() => router.visit(events.index())}
                >
                    <ChevronLeft />
                    Volver
                </Button>

                <Button
                    onClick={() => router.visit(events.edit({ event: event.id }))}
                    variant="outline"
                >
                    <Pencil />
                    Editar Evento
                </Button>
            </div>

            <EventLayout event={event}>
                <main>
                    <Render
                        config={puckConfig}
                        data={{ content: event.content }}
                    />
                </main>
            </EventLayout>
        </AppLayout>
    );
}
