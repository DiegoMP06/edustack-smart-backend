import { Head, router } from '@inertiajs/react';
import { LatLng } from 'leaflet';
import { ChevronLeft } from 'lucide-react';
import { useState } from 'react';
import type { SubmitHandler } from 'react-hook-form';
import { Controller, useForm } from 'react-hook-form';
import { toast } from 'sonner';
import DropzoneInput from '@/components/dropzone/DropzoneInput';
import ActivityForm from '@/components/events/activities/ActivityForm';
import InputError from '@/components/ui/app/input-error';
import { Button } from '@/components/ui/shadcn/button';
import { Label } from '@/components/ui/shadcn/label';
import useMediaUpload from '@/hooks/media/useMediaUpload';
import AppLayout from '@/layouts/app-layout';
import events from '@/routes/events';
import type { BreadcrumbItem } from '@/types';
import type {
    DifficultyLevel,
    Event,
    EventActivityCategory,
    EventActivityFormData,
    EventActivityType,
    EventStatus,
} from '@/types/events';

type CreateActivityProps = {
    event: Event;
    statuses: EventStatus[];
    difficultyLevels: DifficultyLevel[];
    activityTypes: EventActivityType[];
    categories: EventActivityCategory[];
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
    categories,
}: CreateActivityProps) {
    const [processing, setProcessing] = useState(false);

    const defaultValues: EventActivityFormData = {
        name: '',
        description: '',
        requirements: '',
        images: [],
        is_online: false,
        online_link: '',
        location: '',
        latLng: new LatLng(0, 0),
        has_teams: false,
        requires_team: false,
        min_team_size: 0,
        max_team_size: 0,
        with_capacity: false,
        capacity: 0,
        only_students: false,
        is_competition: false,
        is_free: false,
        price: 0,
        speakers: [],
        repository_url: '',
        started_at: new Date(),
        ended_at: new Date(),
        registration_started_at: new Date(),
        registration_ended_at: new Date(),
        event_status_id: 1,
        event_activity_type_id: 1,
        difficulty_level_id: 1,
        categories: [],
    };

    const { uploadImages } = useMediaUpload();

    const {
        control,
        register,
        formState: { errors },
        handleSubmit,
    } = useForm<EventActivityFormData>({
        defaultValues,
    });

    const handleCreateActivity: SubmitHandler<EventActivityFormData> = async ({
        latLng,
        started_at,
        ended_at,
        registration_ended_at,
        registration_started_at,
        ...data
    }) => {
        setProcessing(true);

        const keys = await uploadImages(data.images || []);

        const formData = {
            ...data,
            images: keys,
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

        router.post(
            events.activities.store(event.id), formData, {
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
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs(event)}>
            <Head title="Crear Actividad" />

            <div className="mb-15">
                <Button
                    onClick={() => router.visit(events.activities.index(event.id))}
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
                    register={register}
                    statuses={statuses}
                    difficultyLevels={difficultyLevels}
                    activityTypes={activityTypes}
                    categories={categories}
                />

                <div className="grid gap-2">
                    <Label htmlFor="images">Imágenes: </Label>

                    <Controller
                        name="images"
                        control={control}
                        rules={{
                            validate: (value) =>
                                value!.length > 0 ||
                                'Debe seleccionar al menos una imagen',
                        }}
                        render={({ field: { value, onChange } }) => (
                            <DropzoneInput
                                value={value || []}
                                onChange={onChange}
                                multipleFiles
                            />
                        )}
                    />

                    <InputError message={errors.images?.message} />
                </div>

                <Button type="submit" disabled={processing}>
                    Crear Actividad
                </Button>
            </form>
        </AppLayout>
    );
}
