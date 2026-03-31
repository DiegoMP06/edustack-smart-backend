import type { Media, ResponsiveImages } from '@/types/media';

export const getIdealResponsiveMediaLink = (
    media?: Media,
    size: keyof ResponsiveImages = 'xs',
) =>
    !media
        ? ''
        : media.responsive[size] ||
          Object.values(media.responsive).find((item) => item !== null) ||
          media.urls.original;
