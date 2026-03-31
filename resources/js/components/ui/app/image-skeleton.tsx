import { useState } from 'react';
import { Skeleton } from '@/components/ui/shadcn/skeleton';
import { cn } from '@/lib/utils';

type ImageSkeletonProps = {
    src?: string;
    alt?: string;
    width: number;
    height: number;
    className?: string;
};

export default function ImageSkeleton({
    src,
    alt = 'image',
    width,
    height,
    className,
}: ImageSkeletonProps) {
    const [isLoaded, setIsLoaded] = useState(false);

    return (
        <div className={cn(
            'w-full h-auto',
            className
        )} style={{ aspectRatio: `${width}/${height}` }}>
            {!isLoaded && <Skeleton className="block h-full w-full rounded-md" />}
            {src && (
                <img
                    src={src}
                    alt={alt}
                    width={width}
                    height={height}
                    onLoad={() => setIsLoaded(true)}
                    className={
                        isLoaded ? 'block h-full w-full object-cover' : 'hidden'
                    }
                />
            )}
        </div>
    );
}
