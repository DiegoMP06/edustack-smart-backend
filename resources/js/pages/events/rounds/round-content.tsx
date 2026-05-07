import { Head, router } from "@inertiajs/react";
import { ChevronLeft } from "lucide-react";
import RoundContentForm from "@/components/events/rounds/RoundContentForm";
import { Button } from "@/components/ui/shadcn/button";
import AppLayout from "@/layouts/app-layout";
import events from "@/routes/events";
import type { BreadcrumbItem, CompetitionRound, Event, EventActivity } from "@/types";

type EventContentProps = {
    event: Event;
    activity: EventActivity;
    round: CompetitionRound;
    edit: boolean;
};

const breadcrumbs: (event: Event, activity: EventActivity, round: CompetitionRound) => BreadcrumbItem[] = (event, activity, round) => [
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
        title: `Rondas`,
        href: events.activities.rounds.index({
            event: event.id,
            activity: activity.id,
        }).url,
    },
    {
        title: `${round.name}`,
        href: events.activities.rounds.show({
            event: event.id,
            activity: activity.id,
            round: round.id,
        }).url,
    },
    {
        title: `Contenido`,
        href: events.content.edit(event.id).url,
    },
];

export default function RoundContent({ event, activity, edit, round }: EventContentProps) {
    return (
        <AppLayout breadcrumbs={breadcrumbs(event, activity, round)}>
            <Head title={`Contenido de la ronda ${event.name}`} />

            {edit ? (
                <div className="mb-15">
                    <Button onClick={() =>
                        router.visit(
                            events.activities.rounds.edit({ event: event.id, activity: activity.id, round: round.id })
                        )
                    }>
                        <ChevronLeft />
                        Volver
                    </Button>
                </div>
            ) : null}

            <RoundContentForm eventId={event.id} round={round} edit={edit} />
        </AppLayout>
    )
}
