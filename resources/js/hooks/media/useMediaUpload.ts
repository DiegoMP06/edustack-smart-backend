import { toast } from 'sonner';
import type { z } from 'zod';
import { apiFetch } from '@/lib/api';
import type { PresignedUrlItemSchema } from '@/schemas/media';
import { PresignedUrlsSchema } from '@/schemas/media';

type PresignedUrlItem = z.infer<typeof PresignedUrlItemSchema>;
type UploadFileItem = {
    extension?: string;
    type: string;
    id: number;
};

export default function useMediaUpload() {
    const getPresignedUrls = async (images: UploadFileItem[]) => {
        const presignedUrlsResponse = await apiFetch(
            '/media/presigned-url',
            {},
            {
                method: 'POST',
                body: JSON.stringify({
                    images,
                }),
                headers: { 'Content-Type': 'application/json' },
            },
        );

        const presignedUrls = PresignedUrlsSchema.safeParse(
            presignedUrlsResponse,
        );

        if (presignedUrls.error) {
            toast.error('Ocurrió un error al subir las imágenes');

            return { data: null, error: true };
        }

        return { data: presignedUrls.data, error: false };
    };

    const uploadTempImages = async (
        images: File[],
        presignedUrls: PresignedUrlItem[],
    ) => {
        return await Promise.all(
            presignedUrls.map(async ({ id, path, url }) => {
                const file = images?.[id];

                if (!file) {
                    return;
                }

                const s3Response = await fetch(url, {
                    method: 'PUT',
                    body: file,
                    headers: { 'Content-Type': file.type },
                });

                if (!s3Response.ok) {
                    toast.error('Ocurrió un error al subir las imágenes');

                    return;
                }

                return path;
            }),
        );
    };

    const uploadImages = async (images: File[]) => {
        const uploadFiles: UploadFileItem[] = images.map((file, index) => ({
            extension: file.name.split('.').pop(),
            type: file.type,
            id: index,
        }));

        const presignedUrls = await getPresignedUrls(uploadFiles);

        if (presignedUrls.error || !presignedUrls.data) {
            return;
        }

        return await uploadTempImages(images, presignedUrls.data);
    };

    return {
        uploadImages,
    };
}
