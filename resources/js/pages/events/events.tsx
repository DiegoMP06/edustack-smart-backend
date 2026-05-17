import { Head, router } from '@inertiajs/react';
import { Plus } from 'lucide-react';
import EventItem from '@/components/events/EventItem';
import Pagination from '@/components/ui/app/pagination';
import { Button } from '@/components/ui/shadcn/button';
import type { EventData } from '@/generated/types/App/Modules/Events/DTOs';
import type { ListCollectionQueryParamsData } from '@/generated/types/App/Modules/Shared/DTOs/Query';
import EventsLayout from '@/layouts/events/EventsLayout';
import { create, index } from '@/routes/events';
import type { BreadcrumbItem, PaginationType } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Eventos',
        href: index().url,
    },
];

type EventsProps = {
    events: PaginationType<EventData>;
    filter: ListCollectionQueryParamsData['filter'];
};

export default function Events({ events, filter }: EventsProps) {
    return (
        <EventsLayout breadcrumbs={breadcrumbs} collectionName="events" withSearch>
            <Head title="Eventos" />

            <div className="mb-15">
                <Button onClick={() => router.visit(create())}>
                    <Plus />
                    Crear Evento
                </Button>
            </div>

            {events.data.length > 0 ? (
                <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    {events.data.map((event) => (
                        <EventItem key={event.id} event={event} />
                    ))}
                </div>
            ) : (
                <p className="my-20 text-center text-accent-foreground">
                    No Hay Eventos
                </p>
            )}

            <Pagination
                pagination={events}
                queryParams={{
                    ...filter,
                }}
            />
        </EventsLayout>
    );
}
