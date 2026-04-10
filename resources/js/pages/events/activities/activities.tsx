import { Head, router } from '@inertiajs/react';
import { Plus } from 'lucide-react';
import ActivityItem from '@/components/events/activities/ActivityItem';
import Pagination from '@/components/ui/app/pagination';
import { Button } from '@/components/ui/shadcn/button';
import AppLayout from '@/layouts/app-layout';
import events from '@/routes/events';
import type { BreadcrumbItem, PaginationType } from '@/types';
import type { Event, EventActivity } from '@/types/events';

type ActivitiesProps = {
    event: Event;
    activities: PaginationType<EventActivity>;
    filter: { [key: string]: string };
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
        title: 'Actividades',
        href: events.activities.index(event.id).url,
    },
];

export default function Activities({
    event,
    activities,
    filter,
}: ActivitiesProps) {
    return (
        <AppLayout
            breadcrumbs={breadcrumbs(event)}
            collectionName="activities"
            withSearch
        >
            <Head title={`Actividades de ${event.name}`} />

            <div className="mb-15">
                <Button
                    onClick={() =>
                        router.visit(events.activities.create(event.id))
                    }
                >
                    <Plus />
                    Crear Actividad
                </Button>
            </div>

            {activities.data.length > 0 ? (
                <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    {activities.data.map((activity) => (
                        <ActivityItem
                            key={activity.id}
                            activity={activity}
                            eventId={event.id}
                        />
                    ))}
                </div>
            ) : (
                <p className="my-20 text-center text-accent-foreground">
                    No Hay Actividades
                </p>
            )}

            <Pagination
                pagination={activities}
                queryParams={{
                    ...filter,
                }}
            />
        </AppLayout>
    );
}
