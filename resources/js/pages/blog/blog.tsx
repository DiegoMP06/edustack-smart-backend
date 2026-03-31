import { Head, router } from '@inertiajs/react';
import { Plus } from 'lucide-react';
import PostItem from '@/components/blog/PostItem';
import Pagination from '@/components/ui/app/pagination';
import { Button } from '@/components/ui/shadcn/button';
import AppLayout from '@/layouts/app-layout';
import { create, index } from '@/routes/posts';
import type { BreadcrumbItem, PaginationType } from '@/types';
import type { Post } from '@/types/blog';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Blog',
        href: index().url,
    },
];

type BlogProps = {
    posts: PaginationType<Post>;
    filter: { [key: string]: string };
};

export default function Blog({ posts, filter }: BlogProps) {
    return (
        <AppLayout breadcrumbs={breadcrumbs} collectionName='posts' withSearch>
            <Head title="Blog" />

            <div className="mb-15">
                <Button onClick={() => router.visit(create())}>
                    <Plus />
                    Crear Post
                </Button>
            </div>

            {posts.data.length > 0 ? (
                <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    {posts.data.map((post) => (
                        <PostItem key={post.id} post={post} />
                    ))}
                </div>
            ) : (
                <p className="my-20 text-center text-accent-foreground">
                    No Hay Posts
                </p>
            )}

            <Pagination
                pagination={posts}
                queryParams={{
                    ...filter,
                }}
            />
        </AppLayout>
    );
}
