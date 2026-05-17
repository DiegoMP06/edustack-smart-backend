import { Head, router } from '@inertiajs/react';
import { ChevronLeft } from 'lucide-react';
import PostContentForm from '@/components/blog/PostContentForm';
import { Button } from '@/components/ui/shadcn/button';
import type { PostData } from '@/generated/types/App/Modules/Blog/DTOs';
import AppLayout from '@/layouts/app-layout';
import posts from '@/routes/posts';
import type { BreadcrumbItem } from '@/types';

type PostContentProps = {
    post: PostData;
    edit: boolean;
};

const breadcrumbs: (post: PostData) => BreadcrumbItem[] = (post: PostData) => [
    {
        title: 'Blog',
        href: posts.index().url,
    },
    {
        title: post.name,
        href: posts.show(post.id).url,
    },
    {
        title: `Contenido`,
        href: posts.content.edit(post.id).url,
    },
];

export default function PostContent({ post, edit }: PostContentProps) {
    return (
        <AppLayout breadcrumbs={breadcrumbs(post)}>
            <Head title={`Contenido del Post ${post.name}`} />

            {edit ? (
                <div className="mb-15">
                    <Button onClick={() => router.visit(posts.edit(post.id))}>
                        <ChevronLeft />
                        Volver
                    </Button>
                </div>
            ) : null}

            <PostContentForm post={post} edit={edit} />
        </AppLayout>
    );
}
