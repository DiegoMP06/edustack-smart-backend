import { router } from '@inertiajs/react';
import type { Dispatch, SetStateAction } from 'react';
import { useState } from 'react';
import { useForm } from 'react-hook-form';
import { toast } from 'sonner';
import NewImageModal from '@/components/dropzone/NewImageModal';
import type { ImageFormData } from '@/components/dropzone/NewImageModal';
import posts from '@/routes/posts';

type NewPostImageModalProps = {
    postId: number;
    isModalActive: boolean;
    setIsModalActive: Dispatch<SetStateAction<boolean>>;
};

export default function NewPostImageModal({
    postId,
    isModalActive,
    setIsModalActive,
}: NewPostImageModalProps) {
    const [processing, setProcessing] = useState(false);

    const initialValues: ImageFormData = {
        images: [],
    };

    const {
        control,
        handleSubmit,
        formState: { errors },
        reset,
    } = useForm({
        defaultValues: initialValues,
    });

    const handleAddImage = (data: ImageFormData) => {
        setProcessing(true);
        router.post(posts.medias.store(postId), data, {
            forceFormData: true,
            preserveScroll: true,
            showProgress: true,
            onSuccess: (data) => {
                toast.success(data.props.message as string);
                setIsModalActive(false);
            },
            onFinish: () => setProcessing(false),
            onError: (error) => {
                Object.values(error).forEach((value) => toast.error(value));
            },
        });
    };

    return (
        <NewImageModal
            {...{
                reset,
                isModalActive,
                setIsModalActive,
                processing,
                onSubmit: handleSubmit(handleAddImage),
                control,
                errors,
                multipleFiles: true,
            }}
        />
    );
}
