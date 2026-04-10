import { z } from 'zod';

export const LinkMetaPaginationSchema = z.object({
    url: z.string().nullable(),
    label: z.string(),
    active: z.boolean(),
});

export const MetaPaginationSchema = z.object({
    current_page: z.number(),
    from: z.number().nullable(),
    last_page: z.number(),
    links: z.array(LinkMetaPaginationSchema),
    path: z.string(),
    per_page: z.number(),
    to: z.number().nullable(),
    total: z.number(),
});

export const PaginationSchema = z.object({
    links: z.object({
        first: z.string(),
        last: z.string(),
        prev: z.string().nullable(),
        next: z.string().nullable(),
    }),
    meta: MetaPaginationSchema,
});

export const UserSchema = z.object({
    id: z.number(),
    name: z.string(),
    father_last_name: z.string(),
    mother_last_name: z.string(),
    email: z.string(),
    phone: z.string().nullable(),
    bio: z.string().nullable(),
});

export const UsersWithPaginationSchema = PaginationSchema.extend({
    data: z.array(UserSchema),
});
