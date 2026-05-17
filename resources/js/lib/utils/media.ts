import type {
    MediaData,
    ResponsiveImagesData,
} from '@/generated/types/App/Modules/Shared/DTOs/Media';

export const getIdealResponsiveMediaLink = (
    media?: MediaData,
    size: keyof ResponsiveImagesData = 'xs',
): string =>
    !media
        ? ''
        : media.responsive[size] ||
          Object.values(media.responsive).find((item) => item !== null) ||
          media.urls.original ||
          '';
