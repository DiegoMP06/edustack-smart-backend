import type { Content } from '@puckeditor/core';
import type {
    ROLE_COLLABORATORS,
    PROJECT_STATUS_SLUG,
    PROJECT_LICENSE,
} from '@/consts/projects';
import type { ComponentProps } from '@/lib/puck';
import type { Media, PivotType, UserData } from '.';

export type RoleCollaborators = keyof typeof ROLE_COLLABORATORS;
export type RoleCollaboratorsValues =
    (typeof ROLE_COLLABORATORS)[RoleCollaborators];

export type ProjectStatusSlug = keyof typeof PROJECT_STATUS_SLUG;
export type ProjectStatusSlugValue =
    (typeof PROJECT_STATUS_SLUG)[ProjectStatusSlug];

export type ProjectLicense = keyof typeof PROJECT_LICENSE;
export type ProjectLicenseValue = (typeof PROJECT_LICENSE)[ProjectLicense];

export type ProjectStatus = {
    id: number;
    name: string;
    slug: string;
    description?: string;
    color?: string;
    order: number;
};

export type ProjectCategory = {
    id: number;
    name: string;
    slug: string;
    description?: string;
    icon?: string;
    order: number;
};

export type Project = {
    id: number;
    name: string;
    slug: string;
    summary: string;
    content: Content<ComponentProps>;
    repository_url: string;
    demo_url: string;
    tech_stack: string[];
    version: string;
    license: string;
    is_featured: boolean;
    is_published: boolean;
    published_at: string | null;
    project_status_id: number;
    user_id: number;
    created_at: string;
    updated_at: string;
    media: Media[];
    categories: PivotType<ProjectCategory>[];
    status: ProjectStatus;
    collaborators: PivotType<
        UserData,
        {
            role: RoleCollaborators;
        }
    >[];
    author: UserData;
};

export type ProjectFormData = Pick<
    Project,
    | 'name'
    | 'summary'
    | 'repository_url'
    | 'demo_url'
    | 'tech_stack'
    | 'version'
    | 'license'
    | 'project_status_id'
> & {
    categories: number[];
    images?: File[];
};
