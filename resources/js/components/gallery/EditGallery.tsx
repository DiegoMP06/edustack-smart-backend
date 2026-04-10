import 'photoswipe/dist/photoswipe.css';

import { router } from "@inertiajs/react";
import { useEcho } from "@laravel/echo-react";
import { Plus } from "lucide-react";
import { useEffect } from "react";
import { Gallery } from "react-photoswipe-gallery";
import { Button } from '@/components/ui/shadcn/button';
import type { Media, ImageFormData } from "@/types";
import EditGalleryItem from './EditGalleryItem';
import NewImageModal from './NewImageModal';

type EditGalleryProps = {
    gallery: Media[];
    objectId: number;
    objectType: string;
    objectKey: string
    multipleFiles: boolean;
    processing: boolean;
    isModalActive: boolean;
    onAddImage: (data: ImageFormData) => void;
    onDeleteImage: (mediaId: number) => void;
    setIsModalActive: React.Dispatch<React.SetStateAction<boolean>>
}

export default function EditGallery({
    gallery,
    objectId,
    objectType,
    objectKey,
    multipleFiles,
    processing,
    isModalActive,
    onAddImage,
    onDeleteImage,
    setIsModalActive,
}: EditGalleryProps) {
    const echo = useEcho(`${objectType.toLowerCase()}.${objectId}`, 'MediaProcessed', () => {
        router.reload({ only: [objectKey] });
    });

    useEffect(() => {
        echo.listen()

        return () => echo.leave();
    }, [echo]);

    return (
        <>
            <div className="mt-15">
                <h2 className="mb-4 text-xl font-semibold">Imágenes:</h2>

                <div className="mt-10">
                    <Button onClick={() => setIsModalActive(true)}>
                        <Plus />
                        Agregar Imagen
                    </Button>
                </div>

                <div className="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
                    <Gallery>
                        {gallery.map((image) => (
                            <EditGalleryItem
                                key={image.id}
                                processing={processing}
                                image={image}
                                onDeleteImage={onDeleteImage}
                                objectType={objectType}
                            />
                        ))}
                    </Gallery>
                </div>
            </div>

            <NewImageModal
                processing={processing}
                isModalActive={isModalActive}
                setIsModalActive={setIsModalActive}
                onAddImage={onAddImage}
                multipleFiles={multipleFiles}
            />
        </>
    );
}
