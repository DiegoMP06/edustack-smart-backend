import { Head, router } from '@inertiajs/react';
import { LatLng } from 'leaflet';
import { ChevronLeft } from 'lucide-react';
import { useState } from 'react';
import type { SubmitHandler } from 'react-hook-form';
import { useForm } from 'react-hook-form';
import { toast } from 'sonner';
import ActivityForm from '@/components/events/activities/ActivityForm';
import { Button } from '@/components/ui/shadcn/button';
import AppLayout from '@/layouts/app-layout';
import events from '@/routes/events';
import type { BreadcrumbItem } from '@/types';
import type {
    Event,
    EventActivity,
    EventActivityFormData,
    EventCatalogItem,
} from '@/types/events';

type EditActivityProps = {
    event: Event;
    activity: EventActivity;
    statuses: EventCatalogItem[];
    difficultyLevels: EventCatalogItem[];
    activityTypes: EventCatalogItem[];
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
        title: 'Editar',
        href: events.activities.edit({ event: event.id, activity: activity.id })
            .url,
    },
];

export default function EditActivity({
    event,
    activity,
    statuses,
    difficultyLevels,
    activityTypes,
}: EditActivityProps) {
    const [processing, setProcessing] = useState(false);

    const defaultValues: EventActivityFormData = {
        name: activity.name,
        summary: activity.summary,
        image: [],
        event_status_id: activity.event_status_id,
        event_activity_type_id: activity.event_activity_type_id,
        difficulty_level_id: activity.difficulty_level_id,
        started_at: new Date(activity.started_at),
        ended_at: new Date(activity.ended_at),
        registration_started_at: activity.registration_started_at
            ? new Date(activity.registration_started_at)
            : null,
        registration_ended_at: activity.registration_ended_at
            ? new Date(activity.registration_ended_at)
            : null,
        price: activity.price,
        capacity: activity.max_participants,
        is_online: activity.is_online,
        online_link: activity.online_link ?? '',
        location: activity.location ?? '',
        latLng: new LatLng(
            Number(activity.lat ?? 0),
            Number(activity.lng ?? 0),
        ),
        is_competition: activity.is_competition,
        has_teams: activity.has_teams,
        requires_team: activity.requires_team,
        min_team_size: activity.min_team_size,
        max_team_size: activity.max_team_size,
        only_students: activity.only_students,
        course_id: null,
        project_id: null,
        repository_url: '',
        categories: [],
    };

    const {
        control,
        register,
        formState: { errors },
        handleSubmit,
        watch,
    } = useForm<EventActivityFormData>({
        defaultValues,
    });

    const handleEditActivity: SubmitHandler<EventActivityFormData> = ({
        latLng,
        ...data
    }) => {
        setProcessing(true);
        router.post(
            events.activities.update(
                { event: event.id, activity: activity.id },
                {
                    query: { _method: 'PUT' },
                },
            ),
            {
                ...data,
                image: data.image.length > 0 ? data.image[0] : null,
                lat: data.is_online ? null : latLng.lat,
                lng: data.is_online ? null : latLng.lng,
            },
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
                    setProcessing(false);
                },
            },
        );
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs(event, activity)}>
            <Head title={`Editar ${activity.name}`} />

            <div className="mb-15">
                <Button
                    variant="outline"
                    onClick={() =>
                        router.visit(events.activities.index(event.id))
                    }
                >
                    <ChevronLeft />
                    Volver
                </Button>
            </div>

            <form
                className="mx-auto grid w-full max-w-2xl grid-cols-1 gap-6"
                onSubmit={handleSubmit(handleEditActivity)}
            >
                <ActivityForm
                    control={control}
                    errors={errors}
                    register={register}
                    watch={watch}
                    statuses={statuses}
                    difficultyLevels={difficultyLevels}
                    activityTypes={activityTypes}
                    defaultImage={activity.image}
                />

                <Button type="submit" disabled={processing}>
                    Guardar Cambios
                </Button>
            </form>
        </AppLayout>
    );
}
