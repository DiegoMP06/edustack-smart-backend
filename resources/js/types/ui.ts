import type { ReactNode } from 'react';
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

type LinkPagination = {
    url: null | string;
    label: string;
    page: null | number;
    active: boolean;
};

export type PaginationType<T = unknown> = {
    meta: {
        current_page: number;
        from: number;
        last_page: number;
        links: LinkPagination[];
        path: string;
        per_page: number;
        to: number;
        total: number;
    };
    data: T[];
};
