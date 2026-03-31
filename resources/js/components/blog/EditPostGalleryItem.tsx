import { router } from '@inertiajs/react';
import { useState } from 'react';
import type { Dispatch, SetStateAction } from 'react';
import { Item } from 'react-photoswipe-gallery';
import { toast } from 'sonner';
import ConfirmDialog from '@/components/ui/app/confirm-dialog';
import ImageSkeleton from '@/components/ui/app/image-skeleton';

import {
    ContextMenu,
    ContextMenuContent,
    ContextMenuItem,
    ContextMenuTrigger,
} from '@/components/ui/shadcn/context-menu';
import { getIdealResponsiveMediaLink } from '@/lib/utils';
import posts from '@/routes/posts';
import type { Media } from '@/types';
import type { Post } from '@/types/blog';

type EditPostGalleryItemProps = {
    image: Media;
    postId: Post['id'];
    processing: boolean;
    setProcessing: Dispatch<SetStateAction<boolean>>;
};

export default function EditPostGalleryItem({
    image,
    postId,
    processing,
    setProcessing,
}: EditPostGalleryItemProps) {
    const [isDeleteDialogOpen, setIsDeleteDialogOpen] = useState(false);

    const handleDeleteImage = () => {
        setIsDeleteDialogOpen(true);
    };

    const deleteImage = () => {
        setProcessing(true);
        router.delete(
            posts.medias.destroy({
                post: postId,
                media: image.id,
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
    };

    return (
        <>
            <Item
                original={image.urls.main}
                thumbnail={getIdealResponsiveMediaLink(image)}
                width={image.dimensions.main.width}
                height={image.dimensions.main.height}
            >
                {({ ref, open }) =>
                    image.is_processed ? (
                        <ContextMenu>
                            <ContextMenuTrigger className="block overflow-hidden rounded-md border border-dashed">
                                <img
                                    ref={ref}
                                    onClick={open}
                                    src={getIdealResponsiveMediaLink(image)}
                                    alt={`imagen ${image.id} del post ${postId}`}
                                    width={image.dimensions.main.width}
                                    height={image.dimensions.main.height}
                                />
                            </ContextMenuTrigger>
                            <ContextMenuContent className="w-52">
                                <ContextMenuItem
                                    variant="destructive"
                                    disabled={processing}
                                    onClick={handleDeleteImage}
                                >
                                    Eliminar
                                </ContextMenuItem>
                            </ContextMenuContent>
                        </ContextMenu>
                    ) : (
                        <ImageSkeleton
                            width={image.dimensions.main.width}
                            height={image.dimensions.main.height}
                        />
                    )
                }
            </Item>

            <ConfirmDialog
                open={isDeleteDialogOpen}
                onOpenChange={setIsDeleteDialogOpen}
                onConfirm={deleteImage}
                title="¿Eliminar imagen?"
                description="Esta acción eliminará la imagen de forma permanente."
                confirmLabel="Sí, eliminar"
                confirmDisabled={processing}
            />
        </>
    );
}
