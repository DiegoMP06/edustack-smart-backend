import { Head, router } from '@inertiajs/react';
import { ChevronLeft } from 'lucide-react';
import ActivityOptions from '@/components/events/activities/edit-activity/ActivityOptions';
import EditActivityForm from '@/components/events/activities/edit-activity/EditActivityForm';
import EditActivityGallery from '@/components/events/activities/edit-activity/EditActivityGallery';
import { Button } from '@/components/ui/shadcn/button';
import AppLayout from '@/layouts/app-layout';
import events from '@/routes/events';
import type { BreadcrumbItem } from '@/types';
import type {
    DifficultyLevel,
    Event,
    EventActivity,
    EventActivityCategory,
    EventActivityType,
} from '@/types/events';

type EditActivityProps = {
    event: Event;
    activity: EventActivity;
    difficultyLevels: DifficultyLevel[];
    activityTypes: EventActivityType[];
    categories: EventActivityCategory[];
};

const breadcrumbs = (
    event: Event,
    activity: EventActivity,
): BreadcrumbItem[] => [
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
        {
            title: activity.name,
            href: events.activities.show({ event: event.id, activity: activity.id })
                .url,
        },
        {
            title: 'Editar',
            href: events.activities.edit({ event: event.id, activity: activity.id })
                .url,
        },
    ];

export default function EditActivity({
    event,
    activity,
    difficultyLevels,
    activityTypes,
    categories,
}: EditActivityProps) {

    return (
        <AppLayout breadcrumbs={breadcrumbs(event, activity)}>
            <Head title={`Editar ${activity.name}`} />

            <div className="mb-15">
                <Button onClick={() => router.visit(events.activities.index(event.id))} >
                    <ChevronLeft />
                    Volver
                </Button>
            </div>


            <div className="flex flex-col gap-12 lg:flex-row lg:items-start lg:gap-10">
                <EditActivityForm
                    activity={activity}
                    difficultyLevels={difficultyLevels}
                    activityTypes={activityTypes}
                    categories={categories}
                />

                <aside className="mx-auto flex w-full max-w-2xl flex-col gap-6">
                    <ActivityOptions
                        eventId={event.id}
                        activityId={activity.id}
                        isPublished={activity.is_published}
                        isCompetition={activity.is_competition}
                    />
                </aside>
            </div>

            <EditActivityGallery
                eventId={event.id}
                activityId={activity.id}
                gallery={activity.media}
            />
        </AppLayout>
    );
}
