import type { Content } from '@puckeditor/core';
import type { PROJECT_COLLABORATOR_ROLE } from '@/consts/projects';
import type { ComponentProps } from '@/lib/puck';
import type { Collaborator, Media, PivotType, UserData } from '.';

export type ProjectCollaboratorRole = keyof typeof PROJECT_COLLABORATOR_ROLE;
export type ProjectCollaboratorRoleValues =
    (typeof PROJECT_COLLABORATOR_ROLE)[ProjectCollaboratorRole];

export type ProjectStatus = {
    id: number;
    name: string;
    slug: string;
    description: string;
    color: string;
    order: number;
};

export type ProjectCategory = {
    id: number;
    name: string;
    slug: string;
    description: string;
    icon: string;
    order: number;
};

export type Project = {
    id: number;
    name: string;
    slug: string;
    description: string;
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
    collaborators: Collaborator[];
    author: UserData;
};

export type ProjectFormData = Pick<
    Project,
    | 'name'
    | 'description'
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
