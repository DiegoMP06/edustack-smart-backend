export type GeneratePresignedURLFormData = {
    images: GeneratePresignedURLItemData[];
};
export type GeneratePresignedURLItemData = {
    id: number;
    extension: string;
    type: string;
};
export type ImageDimensionsData = {
    width: number;
    height: number;
};
export type MediaData = {
    id: number;
    urls: Record<string, string | null>;
    dimensions: Record<string, ImageDimensionsData>;
    responsive: ResponsiveImagesData;
    is_processed: boolean;
    custom_properties: Record<string, unknown>;
};
export type ModelMediaFormData = {
    images: string[];
};
export type PresignedURLData = {
    id: number;
    path: string;
    url: string;
};
export type ResponsiveImagesData = {
    xl: string | null;
    lg: string | null;
    base: string | null;
    md: string | null;
    sm: string | null;
    xs: string | null;
};
