import { router } from '@inertiajs/react';
import { useState } from 'react';
import type { SubmitHandler } from 'react-hook-form';
import { useForm } from 'react-hook-form';
import { toast } from 'sonner';
import PostForm from '@/components/blog/PostForm';
import { Button } from '@/components/ui/shadcn/button';
import posts from '@/routes/posts';
import type { PostFormData, Post, PostType, PostCategory } from '@/types/blog';

type EditPostFormProps = {
    post: Post;
    types: PostType[];
    categories: PostCategory[];
};

export default function EditPostForm({
    post,
    types,
    categories,
}: EditPostFormProps) {
    const [processing, setProcessing] = useState(false);
    const initialValues: PostFormData = {
        name: post.name,
        description: post.description,
        reading_time_minutes: post.reading_time_minutes,
        post_type_id: post.post_type_id,
        categories: post.categories.map((category) => category.id),
    };

    const {
        control,
        register,
        handleSubmit,
    } = useForm({
        defaultValues: initialValues,
    });

    const handleUpdatePost: SubmitHandler<PostFormData> = (data) => {
        setProcessing(true);
        router.patch(posts.update(post.id), data, {
            forceFormData: false,
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
        });
    };

    return (
        <form
            className="mx-auto grid w-full max-w-2xl grid-cols-1 gap-6"
            onSubmit={handleSubmit(handleUpdatePost)}
        >
            <PostForm
                types={types}
                categories={categories}
                register={register}
                control={control}
            />

            <Button type="submit" disabled={processing}>
                Guardar Cambios
            </Button>
        </form>
    );
}
