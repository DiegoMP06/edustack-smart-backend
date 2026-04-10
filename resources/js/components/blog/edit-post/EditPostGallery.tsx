import { router } from '@inertiajs/react';
import { useState } from 'react';
import { toast } from 'sonner';
import EditGallery from '@/components/gallery/EditGallery';
import useMediaUpload from '@/hooks/media/useMediaUpload';
import posts from '@/routes/posts';
import type { ImageFormData, Media } from '@/types';
import type { Post } from '@/types/blog';

type EditPostGalleryProps = {
    gallery: Post['media'];
    postId: Post['id'];
};

export default function EditPostGallery({
    gallery,
    postId,
}: EditPostGalleryProps) {
    const [processing, setProcessing] = useState(false);
    const [isModalActive, setIsModalActive] = useState(false);

    const { uploadImages } = useMediaUpload()

    const handleAddImage = async (data: ImageFormData) => {
        setProcessing(true);

        const keys = await uploadImages(data.images);
        const formData = {
            images: keys
        }

        router.post(posts.medias.store(postId), formData, {
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
            posts.medias.destroy({
                post: postId,
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
            objectId={postId}
            objectType="Post"
            objectKey="post"
            multipleFiles
            processing={processing}
            onAddImage={handleAddImage}
            onDeleteImage={handleDeleteImage}
            isModalActive={isModalActive}
            setIsModalActive={setIsModalActive}
        />
    );
}
