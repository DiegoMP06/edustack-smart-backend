import { UserData } from '../../Admin/DTOs';
import { MediaData } from '../../Media/DTOs';
export type DraftPostFormData = {
    name: string;
    description: string;
    images?: File[];
    reading_time_minutes: number;
    post_type_id: number;
    categories: number[];
};
export type PostCategoryData = {
    id: number;
    name: string;
    slug: string;
    description: string;
    color: string;
    order: number;
};
export type PostData = {
    id: number;
    name: string;
    slug: string;
    description: string;
    content: Record<string, unknown>;
    views_count: number;
    reading_time_minutes: number;
    is_featured: boolean;
    is_published: boolean;
    published_at: string;
    post_type_id: number;
    user_id: number;
    created_at: string;
    updated_at: string;
    author: UserData | null;
    type: PostTypeData | null;
    categories: PostCategoryData[] | null;
    media: MediaData[] | null;
};
export type PostTypeData = {
    id: number;
    name: string;
    slug: string;
    description: string;
    icon: string;
    order: number;
};
