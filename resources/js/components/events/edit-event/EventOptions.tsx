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
import eventCollaborators from '@/routes/event-collaborators';
import events from '@/routes/events';
import type { Event } from '@/types';

type EventOptionsProps = {
    isPublished: Event['is_published'];
    eventId: Event['id'];
};

export default function EventOptions({
    eventId,
    isPublished,
}: EventOptionsProps) {
    const [processing, setProcessing] = useState(false);

    const handleEventStatus = () => {
        setProcessing(true);
        router.patch(
            events.status(eventId),
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
                        Puedes editar el contenido de tu evento. Solo haz click
                        en el botón de editar.
                    </CardDescription>
                </CardHeader>
                <CardFooter>
                    <Button
                        variant="outline"
                        onClick={() =>
                            router.visit(
                                events.content.edit(eventId, {
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


            <Card>
                <CardHeader>
                    <CardTitle>Gestionar Colaboradores</CardTitle>
                    <CardDescription>
                        Puedes gestionar los colaboradores de tu evento. Solo
                        haz click en el botón de gestionar.
                    </CardDescription>
                </CardHeader>
                <CardFooter>
                    <Button
                        variant="outline"
                        onClick={() =>
                            router.visit(
                                eventCollaborators.index(eventId, {
                                    query: { edit: true },
                                }),
                            )
                        }
                        disabled={processing}
                    >
                        Gestionar
                    </Button>
                </CardFooter>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle>
                        Administrar Actividades
                    </CardTitle>
                    <CardDescription>
                        Puedes crear, editar y eliminar actividades. Las Actividades son los
                        contenidos de tu evento. Solo haz click en el botón de
                        Administrar.
                    </CardDescription>
                </CardHeader>

                <CardFooter>
                    <Button
                        variant="outline"
                        onClick={() =>
                            router.visit(
                                events.activities.index(eventId),
                            )
                        }
                        disabled={processing}
                    >
                        Administrar
                    </Button>
                </CardFooter>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle>Estado del evento</CardTitle>
                    <CardDescription>
                        Tu evento puede estar en dos estados: Oculto y
                        Publicado. Los eventos ocultos son aquellos que no se
                        muestran en la sección de eventos. Los eventos
                        publicados son aquellos que se muestran en la sección de
                        eventos.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <p>
                        Actualmente tu evento esta en estado:
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
                        onClick={handleEventStatus}
                        disabled={processing}
                    >
                        {isPublished ? 'Ocultar evento' : 'Publicar evento'}
                    </Button>
                </CardFooter>
            </Card>
        </div>
    );
}
