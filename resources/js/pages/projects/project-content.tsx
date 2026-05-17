import { Head, router } from '@inertiajs/react';
import { ChevronLeft } from 'lucide-react';

import ProjectContentForm from '@/components/projects/ProjectContentForm';
import { Button } from '@/components/ui/shadcn/button';
import type { ProjectData } from '@/generated/types/App/Modules/Projects/DTOs';
import SingleProjectLayout from '@/layouts/projects/SingleProjectLayout';
import projects from '@/routes/projects';
import type { BreadcrumbItem } from '@/types';

type ProjectContentProps = {
    project: ProjectData;
    edit: boolean;
};

const breadcrumbs: (project: ProjectData) => BreadcrumbItem[] = (project) => [
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
        <SingleProjectLayout
            breadcrumbs={breadcrumbs(project)}
            project={project}
        >
            <Head title={`Contenido del Proyecto ${project.name}`} />

            {edit ? (
                <div className="mb-15">
                    <Button
                        onClick={() => router.visit(projects.show(project))}
                    >
                        <ChevronLeft />
                        Volver
                    </Button>
                </div>
            ) : null}

            <ProjectContentForm project={project} edit={edit} />
        </SingleProjectLayout>
    );
}
