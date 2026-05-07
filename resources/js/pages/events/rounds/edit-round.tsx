import { Head, router } from '@inertiajs/react';
import { ChevronLeft } from 'lucide-react';
import EditRoundForm from '@/components/events/rounds/edit-round/EditRoundForm';
import RoundOptions from '@/components/events/rounds/edit-round/RoundOptions';
import { Button } from '@/components/ui/shadcn/button';
import AppLayout from '@/layouts/app-layout';
import events from '@/routes/events';
import type { BreadcrumbItem } from '@/types';
import type {
    Event,
    EventActivity,
    CompetitionRound
} from '@/types/events';

type EditRoundProps = {
    event: Event;
    activity: EventActivity;
    round: CompetitionRound;
};

const breadcrumbs = (
    event: Event,
    activity: EventActivity,
    round: CompetitionRound,
): BreadcrumbItem[] => [
        { title: 'Eventos', href: events.index().url },
        { title: event.name, href: events.show(event.id).url },
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
        {
            title: round.name,
            href: events.activities.rounds.show({
                event: event.id,
                activity: activity.id,
                round: round.id,
            }).url,
        },
        {
            title: 'Editar',
            href: events.activities.rounds.edit({
                event: event.id,
                activity: activity.id,
                round: round.id,
            }).url,
        },
    ];

export default function EditRound({ event, activity, round }: EditRoundProps) {
    return (
        <AppLayout breadcrumbs={breadcrumbs(event, activity, round)}>
            <Head title={`Editar ${round.name}`} />

            <div className="mb-15">
                <Button
                    onClick={() =>
                        router.visit(
                            events.activities.rounds.index({
                                event: event.id,
                                activity: activity.id,
                            }),
                        )
                    }
                >
                    <ChevronLeft />
                    Volver
                </Button>
            </div>

            <div className="grid grid-cols-1 gap-12 lg:grid-cols-2 lg:gap-10">
                <EditRoundForm
                    eventId={event.id}
                    activityId={activity.id}
                    round={round}
                />

                <aside className="mx-auto flex w-full max-w-2xl flex-col gap-6">
                    <RoundOptions
                    />
                </aside>
            </div>
        </AppLayout>
    );
}
