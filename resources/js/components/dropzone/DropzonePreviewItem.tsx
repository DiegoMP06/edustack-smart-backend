import type { DropzoneFile } from '@/types';

type DropzonePreviewItemProps = {
    file: DropzoneFile | string;
};

export default function DropzonePreviewItem({
    file,
}: DropzonePreviewItemProps) {
    return (
        <div className="max-w-52 overflow-hidden rounded border border-accent-foreground">
            {typeof file === 'string' ? (
                <img
                    src={file}
                    alt="Vista previa"
                    className="block aspect-square h-auto w-full object-cover"
                />
            ) : (
                <img
                    src={file.preview}
                    alt={file.name}
                    className="block aspect-square h-auto w-full object-cover"
                    onLoad={() => {
                        URL.revokeObjectURL(file.preview);
                    }}
                />
            )}
        </div>
    );
}
