import { Head, router } from '@inertiajs/react';
import { ChevronLeft, Plus } from 'lucide-react';
import RoundItem from '@/components/events/activities/rounds/RoundItem';
import Pagination from '@/components/ui/app/pagination';
import { Button } from '@/components/ui/shadcn/button';
import AppLayout from '@/layouts/app-layout';
import events from '@/routes/events';
import type { BreadcrumbItem, PaginationType } from '@/types';
import type { Event, EventActivity, EventRound } from '@/types/events';

type RoundsProps = {
    event: Event;
    activity: EventActivity;
    rounds: PaginationType<EventRound>;
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
        title: 'Rondas',
        href: events.activities.rounds.index({
            event: event.id,
            activity: activity.id,
        }).url,
    },
];

export default function Rounds({ event, activity, rounds }: RoundsProps) {
    return (
        <AppLayout breadcrumbs={breadcrumbs(event, activity)}>
            <Head title={`Rondas de ${activity.name}`} />

            <div className="mb-15 flex items-center gap-4">
                <Button
                    variant="outline"
                    onClick={() =>
                        router.visit(
                            events.activities.show({
                                event: event.id,
                                activity: activity.id,
                            }),
                        )
                    }
                >
                    <ChevronLeft />
                    Volver a la Actividad
                </Button>

                <Button
                    onClick={() =>
                        router.visit(
                            events.activities.rounds.create({
                                event: event.id,
                                activity: activity.id,
                            }),
                        )
                    }
                >
                    <Plus />
                    Crear Ronda
                </Button>
            </div>

            {rounds.data.length > 0 ? (
                <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                    {rounds.data.map((round) => (
                        <RoundItem
                            key={round.id}
                            round={round}
                            eventId={event.id}
                            activityId={activity.id}
                        />
                    ))}
                </div>
            ) : (
                <p className="my-20 text-center text-accent-foreground">
                    No Hay Rondas
                </p>
            )}

            <Pagination pagination={rounds} />
        </AppLayout>
    );
}
