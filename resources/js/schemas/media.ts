import { z } from 'zod';

export const ResponsiveImagesSchema = z
    .object({
        xl: z.url().optional(),
        lg: z.url().optional(),
        base: z.url().optional(),
        md: z.url().optional(),
        sm: z.url().optional(),
        xs: z.url().optional(),
    })
    .partial();

export const ImageDimensionsSchema = z.object({
    width: z.number().positive(),
    height: z.number().positive(),
});

export const MediaSchema = z.object({
    id: z.number().int().positive(),
    urls: z.record(z.string(), z.url()).and(
        z.object({
            original: z.url(),
        }),
    ),
    dimensions: z.record(z.string(), ImageDimensionsSchema),
    responsive: ResponsiveImagesSchema,
    is_processed: z.boolean(),
    custom_properties: z.array(z.string()),
});

export const PresignedUrlItemSchema = z.object({
    path: z.string(),
    url: z.string(),
    id: z.number().min(0),
});

export const PresignedUrlsSchema = z.array(PresignedUrlItemSchema);
