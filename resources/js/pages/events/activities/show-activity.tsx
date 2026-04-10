import { Head, router } from '@inertiajs/react';
import { Render } from '@puckeditor/core';
import { ChevronLeft, Pencil } from 'lucide-react';
import GalleryContent from '@/components/ui/app/gallery-content';
import { Icon } from '@/components/ui/app/icon';
import { Avatar, AvatarFallback } from '@/components/ui/shadcn/avatar';
import { Badge } from '@/components/ui/shadcn/badge';
import { Button } from '@/components/ui/shadcn/button';
import { useInitials } from '@/hooks/use-initials';
import AppLayout from '@/layouts/app-layout';
import { puckConfig } from '@/lib/puck';
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
    const getInitials = useInitials();

    return (
        <AppLayout breadcrumbs={breadcrumbs(event, activity)}>
            <Head title={activity.name} />

            <div className="mb-15 flex items-center justify-between">
                <Button
                    variant="default"
                    onClick={() => router.visit(events.activities.index(event.id))}
                >
                    <ChevronLeft />
                    Volver
                </Button>

                <Button
                    onClick={() => router.visit(events.activities.edit({
                        event: event.id,
                        activity: activity.id,
                    }))}
                    variant="outline"
                >
                    <Pencil />
                    Editar Actividad
                </Button>
            </div>

            <div className="grid grid-cols-1 gap-10 md:grid-cols-3">
                <div className="md:col-span-2">
                    <main className="mb-10">
                        <h1 className="mt-6 mb-4 text-center text-3xl leading-normal font-bold text-pretty text-foreground">
                            {activity.name}
                        </h1>

                        <p className="my-6 text-justify leading-normal whitespace-pre-wrap text-muted-foreground">
                            {activity.description}
                        </p>
                    </main>

                    <GalleryContent
                        media={activity.media}
                        alt={activity.name}
                        imageKey="main"
                    />

                    <section className="my-10">
                        <Render
                            config={puckConfig}
                            data={{ content: activity.content }}
                        />
                    </section>
                </div>

                <aside className="flex flex-col items-center justify-start gap-6 md:items-stretch">
                    <div className="flex items-center gap-2">
                        <Avatar className="h-8 w-8 overflow-hidden rounded-full">
                            <AvatarFallback className="rounded-lg bg-indigo-200 text-indigo-700 dark:bg-neutral-700 dark:text-white">
                                {getInitials(
                                    event.author?.name +
                                    ' ' +
                                    event.author?.father_last_name +
                                    ' ' +
                                    event.author?.mother_last_name,
                                )}
                            </AvatarFallback>
                        </Avatar>

                        <div className="grid flex-1 text-left text-sm leading-tight">
                            <span className="truncate font-medium">
                                {event.author?.name}{' '}
                                {event.author?.father_last_name}{' '}
                                {event.author?.mother_last_name}
                            </span>
                            <span className="truncate text-xs text-accent-foreground">
                                {event.author?.email}
                            </span>
                        </div>
                    </div>

                    <div>
                        <h3 className="text-lg font-bold">
                            Tipo de Publicación:
                        </h3>

                        <p className="mt-2 flex items-center gap-2 text-sm text-muted-foreground">
                            <Icon
                                iconName={activity.type.icon || ''}
                                className="size-6"
                            />
                            {activity.type.name}
                        </p>
                    </div>

                    <div>
                        <h3 className="text-lg font-bold">Categorías:</h3>

                        <div className="mt-2 flex flex-wrap gap-2">
                            {activity.categories.map((cat) => (
                                <Badge variant="secondary" key={cat.id}>
                                    {cat.name}
                                </Badge>
                            ))}
                        </div>
                    </div>
                </aside>
            </div>

            <main className="mx-auto max-w-4xl">
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
                    {activity.description}
                </p>
            </main>
        </AppLayout>
    );
}
