import { Head, router } from '@inertiajs/react';
import { Check, ChevronLeft, Eye } from 'lucide-react';
import { useState } from 'react';
import { toast } from 'sonner';
import ShowCollaboratorsModal from '@/components/collaborators/ShowCollaboratorsModal';
import UserCollaboratorItem from '@/components/collaborators/UserCollaboratorItem';
import Pagination from '@/components/ui/app/pagination';
import { Button } from '@/components/ui/shadcn/button';
import { PROJECT_COLLABORATOR_ROLE } from '@/consts/projects';
import AppLayout from '@/layouts/app-layout';
import projectCollaborators from '@/routes/project-collaborators';
import projects from '@/routes/projects';
import type {
    BreadcrumbItem,
    PaginationType,
    Project,
    UserData
} from '@/types';

type ProjectCollaboratorsProps = {
    users: PaginationType<UserData>;
    project: Project;
    page: number;
    search: string;
    message?: string;
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
            title: project.name,
            href: projects.show(project.id).url,
        },
        {
            title: `Colaboradores`,
            href: projectCollaborators.index(project.id).url,
        },
    ];

export default function ProjectCollaborators({
    users,
    project,
    edit,
    search,
}: ProjectCollaboratorsProps) {
    const [isModalActive, setIsModalActive] = useState(false);
    const [processing, setProcessing] = useState(false);

    const handleAddCollaborator = (userId: UserData['id'], role: string,) => {
        setProcessing(true);
        const formData = {
            user_id: userId,
            role,
        };

        router.post(projectCollaborators.store(project.id), formData, {
            preserveScroll: true,
            forceFormData: false,
            showProgress: true,
            onError(error) {
                Object.values(error).forEach((value) => toast.error(value));
            },
            onSuccess(data) {
                toast.success(data.props.message as string);
                setIsModalActive(false);
            },
            onFinish() {
                setProcessing(false);
            },
        });
    };

    const handleDeleteCollaborator = (userId: UserData['id']) => {
        setProcessing(true);
        const collaboratorIndex = project.collaborators.find((collaborator) => collaborator.id === userId)?.pivot.id || -1;

        router.delete(
            projectCollaborators.destroy({
                project: project.id,
                project_collaborator: collaboratorIndex,
            }),
            {
                preserveScroll: true,
                forceFormData: false,
                showProgress: true,
                onError(error) {
                    Object.values(error).forEach((value) => toast.error(value));
                },
                onSuccess(data) {
                    toast.success(data.props.message as string);
                },
                onFinish() {
                    setProcessing(false);
                },
            },
        );
    };

    return (
        <AppLayout
            breadcrumbs={breadcrumbs(project)}
            withSearch
            collectionName='users'
        >
            <Head title={`Colaboradores de ${project.name}`} />

            <div className="flex h-screen flex-col gap-6 overflow-hidden">
                <div className="flex-1 overflow-y-auto p-4">

                    <div className="mb-15 flex gap-4">
                        {edit ? (
                            <Button
                                onClick={() =>
                                    router.visit(projects.edit(project))
                                }
                            >
                                <ChevronLeft />
                                Volver
                            </Button>
                        ) : null}
                        <Button
                            onClick={() => setIsModalActive(true)}
                            variant="outline"
                        >
                            <Eye />
                            Ver Colaboradores
                        </Button>
                    </div>

                    {users.data.length > 0 ? (
                        <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                            {users.data.map((user) => (
                                <UserCollaboratorItem
                                    key={user.id}
                                    user={user}
                                    collaborators={project.collaborators}
                                    processing={processing}
                                    roles={PROJECT_COLLABORATOR_ROLE}
                                    onDeleteCollaborator={handleDeleteCollaborator}
                                    onAddCollaborator={handleAddCollaborator}
                                />
                            ))}
                        </div>
                    ) : (
                        <p className="my-20 text-center text-accent-foreground">
                            No Hay Usuarios
                        </p>
                    )}

                    <Pagination
                        pagination={users}
                        queryParams={{
                            search,
                            edit,
                        }}
                    />
                </div>

                <nav className="flex flex-0 justify-end">
                    <Button
                        variant="secondary"
                        onClick={() =>
                            router.visit(
                                edit
                                    ? projects.edit(project)
                                    : projects.index(),
                            )
                        }
                    >
                        <Check />
                        Aceptar
                    </Button>
                </nav>
            </div>

            <ShowCollaboratorsModal
                isModalActive={isModalActive}
                collaborators={project.collaborators}
                processing={processing}
                roles={PROJECT_COLLABORATOR_ROLE}
                setIsModalActive={setIsModalActive}
                onDeleteCollaborator={handleDeleteCollaborator}
                onAddCollaborator={handleAddCollaborator}
            />
        </AppLayout>
    );
}
