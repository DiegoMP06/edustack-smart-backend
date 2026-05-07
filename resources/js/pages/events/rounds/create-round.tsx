import { Head, router } from '@inertiajs/react';
import { ChevronLeft } from 'lucide-react';
import { useState } from 'react';
import type { SubmitHandler } from 'react-hook-form';
import { useForm } from 'react-hook-form';
import { toast } from 'sonner';
import RoundForm from '@/components/events/rounds/RoundForm';
import { Button } from '@/components/ui/shadcn/button';
import AppLayout from '@/layouts/app-layout';
import events from '@/routes/events';
import type { BreadcrumbItem } from '@/types';
import type { Event, EventActivity, CompetitionRoundFormData } from '@/types/events';

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

    const initialValues: CompetitionRoundFormData = {
        name: '',
        description: '',
        winners_count: 1,
        qualified_participants: 3,
        per_parts: false,
        participants_per_round: 1,
        starting_from_scratch: false,
        rate_by_part: false,
        is_the_final: false,
        started_at: new Date(),
        ended_at: new Date(),
    }

    const {
        control,
        register,
        handleSubmit,
    } = useForm({
        defaultValues: initialValues,
    });

    const handleCreateRound: SubmitHandler<CompetitionRoundFormData> = ({ started_at, ended_at, ...data }) => {
        const formData = {
            ...data,
            participants_per_round: data.per_parts ? data.participants_per_round : null,
            rate_by_part: data.per_parts ? data.rate_by_part : false,
            started_at: started_at.toISOString().split('T')[0],
            ended_at: ended_at.toISOString().split('T')[0],
        }

        setProcessing(true);
        router.post(
            events.activities.rounds.store({
                event: event.id,
                activity: activity.id,
            }), formData,
            {
                preserveScroll: true,
                showProgress: true,
                onSuccess: (page) => {
                    toast.success(page.props.message as string);
                },
                onFinish: () => setProcessing(false),
                onError: (error) => {
                    Object.values(error).forEach((value) =>
                        toast.error(value as string),
                    );
                },
            },
        );
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs(event, activity)}>
            <Head title="Crear Ronda" />

            <div className="mb-15">
                <Button
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
                    register={register}
                />

                <Button type="submit" disabled={processing}>
                    Crear Ronda
                </Button>
            </form>
        </AppLayout>
    );
}
