import { useState } from 'react';
import { Item } from 'react-photoswipe-gallery';
import ConfirmDialog from '@/components/ui/app/confirm-dialog';
import ImageSkeleton from '@/components/ui/app/image-skeleton';
import {
    ContextMenu,
    ContextMenuContent,
    ContextMenuItem,
    ContextMenuTrigger,
} from '@/components/ui/shadcn/context-menu';
import { getIdealResponsiveMediaLink } from '@/lib/utils';
import type { Media } from '@/types';

type EditGalleryItemProps = {
    image: Media;
    processing: boolean;
    onDeleteImage: (mediaId: Media['id']) => void;
    objectType: string;
}

export default function EditGalleryItem({
    image,
    processing,
    onDeleteImage,
    objectType
}: EditGalleryItemProps) {
    const [isDeleteDialogOpen, setIsDeleteDialogOpen] = useState(false);

    const handleDeleteImage = () => {
        setIsDeleteDialogOpen(true);
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
                                    alt={`imagen ${image.id} de ${objectType}`}
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
                onConfirm={() => onDeleteImage(image.id)}
                title="¿Eliminar imagen?"
                description="Esta acción eliminará la imagen de forma permanente."
                confirmLabel="Sí, eliminar"
                confirmDisabled={processing}
            />
        </>
    )
}
