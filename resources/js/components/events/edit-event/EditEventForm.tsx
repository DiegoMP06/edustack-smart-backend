import { router } from '@inertiajs/react';
import { LatLng } from 'leaflet';
import { useState } from 'react';
import type { SubmitHandler } from 'react-hook-form';
import { useForm } from 'react-hook-form';
import { toast } from 'sonner';
import EventForm from '@/components/events/EventForm';
import { Button } from '@/components/ui/shadcn/button';
import useMediaUpload from '@/hooks/media/useMediaUpload';
import events from '@/routes/events';
import type { Event, EventFormData } from '@/types';

type EditEventFormProps = {
    event: Event;
};

export default function EditEventForm({ event }: EditEventFormProps) {
    const [processing, setProcessing] = useState(false);

    const initialValues: EventFormData = {
        name: event.name,
        logo: [],
        description: event.description,
        is_free: event.price === 0,
        price: event.price,
        percent_off: event.percent_off,
        with_capacity: event.capacity !== null,
        capacity: event.capacity ?? 0,
        is_online: event.is_online,
        online_link: event.online_link,
        latLng: new LatLng(Number(event.lat), Number(event.lng)),
        location: event.location ?? '',
        start_date: new Date(event.start_date),
        end_date: new Date(event.end_date),
        registration_ended_at: new Date(event.registration_ended_at),
        registration_started_at: new Date(event.registration_started_at),
    };

    const { uploadImages } = useMediaUpload();

    const {
        control,
        register,
        handleSubmit,
        formState: { errors },
    } = useForm({
        defaultValues: initialValues,
    });

    const handleEditEvent: SubmitHandler<EventFormData> = async ({
        latLng,
        start_date,
        end_date,
        registration_ended_at,
        registration_started_at,
        ...data
    }) => {
        setProcessing(true);
        let keys: string[] = [];

        if (data.logo.length > 0) {
            keys = ((await uploadImages(data.logo)) || []) as string[];
        }

        console.log(keys)

        const formData = {
            ...data,
            logo: keys.length > 0 ? keys[0] : null,
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

        router.patch(
            events.update(event.id),
            formData,
            {
                preserveScroll: true,
                showProgress: true,
                onSuccess: (data) => {
                    toast.success(data.props.message as string);
                },
                onFinish() {
                    setProcessing(false);
                },
                onError(error) {
                    Object.values(error).forEach((value) => toast.error(value));
                },
            },
        );
    };

    return (
        <form
            onSubmit={handleSubmit(handleEditEvent)}
            className="mx-auto grid w-full max-w-2xl grid-cols-1 gap-6"
        >
            <EventForm
                {...{
                    control,
                    errors,
                    register,
                    defaultImage: event.media.at(0)?.urls.thumbnail,
                    edit: true,
                }}
            />

            <Button type="submit" disabled={processing}>
                Guardar Cambios
            </Button>
        </form>
    );
}
