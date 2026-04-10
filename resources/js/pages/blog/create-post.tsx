import { Head, router } from '@inertiajs/react';
import { ChevronLeft } from 'lucide-react';
import { useState } from 'react';
import type { SubmitHandler } from 'react-hook-form';
import { Controller, useForm } from 'react-hook-form';
import { toast } from 'sonner';
import PostForm from '@/components/blog/PostForm';
import DropzoneInput from '@/components/dropzone/DropzoneInput';
import InputError from '@/components/ui/app/input-error';
import { Button } from '@/components/ui/shadcn/button';
import { Label } from '@/components/ui/shadcn/label';
import useMediaUpload from '@/hooks/media/useMediaUpload';
import AppLayout from '@/layouts/app-layout';
import posts from '@/routes/posts';
import type { BreadcrumbItem } from '@/types';
import type { PostCategory, PostFormData, PostType } from '@/types/blog';

type CreatePostProps = {
    types: PostType[];
    categories: PostCategory[];
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Blog',
        href: posts.index().url,
    },
    {
        title: 'Crear Post',
        href: posts.create().url,
    },
];

export default function CreatePost({ types, categories }: CreatePostProps) {
    const [processing, setProcessing] = useState(false);

    const initialValues: PostFormData = {
        name: '',
        description: '',
        reading_time_minutes: 5,
        images: [],
        post_type_id: 1,
        categories: [],
    };

    const { uploadImages } = useMediaUpload();

    const {
        control,
        register,
        formState: { errors },
        handleSubmit,
    } = useForm({
        defaultValues: initialValues,
    });

    const handleCreatePost: SubmitHandler<PostFormData> = async (data) => {
        setProcessing(true);

        const keys = await uploadImages(data.images || []);
        const formData = {
            ...data,
            images: keys
        }

        router.post(posts.store(), formData, {
            preserveScroll: true,
            showProgress: true,
            onSuccess: (data) => {
                toast.success(data.props.message as string);
            },
            onFinish: () => setProcessing(false),
            onError: (error) => {
                Object.values(error).forEach((value) => toast.error(value));
            },
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Crear Post" />

            <div className="mb-15">
                <Button onClick={() => router.visit(posts.index())}>
                    <ChevronLeft />
                    Volver
                </Button>
            </div>

            <form
                className="mx-auto grid w-full max-w-2xl grid-cols-1 gap-6"
                onSubmit={handleSubmit(handleCreatePost)}
            >
                <PostForm
                    control={control}
                    register={register}
                    types={types}
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
                    Crear Post
                </Button>
            </form>
        </AppLayout>
    );
}
