import 'photoswipe/dist/photoswipe.css';

import { Gallery, Item } from 'react-photoswipe-gallery';
import type { MediaData } from '@/generated/types/App/Modules/Shared/DTOs/Media';
import { getIdealResponsiveMediaLink } from '@/lib/utils';

type GalleryContentProps = {
    media: MediaData[];
    alt: string;
    imageKey?: string;
};

export default function GalleryContent({
    media,
    alt,
    imageKey = 'original',
}: GalleryContentProps) {
    if (media.length === 0) {
        return null;
    }

    return (
        <section className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <Gallery>
                {media.map((item) => {
                    const src = item.urls[imageKey] ?? item.urls.original;
                    const dimensions =
                        item.dimensions[imageKey] ?? item.dimensions.main;

                    return (
                        <Item
                            key={item.id}
                            original={src || ''}
                            thumbnail={getIdealResponsiveMediaLink(item)}
                            width={dimensions?.width}
                            height={dimensions?.height}
                        >
                            {({ ref, open }) => (
                                <img
                                    ref={ref}
                                    onClick={open}
                                    src={getIdealResponsiveMediaLink(item)}
                                    alt={alt + '_' + item.id}
                                    width={dimensions?.width}
                                    height={dimensions?.height}
                                    className="h-full w-full cursor-pointer rounded-md border border-border object-cover transition duration-300 hover:scale-105"
                                    loading="lazy"
                                />
                            )}
                        </Item>
                    );
                })}
            </Gallery>
        </section>
    );
}
