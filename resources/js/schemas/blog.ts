import { z } from "zod";
import { MediaSchema } from "./media";
import { PuckContentSchema } from "./puck";
import { PaginationSchema, UserSchema } from ".";

export const PostTypeSchema = z.object({
    id: z.number(),
    name: z.string(),
    slug: z.string(),
    description: z.string(),
    icon: z.string(),
    order: z.number(),
    created_at: z.string().nullable(),
    updated_at: z.string().nullable(),
});

export const PostCategorySchema = z.object({
    id: z.number(),
    name: z.string(),
    slug: z.string(),
    description: z.string().nullable(),
    color: z.string(),
    order: z.number(),
    created_at: z.string(),
    updated_at: z.string(),
});

export const PostSchema = z.object({
    id: z.number(),
    name: z.string(),
    slug: z.string(),
    summary: z.string(),
    content: PuckContentSchema,
    views_count: z.number(),
    reading_time_minutes: z.number(),
    is_featured: z.boolean(),
    is_published: z.boolean(),
    published_at: z.string(),
    post_type_id: z.number(),
    user_id: z.number(),
    created_at: z.string(),
    updated_at: z.string(),
    deleted_at: z.string().nullable(),
    type: PostTypeSchema,
    categories: z.array(PostCategorySchema),
    author: UserSchema,
    media: z.array(MediaSchema),
});

export const PostsWithPagination = PaginationSchema.extend({
    data: z.array(PostSchema),
});
