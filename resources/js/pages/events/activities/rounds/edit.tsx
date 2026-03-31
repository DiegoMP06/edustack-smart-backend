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
import type {
    Event,
    EventActivity,
    EventRound,
    EventRoundFormData,
} from '@/types/events';

type EditRoundProps = {
    event: Event;
    activity: EventActivity;
    round: EventRound;
};

const breadcrumbs = (
    event: Event,
    activity: EventActivity,
    round: EventRound,
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
    const [processing, setProcessing] = useState(false);

    const {
        control,
        register,
        formState: { errors },
        handleSubmit,
    } = useForm<EventRoundFormData>({
        defaultValues: {
            name: round.name,
            content: round.content ?? [],
            started_at: new Date(round.started_at),
            ended_at: new Date(round.ended_at),
            participants_per_round: round.participants_per_round,
            starting_from_scratch: round.starting_from_scratch,
            qualified_participants: round.qualified_participants,
            winners_count: round.winners_count,
            is_the_final: round.is_the_final,
        },
    });

    const handleEditRound: SubmitHandler<EventRoundFormData> = (data) => {
        setProcessing(true);

        router.put(
            events.activities.rounds.update({
                event: event.id,
                activity: activity.id,
                round: round.id,
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
                    Object.values(error).forEach((value) => {
                        toast.error(value as string);
                    });
                    setProcessing(false);
                },
            },
        );
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs(event, activity, round)}>
            <Head title={`Editar ${round.name}`} />

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
                onSubmit={handleSubmit(handleEditRound)}
            >
                <RoundForm
                    control={control}
                    errors={errors}
                    register={register}
                />

                <Button type="submit" disabled={processing}>
                    Guardar Cambios
                </Button>
            </form>
        </AppLayout>
    );
}
