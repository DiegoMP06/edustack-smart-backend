import { Head, router } from '@inertiajs/react';
import { ChevronLeft } from 'lucide-react';
import ProjectContentForm from '@/components/projects/ProjectContentForm';
import { Button } from '@/components/ui/shadcn/button';
import AppLayout from '@/layouts/app-layout';
import projects from '@/routes/projects';
import type { BreadcrumbItem } from '@/types';
import type { Project } from '@/types/projects';

type ProjectContentProps = {
    project: Project;
    edit: boolean;
};

const breadcrumbs: (project: Project) => BreadcrumbItem[] = (
    project: Project,
) => [
    {
        title: 'Proyectos',
        href: projects.index().url,
    },
    {
        title: `${project.name}`,
        href: projects.show(project.id).url,
    },
    {
        title: `Contenido`,
        href: projects.content.edit(project.id).url,
    },
];

export default function ProjectContent({ project, edit }: ProjectContentProps) {
    return (
        <AppLayout breadcrumbs={breadcrumbs(project)}>
            <Head title={`Contenido del Proyecto ${project.name}`} />

            {edit ? (
                <div className="mb-15">
                    <Button
                        onClick={() => router.visit(projects.edit(project))}
                    >
                        <ChevronLeft />
                        Volver
                    </Button>
                </div>
            ) : null}

            <ProjectContentForm project={project} edit={edit} />
        </AppLayout>
    );
}
