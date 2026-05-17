import { Head, router } from '@inertiajs/react';
import { Plus } from 'lucide-react';

import ProjectItem from '@/components/projects/ProjectItem';
import Pagination from '@/components/ui/app/pagination';
import { Button } from '@/components/ui/shadcn/button';
import type { ProjectData } from '@/generated/types/App/Modules/Projects/DTOs';
import type { ListCollectionQueryParamsData } from '@/generated/types/App/Modules/Shared/DTOs/Query';
import ProjectsLayout from '@/layouts/projects/ProjectsLayout';
import { create, index } from '@/routes/projects';
import type { BreadcrumbItem, PaginationType } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Proyectos',
        href: index().url,
    },
];

type ProjectsProps = {
    filter: ListCollectionQueryParamsData['filter'];
    projects: PaginationType<ProjectData>;
};

export default function Projects({ filter, projects }: ProjectsProps) {
    return (
        <ProjectsLayout
            breadcrumbs={breadcrumbs}
            withSearch
            collectionName="projects"
        >
            <Head title="Proyectos" />

            <div className="mb-15">
                <Button onClick={() => router.visit(create())}>
                    <Plus />
                    Crear Proyecto
                </Button>
            </div>

            {projects.data.length > 0 ? (
                <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    {projects.data.map((project) => (
                        <ProjectItem key={project.id} project={project} />
                    ))}
                </div>
            ) : (
                <p className="my-20 text-center text-accent-foreground">
                    No Hay Proyectos
                </p>
            )}

            <Pagination pagination={projects} queryParams={{ filter }} />
        </ProjectsLayout>
    );
}
