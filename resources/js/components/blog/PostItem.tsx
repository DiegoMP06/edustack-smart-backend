import { Link, router } from '@inertiajs/react';
import { Check, MoreHorizontalIcon, Pencil, Trash, XIcon } from 'lucide-react';
import { useState } from 'react';
import { toast } from 'sonner';
import ConfirmDialog from '@/components/ui/app/confirm-dialog';
import { Icon } from '@/components/ui/app/icon';
import { Badge } from '@/components/ui/shadcn/badge';
import { Button } from '@/components/ui/shadcn/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuTrigger,
} from '@/components/ui/shadcn/dropdown-menu';
import {
    HoverCard,
    HoverCardContent,
    HoverCardTrigger,
} from '@/components/ui/shadcn/hover-card';
import {
    Item,
    ItemActions,
    ItemContent,
    ItemDescription,
    ItemHeader,
    ItemTitle,
} from '@/components/ui/shadcn/item';
import { getIdealResponsiveMediaLink, cn } from '@/lib/utils';
import posts from '@/routes/posts';
import type { Post } from '@/types/blog';

type PostItemProps = {
    post: Post;
};

export default function PostItem({ post }: PostItemProps) {
    const [processing, setProcessing] = useState(false);
    const [isDeleteDialogOpen, setIsDeleteDialogOpen] = useState(false);

    const handlePostStatus = () => {
        setProcessing(true);
        router.patch(
            posts.status(post.id),
            {},
            {
                preserveScroll: true,
                showProgress: true,
                onError(error) {
                    Object.values(error).forEach((value) => toast.error(value));
                },
                onFinish() {
                    setProcessing(false);
                },
                onSuccess(data) {
                    toast.success(data.props.message as string);
                },
            },
        );
    };

    const deletePost = () => {
        setProcessing(true);
        router.delete(posts.destroy(post.id), {
            preserveScroll: true,
            showProgress: true,
            onError(error) {
                Object.values(error).forEach((value) => toast.error(value));
            },
            onFinish() {
                setProcessing(false);
            },
            onSuccess(data) {
                toast.success(data.props.message as string);
            },
        });
    };

    const handleDeletePost = () => {
        setIsDeleteDialogOpen(true);
    };

    return (
        <>
            <Item variant="outline" className="items-start gap-0 p-0">
                <ItemHeader>
                    <img
                        src={getIdealResponsiveMediaLink(post.media.at(0))}
                        alt={post.name}
                        className="block h-auto w-full rounded-t-md rounded-b-none object-cover"
                        width={post.media.at(0)?.dimensions.main.width}
                        height={post.media.at(0)?.dimensions.main.height}
                    />
                </ItemHeader>

                <ItemContent className="p-4">
                    <ItemTitle>
                        <HoverCard>
                            <HoverCardTrigger asChild>
                                <Link
                                    href={posts.show(post.id)}
                                    className="text-base hover:underline"
                                >
                                    {post.name}
                                </Link>
                            </HoverCardTrigger>
                            <HoverCardContent className="w-80">
                                <div className="grid gap-3">
                                    <h4 className="flex flex-wrap items-center gap-2 text-lg font-semibold">
                                        {post.name}

                                        <Badge
                                            className={cn(
                                                post.is_published
                                                    ? 'bg-green-100 text-green-800'
                                                    : 'bg-red-100 text-red-800',
                                            )}
                                        >
                                            {post.is_published
                                                ? 'Publicado'
                                                : 'Oculto'}
                                        </Badge>
                                    </h4>

                                    <p className="flex flex-wrap items-center gap-2 text-sm font-semibold">
                                        Tipo de Post:
                                        <Badge variant="outline">
                                            <Icon
                                                iconName={post.type.icon || ''}
                                                className="size-4"
                                            />
                                            {post.type.name}
                                        </Badge>
                                    </p>

                                    <p className="flex flex-wrap items-center gap-2 text-sm font-semibold">
                                        Categorías:
                                        {post.categories.map((category) => (
                                            <Badge
                                                variant="secondary"
                                                key={category.id}
                                            >
                                                {category.name}
                                            </Badge>
                                        ))}
                                    </p>
                                </div>
                            </HoverCardContent>
                        </HoverCard>
                    </ItemTitle>

                    <ItemDescription>
                        {post.description.substring(0, 100)}...
                    </ItemDescription>
                </ItemContent>

                <ItemActions className="p-4">
                    <DropdownMenu modal={false}>
                        <DropdownMenuTrigger asChild>
                            <Button
                                variant="ghost"
                                aria-label="Open menu"
                                size="icon"
                            >
                                <MoreHorizontalIcon />
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent className="w-40" align="end">
                            <DropdownMenuLabel>
                                Opciones del post
                            </DropdownMenuLabel>

                            <DropdownMenuGroup>
                                <DropdownMenuItem asChild disabled={processing}>
                                    <Link href={posts.edit(post.id)}>
                                        <Pencil />
                                        Editar
                                    </Link>
                                </DropdownMenuItem>

                                <DropdownMenuItem
                                    onClick={handlePostStatus}
                                    disabled={processing}
                                    variant={
                                        post.is_published
                                            ? 'destructive'
                                            : 'default'
                                    }
                                >
                                    {post.is_published ? (
                                        <>
                                            <XIcon />
                                            Ocultar
                                        </>
                                    ) : (
                                        <>
                                            <Check />
                                            Publicar
                                        </>
                                    )}
                                </DropdownMenuItem>

                                <DropdownMenuItem
                                    onClick={handleDeletePost}
                                    disabled={processing}
                                    variant="destructive"
                                >
                                    <Trash />
                                    Eliminar
                                </DropdownMenuItem>
                            </DropdownMenuGroup>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </ItemActions>
            </Item>

            <ConfirmDialog
                open={isDeleteDialogOpen}
                onOpenChange={setIsDeleteDialogOpen}
                onConfirm={deletePost}
                title="¿Eliminar post?"
                description="Esta acción eliminará el post de forma permanente."
                confirmLabel="Sí, eliminar"
                confirmDisabled={processing}
            />
        </>
    );
}
