import { router } from "@inertiajs/react";
import { LatLng } from "leaflet";
import { useState } from "react";
import type { SubmitHandler } from "react-hook-form";
import { useForm } from "react-hook-form";
import { toast } from "sonner";
import { Button } from "@/components/ui/shadcn/button";
import events from "@/routes/events";
import type { DifficultyLevel, EventActivity, EventActivityCategory, EventActivityFormData, EventActivityType, EventStatus } from "@/types";
import ActivityForm from "../ActivityForm";

type EditActivityFormProps = {
    activity: EventActivity;
    statuses: EventStatus[];
    difficultyLevels: DifficultyLevel[];
    activityTypes: EventActivityType[];
    categories: EventActivityCategory[];
};

export default function EditActivityForm({ activity, activityTypes, categories, difficultyLevels, statuses }: EditActivityFormProps) {
    const [processing, setProcessing] = useState(false);
    const defaultValues: EventActivityFormData = {
        name: activity.name,
        description: activity.description,
        requirements: activity.requirements || '',
        is_free: activity.price === 0,
        price: activity.price,
        with_capacity: activity.capacity !== null,
        capacity: activity.capacity,
        is_online: activity.is_online,
        online_link: activity.online_link ?? '',
        location: activity.location ?? '',
        latLng: new LatLng(Number(activity.lat), Number(activity.lng)),
        is_competition: activity.is_competition,
        has_teams: activity.has_teams,
        requires_team: activity.requires_team,
        min_team_size: activity.min_team_size || 0,
        max_team_size: activity.max_team_size || 0,
        only_students: activity.only_students,
        repository_url: activity.repository_url ?? '',
        started_at: new Date(activity.started_at),
        ended_at: new Date(activity.ended_at),
        registration_started_at: new Date(activity.registration_started_at),
        registration_ended_at: new Date(activity.registration_ended_at),
        event_status_id: activity.event_status_id,
        event_activity_type_id: activity.event_activity_type_id,
        difficulty_level_id: activity.difficulty_level_id,
        speakers: activity.speakers,
        categories: activity.categories.map(category => category.id) || [],
    };

    const {
        control,
        register,
        handleSubmit,
    } = useForm<EventActivityFormData>({
        defaultValues,
    });

    const handleEditActivity: SubmitHandler<EventActivityFormData> = ({
        latLng,
        started_at,
        ended_at,
        registration_ended_at,
        registration_started_at,
        ...data
    }) => {
        const formData = {
            ...data,
            price: data.is_free ? 0 : data.price,
            capacity: data.with_capacity ? data.capacity : null,
            online_link: data.is_online ? data.online_link : null,
            location: data.is_online ? null : data.location,
            lat: data.is_online ? null : latLng.lat,
            lng: data.is_online ? null : latLng.lng,
            min_team_size: data.has_teams ? data.min_team_size : null,
            max_team_size: data.has_teams ? data.max_team_size : null,
            requires_team: data.has_teams ? data.requires_team : false,
            started_at: started_at.toISOString().split('T')[0],
            ended_at: ended_at.toISOString().split('T')[0],
            registration_started_at: registration_started_at
                .toISOString()
                .split('T')[0],
            registration_ended_at: registration_ended_at
                .toISOString().split('T')[0],
        };

        setProcessing(true);
        router.post(
            events.activities.update({ event: activity.event_id, activity: activity.id }, { query: { _method: 'PUT' }, }),
            formData,
            {
                preserveScroll: true,
                showProgress: true,
                forceFormData: true,
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
        <form
            onSubmit={handleSubmit(handleEditActivity)}
            className="mx-auto grid w-full max-w-2xl grid-cols-1 gap-6"
        >
            <ActivityForm
                control={control}
                register={register}
                statuses={statuses}
                difficultyLevels={difficultyLevels}
                activityTypes={activityTypes}
                categories={categories}
            />

            <Button type="submit" disabled={processing}>
                Guardar Cambios
            </Button>
        </form>
    )
}

