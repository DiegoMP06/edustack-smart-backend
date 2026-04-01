import type { Content } from '@puckeditor/core';
import type { COURSE_ENROLLMENT_STATUS } from '@/consts/classroom';
import type { ComponentProps } from '@/lib/puck';
import type { Media, UserData } from '.';

export type CourseEnrollmentStatus = keyof typeof COURSE_ENROLLMENT_STATUS;
export type CourseEnrollmentStatusValue =
    (typeof COURSE_ENROLLMENT_STATUS)[CourseEnrollmentStatus];

export type CourseStatus = {
    id: number;
    name: string;
    slug: string;
    color?: string;
    order?: number;
};

export type CourseCategory = {
    id: number;
    name: string;
    slug: string;
    color?: string;
    icon?: string;
    order?: number;
};

export type CourseSection = {
    id: number;
    name: string;
    description: string | null;
    order: number;
    is_published: boolean;
    course_id: number;
};

export type CourseLesson = {
    id: number;
    name: string;
    summary: string | null;
    content: Content<ComponentProps>;
    type: 'text' | 'video' | 'activity' | 'live';
    video_url: string | null;
    video_duration_seconds: number | null;
    order: number;
    estimated_minutes: number;
    is_published: boolean;
    is_preview: boolean;
    course_section_id: number;
    course_id: number;
};

export type Assignment = {
    id: number;
    name: string;
    summary: string | null;
    instructions: Content<ComponentProps>;
    max_score: number;
    passing_score: number;
    allow_late_submissions: boolean;
    max_attempts: number;
    submission_type: 'file' | 'text' | 'url' | 'form' | 'mixed';
    is_published: boolean;
    due_date: string | null;
    available_from: string | null;
    course_id: number;
    course_lesson_id: number | null;
    user_id: number;
};

export type CourseDiscussion = {
    id: number;
    title: string;
    content: Content<ComponentProps>;
    is_pinned: boolean;
    is_closed: boolean;
    course_id: number;
    course_lesson_id: number | null;
    user_id: number;
};

export type CourseAnnouncement = {
    id: number;
    title: string;
    content: Content<ComponentProps>;
    is_pinned: boolean;
    notify_students: boolean;
    published_at: string | null;
    course_id: number;
    user_id: number;
};

export type Course = {
    id: number;
    name: string;
    slug: string;
    summary: string;
    content: Content<ComponentProps>;
    code: string | null;
    credits: number;
    period: string | null;
    is_published: boolean;
    is_free: boolean;
    price: number;
    capacity: number | null;
    start_date: string | null;
    end_date: string | null;
    enrollment_start_date: string | null;
    enrollment_end_date: string | null;
    course_status_id: number;
    course_category_id: number | null;
    user_id: number;
    created_at: string;
    updated_at: string;
    status?: CourseStatus;
    category?: CourseCategory;
    media: Media[];
    sections?: CourseSection[];
    lessons?: CourseLesson[];
    assignments?: Assignment[];
    discussions?: CourseDiscussion[];
    announcements?: CourseAnnouncement[];
    author?: UserData;
};

export type CourseFormData = {
    name: string;
    cover: File[];
    summary: string;
    code: string;
    credits: number;
    period: string;
    price: number;
    is_free: boolean;
    capacity: number | null;
    course_status_id: number | string;
    course_category_id: number | string | null;
    start_date: string;
    end_date: string;
    enrollment_start_date: string;
    enrollment_end_date: string;
    is_published: boolean;
};
