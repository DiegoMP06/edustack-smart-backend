import { Head, router } from '@inertiajs/react';
import { ChevronLeft } from 'lucide-react';
import { Button } from '@/components/ui/shadcn/button';
import AppLayout from '@/layouts/app-layout';
import posts from '@/routes/posts';
import type { BreadcrumbItem } from '@/types';
import type { Post, PostCategory, PostType } from '@/types/blog';
import EditPostForm from './EditPost/EditPostForm';
import EditPostGallery from './EditPost/EditPostGallery';
import PostOptions from './EditPost/PostOptions';

const breadcrumbs = (post: Post): BreadcrumbItem[] => [
    {
        title: 'Blog',
        href: posts.index().url,
    },
    {
        title: post.name,
        href: posts.show(post.id).url,
    },
    {
        title: `Editar`,
        href: posts.edit(post.id).url,
    },
];

type EditPostProps = {
    post: Post;
    types: PostType[];
    categories: PostCategory[];
};

export default function EditPost({ post, types, categories }: EditPostProps) {
    return (
        <AppLayout breadcrumbs={breadcrumbs(post)}>
            <Head title={`Editar ${post.name}`} />

            <div className="mb-15">
                <Button onClick={() => router.visit(posts.index())}>
                    <ChevronLeft />
                    Volver
                </Button>
            </div>

            <div className="flex flex-col gap-12 lg:flex-row lg:items-start lg:gap-10">
                <EditPostForm
                    post={post}
                    types={types}
                    categories={categories}
                />

                <aside className="mx-auto flex w-full max-w-2xl flex-col gap-6">
                    <PostOptions
                        isPublished={post.is_published}
                        postId={post.id}
                    />
                </aside>
            </div>

            <EditPostGallery postId={post.id} gallery={post.media} />
        </AppLayout>
    );
}
