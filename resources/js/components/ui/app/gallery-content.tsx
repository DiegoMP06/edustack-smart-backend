import 'photoswipe/dist/photoswipe.css';

import type { Media } from '@/types/media';
import { getIdealResponsiveMediaLink } from '@/lib/utils';
import { Gallery, Item } from 'react-photoswipe-gallery';

type GalleryContentProps = {
    media: Media[];
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

    return (<section className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">

        <Gallery >
            {media.map((item) => {
                const src = item.urls[imageKey] ?? item.urls.original;
                const dimensions =
                    item.dimensions[imageKey] ?? item.dimensions.main;

                return (
                    <Item
                        key={item.id}
                        original={src}
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
                                className="h-full w-full rounded-md border border-border object-cover hover:scale-105 transition duration-300 cursor-pointer"
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
