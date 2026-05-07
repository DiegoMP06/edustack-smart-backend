import { Head, router } from '@inertiajs/react';
import { LatLng } from 'leaflet';
import { ChevronLeft } from 'lucide-react';
import { useState } from 'react';
import type { SubmitHandler } from 'react-hook-form';
import { useForm } from 'react-hook-form';
import { toast } from 'sonner';
import EventForm from '@/components/events/EventForm';
import { Button } from '@/components/ui/shadcn/button';
import useMediaUpload from '@/hooks/media/useMediaUpload';
import AppLayout from '@/layouts/app-layout';
import events from '@/routes/events';
import type { BreadcrumbItem } from '@/types';
import type { EventFormData } from '@/types/events';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Eventos',
        href: events.index().url,
    },
    {
        title: 'Crear Evento',
        href: events.create().url,
    },
];

export default function CreateEvent() {
    const [processing, setProcessing] = useState(false);

    const initialValues: EventFormData = {
        name: '',
        logo: [],
        description: '',
        is_free: true,
        price: 0,
        percent_off: 0,
        with_capacity: false,
        capacity: 0,
        is_online: false,
        online_link: '',
        latLng: new LatLng(0, 0),
        location: '',
        registration_started_at: new Date(),
        registration_ended_at: new Date(),
        start_date: new Date(),
        end_date: new Date(),
    };

    const { uploadImages } = useMediaUpload();

    const {
        control,
        register,
        handleSubmit,
    } = useForm({
        defaultValues: initialValues,
    });

    const handleCreateEvent: SubmitHandler<EventFormData> = async ({
        latLng,
        start_date,
        end_date,
        registration_ended_at,
        registration_started_at,
        ...data
    }) => {
        setProcessing(true);

        const keys = await uploadImages(data.logo || []);

        const formData = {
            ...data,
            logo: keys && keys.length > 0 ? keys[0] : null,
            price: data.is_free ? 0 : data.price,
            percent_off: data.is_free ? 0 : data.percent_off,
            capacity: data.with_capacity ? data.capacity : null,
            online_link: data.is_online ? data.online_link : null,
            location: data.is_online ? null : data.location,
            lat: data.is_online ? null : latLng.lat,
            lng: data.is_online ? null : latLng.lng,
            start_date: start_date.toISOString().split('T')[0],
            end_date: end_date.toISOString().split('T')[0],
            registration_started_at: registration_started_at
                .toISOString()
                .split('T')[0],
            registration_ended_at: registration_ended_at
                .toISOString()
                .split('T')[0],
        };

        router.post(events.store(), formData, {
            preserveScroll: true,
            showProgress: true,
            forceFormData: true,
            onSuccess: (params) => {
                toast.success(params.props.message as string);
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
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Crear Evento" />

            <div className="mb-15">
                <Button onClick={() => router.visit(events.index())}>
                    <ChevronLeft />
                    Volver
                </Button>
            </div>

            <form
                className="mx-auto grid w-full max-w-2xl grid-cols-1 gap-6"
                onSubmit={handleSubmit(handleCreateEvent)}
            >
                <EventForm
                    control={control}
                    register={register}
                />

                <Button type="submit" disabled={processing}>
                    Crear Evento
                </Button>
            </form>
        </AppLayout>
    );
}
