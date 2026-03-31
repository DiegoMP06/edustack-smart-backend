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
    EventActivityFormData,
    EventCatalogItem,
} from '@/types/events';

type CreateActivityProps = {
    event: Event;
    statuses: EventCatalogItem[];
    difficultyLevels: EventCatalogItem[];
    activityTypes: EventCatalogItem[];
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
    {
        title: 'Actividades',
        href: events.activities.index(event.id).url,
    },
    {
        title: 'Crear Actividad',
        href: events.activities.create(event.id).url,
    },
];

export default function CreateActivity({
    event,
    statuses,
    difficultyLevels,
    activityTypes,
}: CreateActivityProps) {
    const [processing, setProcessing] = useState(false);

    const defaultValues: EventActivityFormData = {
        name: '',
        summary: '',
        image: [],
        event_status_id: statuses[0]?.id ?? '',
        event_activity_type_id: '',
        difficulty_level_id: '',
        started_at: new Date(),
        ended_at: new Date(),
        registration_started_at: null,
        registration_ended_at: null,
        price: 0,
        capacity: null,
        is_online: false,
        online_link: '',
        location: '',
        latLng: new LatLng(0, 0),
        is_competition: false,
        has_teams: false,
        requires_team: false,
        min_team_size: null,
        max_team_size: null,
        only_students: false,
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

    const handleCreateActivity: SubmitHandler<EventActivityFormData> = ({
        latLng,
        ...data
    }) => {
        setProcessing(true);
        router.post(
            events.activities.store(event.id),
            {
                ...data,
                image: data.image[0],
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
        <AppLayout breadcrumbs={breadcrumbs(event)}>
            <Head title="Crear Actividad" />

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
                onSubmit={handleSubmit(handleCreateActivity)}
            >
                <ActivityForm
                    control={control}
                    errors={errors}
                    register={register}
                    watch={watch}
                    statuses={statuses}
                    difficultyLevels={difficultyLevels}
                    activityTypes={activityTypes}
                />

                <Button type="submit" disabled={processing}>
                    Crear Actividad
                </Button>
            </form>
        </AppLayout>
    );
}
