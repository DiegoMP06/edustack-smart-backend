import { router } from '@inertiajs/react';
import { useState } from 'react';
import { toast } from 'sonner';
import EditGallery from '@/components/gallery/EditGallery';
import useMediaUpload from '@/hooks/media/useMediaUpload';
import events from '@/routes/events';
import type { EventActivity, ImageFormData, Media } from '@/types';

type EditActivityGalleryProps = {
    gallery: EventActivity['media']
    eventId: EventActivity['event_id'];
    activityId: EventActivity['id']
}

export default function EditActivityGallery({ gallery, activityId, eventId }: EditActivityGalleryProps) {
    const [processing, setProcessing] = useState(false);
    const [isModalActive, setIsModalActive] = useState(false);

    const { uploadImages } = useMediaUpload()

    const handleAddImage = async (data: ImageFormData) => {
        setProcessing(true);

        const keys = await uploadImages(data.images);
        const formData = {
            images: keys
        }

        router.post(events.activities.medias.store({ event: eventId, activity: activityId }), formData, {
            preserveScroll: true,
            showProgress: true,
            onSuccess: (data) => {
                setIsModalActive(false);
                toast.success(data.props.message as string);
            },
            onFinish: () => setProcessing(false),
            onError: (error) => {
                Object.values(error).forEach((value) => toast.error(value));
            },
        });
    }

    const handleDeleteImage = (mediaId: Media['id']) => {
        setProcessing(true);
        router.delete(
            events.activities.medias.destroy({
                event: eventId,
                activity: activityId,
                media: mediaId,
            }),
            {
                preserveScroll: true,
                showProgress: true,
                forceFormData: false,
                onSuccess(data) {
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
    }

    return (
        <EditGallery
            gallery={gallery}
            objectId={activityId}
            objectType="event-activity"
            objectKey="activity"
            multipleFiles
            processing={processing}
            onAddImage={handleAddImage}
            onDeleteImage={handleDeleteImage}
            isModalActive={isModalActive}
            setIsModalActive={setIsModalActive}
        />
    );
}

