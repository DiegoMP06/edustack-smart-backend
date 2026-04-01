import { Head, router } from '@inertiajs/react';
import { ChevronLeft } from 'lucide-react';
import EditProjectForm from '@/components/projects/edit-project/EditProjectForm';
import EditProjectGallery from '@/components/projects/edit-project/EditProjectGallery';
import ProjectOptions from '@/components/projects/edit-project/ProjectOptions';
import { Button } from '@/components/ui/shadcn/button';
import AppLayout from '@/layouts/app-layout';
import projects from '@/routes/projects';
import type { BreadcrumbItem } from '@/types';
import type { Project, ProjectCategory, ProjectStatus } from '@/types/projects';

type EditProjectProps = {
    project: Project;
    statuses: ProjectStatus[];
    categories: ProjectCategory[];
};

const breadcrumbs = (project: Project): BreadcrumbItem[] => [
    {
        title: 'Proyectos',
        href: projects.index().url,
    },
    {
        title: project.name,
        href: projects.show(project.id).url,
    },
    {
        title: `Editar`,
        href: projects.edit(project.id).url,
    },
];

export default function EditProject({
    project,
    statuses,
    categories,
}: EditProjectProps) {
    return (
        <AppLayout breadcrumbs={breadcrumbs(project)}>
            <Head title={`Editar ${project.name}`} />

            <div className="mb-15">
                <Button onClick={() => router.visit(projects.index())}>
                    <ChevronLeft />
                    Volver
                </Button>
            </div>

            <div className="grid grid-cols-1 gap-12 lg:grid-cols-2 lg:gap-10">
                <EditProjectForm
                    statuses={statuses}
                    categories={categories}
                    project={project}
                />

                <aside className="mx-auto flex w-full max-w-2xl flex-col gap-6">
                    <ProjectOptions
                        projectId={project.id}
                        isPublished={project.is_published}
                    />
                </aside>
            </div>

            <EditProjectGallery
                projectId={project.id}
                gallery={project.media}
            />
        </AppLayout>
    );
}
