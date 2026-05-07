import type { CompetitionRound, CompetitionRoundFormData, Event, EventActivity } from "@/types";
import RoundForm from "../RoundForm";
import { SubmitHandler, useForm } from "react-hook-form";
import { useState } from "react";
import { router } from "@inertiajs/react";
import events from "@/routes/events";
import { toast } from "sonner";
import { Button } from "@/components/ui/shadcn/button";

type EditRoundFormProps = {
    round: CompetitionRound;
    eventId: Event['id'];
    activityId: EventActivity['id'];
}

export default function EditRoundForm({ round, activityId, eventId }: EditRoundFormProps) {
    const [processing, setProcessing] = useState(false);
    const initialValues: CompetitionRoundFormData = {
        name: round.name,
        description: round.description,
        per_parts: round.participants_per_round !== null,
        participants_per_round: round.participants_per_round || 1,
        rate_by_part: round.rate_by_part,
        starting_from_scratch: round.starting_from_scratch,
        qualified_participants: round.qualified_participants,
        winners_count: round.winners_count,
        is_the_final: round.is_the_final,
        started_at: new Date(round.started_at),
        ended_at: new Date(round.ended_at),
    };

    const { control, register, handleSubmit } = useForm({
        defaultValues: initialValues,
    });

    const handleEditRound: SubmitHandler<CompetitionRoundFormData> = ({ started_at, ended_at, ...data }) => {
        const formData = {
            ...data,
            participants_per_round: data.per_parts ? data.participants_per_round : null,
            rate_by_part: data.per_parts ? data.rate_by_part : false,
            started_at: started_at.toISOString().split('T')[0],
            ended_at: ended_at.toISOString().split('T')[0],
        }

        setProcessing(true);
        router.put(
            events.activities.rounds.update({
                event: eventId,
                activity: activityId,
                round: round.id,
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
    }

    return (
        <form
            onSubmit={handleSubmit(handleEditRound)}
            className="mx-auto grid w-full max-w-2xl grid-cols-1 gap-6"
        >
            <RoundForm
                control={control}
                register={register}
            />

            <Button type="submit" disabled={processing}>
                Guardar Cambios
            </Button>
        </form>
    )
}

