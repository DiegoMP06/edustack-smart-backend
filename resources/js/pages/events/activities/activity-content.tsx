import { Head, router } from "@inertiajs/react";
import { ChevronLeft } from "lucide-react";
import ActivityContentForm from "@/components/events/activities/ActivityContentForm";
import { Button } from "@/components/ui/shadcn/button";
import AppLayout from "@/layouts/app-layout";
import events from "@/routes/events";
import type { BreadcrumbItem, Event, EventActivity } from "@/types";

type EventContentProps = {
    event: Event;
    activity: EventActivity;
    edit: boolean;
};

const breadcrumbs: (event: Event, activity: EventActivity) => BreadcrumbItem[] = (event, activity) => [
    {
        title: 'Eventos',
        href: events.index().url,
    },
    {
        title: `${event.name}`,
        href: events.show(event.id).url,
    },
    {
        title: 'Actividades',
        href: events.activities.index(event.id).url,
    },
    {
        title: `${activity.name}`,
        href: events.activities.show({ event: event.id, activity: activity.id }).url,
    },
    {
        title: `Contenido`,
        href: events.content.edit(event.id).url,
    },
];

export default function ActivityContent({ event, activity, edit }: EventContentProps) {
    return (
        <AppLayout breadcrumbs={breadcrumbs(event, activity)}>
            <Head title={`Contenido de la actividad ${event.name}`} />

            {edit ? (
                <div className="mb-15">
                    <Button onClick={() => router.visit(events.activities.edit({ event: event.id, activity: activity.id }))}>
                        <ChevronLeft />
                        Volver
                    </Button>
                </div>
            ) : null}

            <ActivityContentForm activity={activity} edit={edit} />
        </AppLayout>
    )
}
