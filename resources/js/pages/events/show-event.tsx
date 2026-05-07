import { Head, router } from '@inertiajs/react';
import { Render } from '@puckeditor/core';
import { ChevronLeft, Clock, LogIn, Pencil } from 'lucide-react';
import ShowLocationMap from '@/components/leaflet/ShowLocationMap';
import { Avatar, AvatarFallback, AvatarGroup, AvatarGroupCount } from '@/components/ui/shadcn/avatar';
import { Button } from '@/components/ui/shadcn/button';
import { HoverCard, HoverCardContent, HoverCardTrigger } from '@/components/ui/shadcn/hover-card';
import { useInitials } from '@/hooks/use-initials';
import AppLayout from '@/layouts/app-layout';
import { puckConfig } from '@/lib/puck';
import { formatDatetimeToLocale, formatDateToLocale } from '@/lib/utils';
import events from '@/routes/events';
import type { BreadcrumbItem, Event } from '@/types';

type ShowEventProps = {
    event: Event;
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
];

export default function ShowEvent({ event }: ShowEventProps) {
    const getInitials = useInitials()
    const firstFiveCollaborators = event.collaborators.slice(0, 5);
    const remainingCollaborators =
        event.collaborators.length - firstFiveCollaborators.length;

    return (
        <AppLayout breadcrumbs={breadcrumbs(event)}>
            <Head title={event.name} />

            <div className="mb-15 flex items-center justify-between">
                <Button
                    variant="default"
                    onClick={() => router.visit(events.index())}
                >
                    <ChevronLeft />
                    Volver
                </Button>

                <Button
                    onClick={() => router.visit(events.edit({
                        event: event.id,
                    }))}
                    variant="outline"
                >
                    <Pencil />
                    Editar Evento
                </Button>
            </div>

            <div className="grid grid-cols-1 gap-10 md:grid-cols-3">
                <div className="md:col-span-2">
                    <main className="mb-10">
                        <div className="">
                            <img
                                src={event.media?.[0].urls.main}
                                alt={event.name}
                                className="mx-auto block aspect-square w-full max-w-40 rounded-full shadow-md"
                                width={event.media?.[0].dimensions.main.width}
                                height={event.media?.[0].dimensions.main.height}
                            />
                        </div>

                        <h1 className="mt-6 mb-4 text-center text-3xl leading-normal font-bold text-pretty text-foreground">
                            {event.name}
                        </h1>

                        <p className="my-6 text-justify leading-normal whitespace-pre-wrap text-muted-foreground">
                            {event.description}
                        </p>
                    </main>

                    <section className="my-10 overflow-x-scroll w-full">
                        <Render
                            config={puckConfig}
                            data={{ content: event.content }}
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

                    {event.collaborators.length > 0 && (
                        <div>
                            <h3 className="text-lg font-bold">
                                Colaboradores:
                            </h3>

                            <div className="mt-2 flex flex-wrap gap-2">
                                <AvatarGroup className="grayscale">
                                    {firstFiveCollaborators.map(
                                        (collaborator) => (
                                            <HoverCard key={collaborator.id}>
                                                <HoverCardTrigger asChild>
                                                    <Avatar className="h-8 w-8 overflow-hidden rounded-full">
                                                        <AvatarFallback className="rounded-lg bg-indigo-200 text-indigo-700 dark:bg-neutral-700 dark:text-white">
                                                            {getInitials(
                                                                collaborator?.name +
                                                                ' ' +
                                                                collaborator?.father_last_name +
                                                                ' ' +
                                                                collaborator?.mother_last_name,
                                                            )}
                                                        </AvatarFallback>
                                                    </Avatar>
                                                </HoverCardTrigger>
                                                <HoverCardContent className="w-64">
                                                    <div className="flex items-center gap-2">
                                                        <Avatar className="h-8 w-8 overflow-hidden rounded-full">
                                                            <AvatarFallback className="rounded-lg bg-indigo-200 text-indigo-700 dark:bg-neutral-700 dark:text-white">
                                                                {getInitials(
                                                                    collaborator.name +
                                                                    ' ' +
                                                                    collaborator.father_last_name +
                                                                    ' ' +
                                                                    collaborator.mother_last_name,
                                                                )}
                                                            </AvatarFallback>
                                                        </Avatar>
                                                        <div className="grid flex-1 text-left text-sm leading-tight">
                                                            <span className="truncate font-medium">
                                                                {
                                                                    collaborator.name
                                                                }{' '}
                                                                {
                                                                    collaborator.father_last_name
                                                                }{' '}
                                                                {
                                                                    collaborator.mother_last_name
                                                                }
                                                            </span>
                                                            <span className="truncate text-xs text-accent-foreground">
                                                                {
                                                                    collaborator.email
                                                                }
                                                            </span>
                                                        </div>
                                                    </div>
                                                </HoverCardContent>
                                            </HoverCard>
                                        ),
                                    )}

                                    {remainingCollaborators > 0 && (
                                        <AvatarGroupCount>
                                            +{remainingCollaborators}
                                        </AvatarGroupCount>
                                    )}
                                </AvatarGroup>
                            </div>
                        </div>
                    )}

                    <div>
                        <h3 className="text-lg font-bold">
                            Fecha:
                        </h3>

                        <p className="mt-2 flex items-center gap-2 text-sm text-muted-foreground capitalize">
                            <Clock
                                className="size-6"
                            />
                            {formatDateToLocale(event.start_date)} -{' '}
                            {formatDateToLocale(event.end_date)}
                        </p>
                    </div>

                    <div>
                        <h3 className="text-lg font-bold">
                            Fecha de Registro:
                        </h3>

                        <p className="mt-2 flex items-center gap-2 text-sm text-muted-foreground capitalize">
                            <LogIn
                                className="size-6"
                            />
                            {formatDatetimeToLocale(event.registration_started_at)} -{' '}
                            {formatDatetimeToLocale(event.registration_ended_at)}
                        </p>
                    </div>

                    {!event.is_online ? (
                        <div>
                            <h3 className="text-lg font-bold">Ubicación:</h3>

                            <div className="mt-2 w-full">
                                <ShowLocationMap
                                    lat={event.lat || 0}
                                    lng={event.lng || 0}
                                    location={event.location || ''}
                                />
                            </div>
                        </div>
                    ) : (
                        <div>
                            <h3 className="text-lg font-bold">Enlace:</h3>

                            <div className="mt-2 w-full">
                                <a
                                    href={event.online_link || '#'}
                                    className="text-blue-500 hover:underline"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >
                                    {event.online_link}
                                </a>
                            </div>
                        </div>
                    )}
                </aside>
            </div >
        </AppLayout >
    );
}
