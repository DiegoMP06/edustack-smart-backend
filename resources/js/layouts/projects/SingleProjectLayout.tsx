import { AppWindow, FileCode, UserCog } from 'lucide-react';
import type { ProjectData } from '@/generated/types/App/Modules/Projects/DTOs';
import AppLayoutTemplate from '@/layouts/app/app-sidebar-layout';
import projects from '@/routes/projects';
import type { AppLayoutProps, NavItem } from '@/types';

type SingleProjectLayoutProps = AppLayoutProps & {
    project: ProjectData;
};

const singleEventLinks = (project: ProjectData): NavItem[] => [
    {
        title: 'Proyectos',
        href: projects.index(),
        icon: FileCode,
    },
    {
        title: 'Colaboradores',
        href: projects.collaborators.index(project.id, {
            query: { edit: true },
        }),
        icon: UserCog,
    },
    {
        title: 'Contenido',
        href: projects.content.edit(project.id, { query: { edit: true } }),
        icon: AppWindow,
    },
];

export default ({
    children,
    breadcrumbs,
    project,
    ...props
}: SingleProjectLayoutProps) => {
    const SidebarLabel =
        project.name.length > 25
            ? project.name.slice(0, 25) + '...'
            : project.name;

    return (
        <AppLayoutTemplate
            breadcrumbs={breadcrumbs}
            {...props}
            sidebarLinks={singleEventLinks(project)}
            sidebarLabel={SidebarLabel}
        >
            {children}
        </AppLayoutTemplate>
    );
};
