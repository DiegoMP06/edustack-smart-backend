import { Head, router } from '@inertiajs/react';
import { Plus } from 'lucide-react';
import ProjectItem from '@/components/projects/ProjectItem';
import Pagination from '@/components/ui/app/pagination';
import { Button } from '@/components/ui/shadcn/button';
import AppLayout from '@/layouts/app-layout';
import { create, index } from '@/routes/projects';
import type { BreadcrumbItem, PaginationType } from '@/types';
import type { Project } from '@/types/projects';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Proyectos',
        href: index().url,
    },
];

type ProjectsProps = {
    page: number;
    filter: string;
    projects: PaginationType<Project>;
};

export default function Projects({ filter, projects }: ProjectsProps) {
    return (
        <AppLayout breadcrumbs={breadcrumbs} withSearch collectionName='projects'>
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
        </AppLayout>
    );
}
