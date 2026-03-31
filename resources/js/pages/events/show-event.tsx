import { Head, router } from '@inertiajs/react';
import { Render } from '@puckeditor/core';
import { ChevronLeft } from 'lucide-react';
import { useState } from 'react';
import { toast } from 'sonner';
import { Button } from '@/components/ui/shadcn/button';
import AppLayout from '@/layouts/app-layout';
import EventLayout from '@/layouts/events/EventLayout';
import { puckConfig } from '@/lib/puck';
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
    const [processing, setProcessing] = useState(false);

    const registerToEvent = () => {
        setProcessing(true);

        router.post(
            events.registrations.store(event.id),
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

    return (
        <AppLayout breadcrumbs={breadcrumbs(event)}>
            <Head title={event.name} />

            <div className="mb-15">
                <Button onClick={() => router.visit(events.index())}>
                    <ChevronLeft />
                    Volver
                </Button>
            </div>

            <EventLayout event={event}>
                <div className="mb-6">
                    <Button onClick={registerToEvent} disabled={processing}>
                        Inscribirme al evento
                    </Button>
                </div>

                <main className="mx-auto max-w-4xl">
                    <div className="mt-10">
                        <Render
                            config={puckConfig}
                            data={{ content: event.content }}
                        />
                    </div>
                </main>
            </EventLayout>
        </AppLayout>
    );
}
