import type { ReactNode } from 'react';
import type { z } from 'zod';
import type { LinkMetaPaginationSchema, PaginationSchema } from '@/schemas';
import type { BreadcrumbItem } from '@/types/navigation';

export type AppLayoutProps = {
    children: ReactNode;
    breadcrumbs?: BreadcrumbItem[];
    withSearch?: boolean;
    collectionName?: string;
};

export type AppVariant = 'header' | 'sidebar';

export type AuthLayoutProps = {
    children?: ReactNode;
    name?: string;
    title?: string;
    description?: string;
};

export type LinkMetaPagination = z.infer<typeof LinkMetaPaginationSchema>;

export type PaginationType<T = unknown> = z.infer<typeof PaginationSchema> & {
    data: T[];
};
