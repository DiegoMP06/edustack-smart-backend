import { Head, router } from '@inertiajs/react';
import { ChevronLeft, Pencil } from 'lucide-react';
import { Button } from '@/components/ui/shadcn/button';
import AppLayout from '@/layouts/app-layout';
import { formatDateToLocale } from '@/lib/utils';
import events from '@/routes/events';
import type { BreadcrumbItem } from '@/types';
import type { Event, EventActivity, EventRound } from '@/types/events';

type ShowRoundProps = {
    event: Event;
    activity: EventActivity;
    round: EventRound;
};

const breadcrumbs = (
    event: Event,
    activity: EventActivity,
    round: EventRound,
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
    {
        title: round.name,
        href: events.activities.rounds.show({
            event: event.id,
            activity: activity.id,
            round: round.id,
        }).url,
    },
];

export default function ShowRound({ event, activity, round }: ShowRoundProps) {
    return (
        <AppLayout breadcrumbs={breadcrumbs(event, activity, round)}>
            <Head title={round.name} />

            <div className="mb-15 flex items-center justify-between">
                <Button
                    variant="outline"
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
                    Volver a Rondas
                </Button>

                <div className="flex gap-2">
                    <Button
                        variant="outline"
                        onClick={() =>
                            router.visit(
                                events.activities.rounds.edit({
                                    event: event.id,
                                    activity: activity.id,
                                    round: round.id,
                                }),
                            )
                        }
                    >
                        <Pencil />
                        Editar Ronda
                    </Button>
                </div>
            </div>

            <main className="mx-auto max-w-4xl">
                <h1 className="mb-4 text-center text-3xl font-bold text-foreground">
                    Ronda #{round.round_number}: {round.name}
                </h1>

                <div className="mb-10 grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div className="rounded-lg border p-4">
                        <h3 className="text-sm font-semibold text-muted-foreground">
                            Fecha y Hora de Inicio
                        </h3>
                        <p className="mt-1 text-sm">
                            {formatDateToLocale(round.started_at)}
                        </p>
                    </div>

                    <div className="rounded-lg border p-4">
                        <h3 className="text-sm font-semibold text-muted-foreground">
                            Fecha y Hora de Fin
                        </h3>
                        <p className="mt-1 text-sm">
                            {formatDateToLocale(round.ended_at)}
                        </p>
                    </div>
                </div>

                <div className="mb-10 grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div className="rounded-lg border p-4 text-sm">
                        Participantes por ronda:{' '}
                        {round.participants_per_round ?? 'N/A'}
                    </div>
                    <div className="rounded-lg border p-4 text-sm">
                        Clasificados: {round.qualified_participants}
                    </div>
                    <div className="rounded-lg border p-4 text-sm">
                        Ganadores: {round.winners_count}
                    </div>
                </div>

                <div className="mt-10">
                    <div className="mb-4 flex items-center justify-between">
                        <h2 className="text-2xl font-bold">Ejercicios</h2>
                        {/* Assuming there is a route for adding exercises */}
                    </div>

                    {round.exercises && round.exercises.length > 0 ? (
                        <div className="grid gap-4">
                            {round.exercises.map((exercise) => (
                                <div
                                    key={exercise.id}
                                    className="rounded-lg border p-4"
                                >
                                    <h4 className="font-semibold">
                                        {exercise.name}
                                    </h4>
                                    <p className="text-sm text-muted-foreground">
                                        {exercise.description ??
                                            'Sin descripción'}
                                    </p>
                                </div>
                            ))}
                        </div>
                    ) : (
                        <p className="rounded-lg border border-dashed py-10 text-center text-muted-foreground">
                            No hay ejercicios registrados para esta ronda.
                        </p>
                    )}
                </div>
            </main>
        </AppLayout>
    );
}
