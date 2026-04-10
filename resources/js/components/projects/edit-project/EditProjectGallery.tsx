import { router } from '@inertiajs/react';
import { useState } from 'react';
import { toast } from 'sonner';
import EditGallery from '@/components/gallery/EditGallery';
import useMediaUpload from '@/hooks/media/useMediaUpload';
import projects from '@/routes/projects';
import type { ImageFormData, Media, Project } from '@/types';

type EditProjectGalleryProps = {
    gallery: Project['media'];
    projectId: Project['id'];
};

export default function EditProjectGallery({
    gallery,
    projectId,
}: EditProjectGalleryProps) {
    const [processing, setProcessing] = useState(false);
    const [isModalActive, setIsModalActive] = useState(false);

    const { uploadImages } = useMediaUpload()

    const handleAddImage = async (data: ImageFormData) => {
        setProcessing(true);

        const keys = await uploadImages(data.images);
        const formData = {
            images: keys
        }

        router.post(projects.medias.store(projectId), formData, {
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
            projects.medias.destroy({
                project: projectId,
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
            objectId={projectId}
            objectType="Project"
            objectKey="project"
            multipleFiles
            processing={processing}
            onAddImage={handleAddImage}
            onDeleteImage={handleDeleteImage}
            isModalActive={isModalActive}
            setIsModalActive={setIsModalActive}
        />
    );
}
