import { Head, router } from '@inertiajs/react';
import { ChevronLeft, Pencil } from 'lucide-react';
import { useState } from 'react';
import { toast } from 'sonner';
import { Button } from '@/components/ui/shadcn/button';
import AppLayout from '@/layouts/app-layout';
import { formatDateToLocale } from '@/lib/utils';
import events from '@/routes/events';
import type { BreadcrumbItem } from '@/types';
import type { Event, EventActivity } from '@/types/events';

type ShowActivityProps = {
    event: Event;
    activity: EventActivity;
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
];

export default function ShowActivity({ event, activity }: ShowActivityProps) {
    const [processing, setProcessing] = useState(false);
    const [teamName, setTeamName] = useState('');

    const registerToActivity = () => {
        setProcessing(true);

        router.post(
            events.activities.registrations.store({
                event: event.id,
                activity: activity.id,
            }),
            {},
            {
                preserveScroll: true,
                showProgress: true,
                onSuccess: (page) => {
                    toast.success(page.props.message as string);
                },
                onError: (error) => {
                    Object.values(error).forEach((value) => {
                        toast.error(value as string);
                    });
                },
                onFinish: () => setProcessing(false),
            },
        );
    };

    const createTeam = () => {
        setProcessing(true);

        router.post(
            events.activities.teams.store({
                event: event.id,
                activity: activity.id,
            }),
            { name: teamName },
            {
                preserveScroll: true,
                showProgress: true,
                onSuccess: (page) => {
                    toast.success(page.props.message as string);
                    setTeamName('');
                },
                onError: (error) => {
                    Object.values(error).forEach((value) => {
                        toast.error(value as string);
                    });
                },
                onFinish: () => setProcessing(false),
            },
        );
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs(event, activity)}>
            <Head title={activity.name} />

            <div className="mb-15 flex items-center justify-between">
                <Button
                    variant="outline"
                    onClick={() =>
                        router.visit(events.activities.index(event.id))
                    }
                >
                    <ChevronLeft />
                    Volver a Actividades
                </Button>

                <Button
                    onClick={() =>
                        router.visit(
                            events.activities.edit({
                                event: event.id,
                                activity: activity.id,
                            }),
                        )
                    }
                >
                    <Pencil />
                    Editar Actividad
                </Button>
            </div>

            <main className="mx-auto max-w-4xl">
                {activity.image && (
                    <div className="mx-auto mb-10">
                        <img
                            src={activity.image}
                            alt={activity.name}
                            className="mx-auto block aspect-video w-full max-w-2xl rounded-lg object-cover shadow-md"
                        />
                    </div>
                )}

                <h1 className="mb-4 text-center text-3xl font-bold text-foreground">
                    {activity.name}
                </h1>

                <div className="mb-10 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <div className="rounded-lg border p-4">
                        <h3 className="text-sm font-semibold text-muted-foreground">
                            Fecha y Hora
                        </h3>
                        <p className="mt-1 text-sm">
                            {formatDateToLocale(activity.started_at)} -{' '}
                            {formatDateToLocale(activity.ended_at)}
                        </p>
                    </div>

                    <div className="rounded-lg border p-4">
                        <h3 className="text-sm font-semibold text-muted-foreground">
                            Ubicación
                        </h3>
                        <p className="mt-1 text-sm">
                            {activity.is_online ? (
                                <a
                                    href={activity.online_link || '#'}
                                    className="text-blue-500 hover:underline"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >
                                    En Línea (Unirse)
                                </a>
                            ) : (
                                activity.location
                            )}
                        </p>
                    </div>

                    <div className="rounded-lg border p-4">
                        <h3 className="text-sm font-semibold text-muted-foreground">
                            Detalles
                        </h3>
                        <p className="mt-1 text-sm capitalize">
                            Tipo: {activity.type?.name} | Dificultad:{' '}
                            {activity.difficultyLevel?.name}
                        </p>
                    </div>
                </div>

                <p className="leading-relaxed whitespace-pre-wrap text-muted-foreground">
                    {activity.summary}
                </p>

                <div className="mt-8 flex flex-wrap items-center gap-3">
                    <Button onClick={registerToActivity} disabled={processing}>
                        Inscribirme a la actividad
                    </Button>

                    {activity.has_teams && (
                        <>
                            <input
                                value={teamName}
                                onChange={(eventInput) =>
                                    setTeamName(eventInput.target.value)
                                }
                                className="h-10 rounded-md border px-3"
                                placeholder="Nombre del equipo"
                            />
                            <Button
                                variant="outline"
                                onClick={createTeam}
                                disabled={
                                    processing || teamName.trim().length < 3
                                }
                            >
                                Crear equipo
                            </Button>
                        </>
                    )}
                </div>

                {/* Content could be rendered here if Puck or Tiptap is used */}
            </main>
        </AppLayout>
    );
}
