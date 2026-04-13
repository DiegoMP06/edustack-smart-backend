import { router } from '@inertiajs/react';
import React, { useState } from 'react';
import { toast } from 'sonner';
import { Button } from '@/components/ui/shadcn/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/shadcn/card';
import events from '@/routes/events';
import type { Event, EventActivity } from '@/types';

type ActivityOptionsProps = {
    isPublished: EventActivity['is_published'];
    activityId: EventActivity['id'];
    eventId: Event['id'];
    isCompetition: EventActivity['is_competition'];
};


export default function ActivityOptions({
    isPublished,
    activityId,
    eventId,
    isCompetition,
}: ActivityOptionsProps) {
    const [processing, setProcessing] = useState(false);

    const handleActivityStatus = () => {
        setProcessing(true);
        router.patch(
            events.activities.status({ event: eventId, activity: activityId }),
            {},
            {
                preserveScroll: true,
                showProgress: true,

                onError(error) {
                    Object.values(error).forEach((value) => toast.error(value));
                },
                onFinish() {
                    setProcessing(false);
                },
                onSuccess(data) {
                    toast.success(data.props.message as string);
                },
            },
        );
    };

    return (
        <div className="grid gap-2">
            <Card>
                <CardHeader>
                    <CardTitle>Editar Contenido</CardTitle>
                    <CardDescription>
                        Puedes editar el contenido de tu actividad. Solo haz click
                        en el botón de editar.
                    </CardDescription>
                </CardHeader>
                <CardFooter>
                    <Button
                        variant="outline"
                        onClick={() =>
                            router.visit(
                                events.activities.content.edit({ event: eventId, activity: activityId }, {
                                    query: { edit: true },
                                }),
                            )
                        }
                        disabled={processing}
                    >
                        Editar
                    </Button>
                </CardFooter>
            </Card>

            {isCompetition && (
                <Card>
                    <CardHeader>
                        <CardTitle>Administrar Rondas</CardTitle>
                        <CardDescription>
                            Puedes administrar las rondas de tu actividad. Solo
                            haz click en el botón de administrar.
                        </CardDescription>
                    </CardHeader>
                    <CardFooter>
                        <Button
                            variant="outline"
                            onClick={() =>
                                router.visit(
                                    events.activities.rounds.index({ event: eventId, activity: activityId }),
                                )
                            }
                            disabled={processing}
                        >
                            Administrar
                        </Button>
                    </CardFooter>
                </Card>
            )}

            <Card>
                <CardHeader>
                    <CardTitle>Estado de la actividad</CardTitle>
                    <CardDescription>
                        Tu actividad puede estar en dos estados: Oculto y
                        Publicado. Las actividades ocultas son aquellas que no se
                        muestran en la sección de actividades. Las actividades
                        publicadas son aquellas que se muestran en la sección de
                        eventos.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <p>
                        Actualmente tu actividad esta en estado:
                        <strong
                            className={
                                isPublished ? 'text-green-600' : 'text-red-600'
                            }
                        >
                            {isPublished ? ' Publicado' : ' Oculto'}
                        </strong>
                    </p>
                </CardContent>
                <CardFooter>
                    <Button
                        variant={isPublished ? 'destructive' : 'outline'}
                        onClick={handleActivityStatus}
                        disabled={processing}
                    >
                        {isPublished ? 'Ocultar actividad' : 'Publicar actividad'}
                    </Button>
                </CardFooter>
            </Card>
        </div>
    );
}
