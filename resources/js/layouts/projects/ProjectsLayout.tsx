import { FileCode } from 'lucide-react';
import AppLayoutTemplate from '@/layouts/app/app-sidebar-layout';
import projects from '@/routes/projects';
import type { AppLayoutProps, NavItem } from '@/types';

const projectsLinks: NavItem[] = [
    {
        title: 'Proyectos',
        href: projects.index(),
        icon: FileCode,
    },
];

export default ({ children, breadcrumbs, ...props }: AppLayoutProps) => (
    <AppLayoutTemplate
        breadcrumbs={breadcrumbs}
        {...props}
        sidebarLinks={projectsLinks}
        sidebarLabel="Proyectos"
    >
        {children}
    </AppLayoutTemplate>
);
