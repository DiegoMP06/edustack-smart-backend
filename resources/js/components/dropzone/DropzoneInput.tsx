import { useEffect, useMemo } from 'react';
import { useDropzone } from 'react-dropzone';
import type { DropzoneFile } from '@/types';
import DropzonePreviewItem from './DropzonePreviewItem';

type DropzoneInputProps = {
    value: File[];
    multipleFiles?: boolean;
    defaultImage?: string | null;
    onChange: (file: File[]) => void;
};

export default function DropzoneInput({
    value,
    multipleFiles,
    onChange,
    defaultImage,
}: DropzoneInputProps) {
    const files = useMemo<DropzoneFile[]>(
        () =>
            value.map((file) => ({
                ...file,
                preview: URL.createObjectURL(file),
            })),
        [value],
    );
    const { getRootProps, getInputProps } = useDropzone({
        accept: {
            'image/*': ['.png', '.jpg', '.jpeg', '.webp'],
        },
        multiple: multipleFiles,
        onDrop: (acceptedFiles) => {
            onChange(acceptedFiles);
        },
    });

    useEffect(() => {
        return () => files.forEach((file) => URL.revokeObjectURL(file.preview));
    }, [files]);

    return (
        <section className="flex flex-col">
            <div
                {...getRootProps({
                    className:
                        'flex flex-col items-center justify-center w-full h-32 border border-accent-foreground border-dashed rounded cursor-pointer',
                })}
            >
                <input {...getInputProps()} />
                <p className="text-center text-xs font-semibold text-accent-foreground">
                    Arrastra o haz click para subir una imagen
                </p>
            </div>

            <aside className="mt-4 flex flex-row flex-wrap items-center justify-center gap-2">
                {!multipleFiles && defaultImage && files.length === 0 && (
                    <DropzonePreviewItem file={defaultImage} />
                )}

                {files.map((file, i) => (
                    <DropzonePreviewItem key={i} file={file} />
                ))}
            </aside>
        </section>
    );
}
