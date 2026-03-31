import { Head, router } from '@inertiajs/react';
import { ChevronLeft } from 'lucide-react';
import { useState } from 'react';
import type { SubmitHandler } from 'react-hook-form';
import { useForm } from 'react-hook-form';
import { toast } from 'sonner';
import RoundForm from '@/components/events/activities/rounds/RoundForm';
import { Button } from '@/components/ui/shadcn/button';
import AppLayout from '@/layouts/app-layout';
import events from '@/routes/events';
import type { BreadcrumbItem } from '@/types';
import type { Event, EventActivity, EventRoundFormData } from '@/types/events';

type CreateRoundProps = {
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
    {
        title: 'Rondas',
        href: events.activities.rounds.index({
            event: event.id,
            activity: activity.id,
        }).url,
    },
    {
        title: 'Crear Ronda',
        href: events.activities.rounds.create({
            event: event.id,
            activity: activity.id,
        }).url,
    },
];

export default function CreateRound({ event, activity }: CreateRoundProps) {
    const [processing, setProcessing] = useState(false);

    const {
        control,
        register,
        formState: { errors },
        handleSubmit,
    } = useForm<EventRoundFormData>({
        defaultValues: {
            name: '',
            content: [],
            started_at: new Date(),
            ended_at: new Date(),
            participants_per_round: null,
            starting_from_scratch: false,
            qualified_participants: 1,
            winners_count: 1,
            is_the_final: false,
        },
    });

    const handleCreateRound: SubmitHandler<EventRoundFormData> = (data) => {
        setProcessing(true);
        router.post(
            events.activities.rounds.store({
                event: event.id,
                activity: activity.id,
            }),
            {
                ...data,
                content: (data.content ?? []) as never,
            } as never,
            {
                preserveScroll: true,
                showProgress: true,
                onSuccess: (page) => {
                    toast.success(page.props.message as string);
                    router.visit(
                        events.activities.rounds.index({
                            event: event.id,
                            activity: activity.id,
                        }),
                    );
                },
                onFinish: () => setProcessing(false),
                onError: (error) => {
                    Object.values(error).forEach((value) =>
                        toast.error(value as string),
                    );
                    setProcessing(false);
                },
            },
        );
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs(event, activity)}>
            <Head title="Crear Ronda" />

            <div className="mb-15">
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
                    Volver
                </Button>
            </div>

            <form
                className="mx-auto grid w-full max-w-2xl grid-cols-1 gap-6"
                onSubmit={handleSubmit(handleCreateRound)}
            >
                <RoundForm
                    control={control}
                    errors={errors}
                    register={register}
                />

                <Button type="submit" disabled={processing}>
                    Crear Ronda
                </Button>
            </form>
        </AppLayout>
    );
}
