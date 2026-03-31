import { Link, router } from '@inertiajs/react';
import {
    Check,
    GitBranch,
    Link2,
    MoreHorizontalIcon,
    Pencil,
    Trash,
    XIcon,
} from 'lucide-react';
import { useState } from 'react';
import { toast } from 'sonner';
import ConfirmDialog from '@/components/ui/app/confirm-dialog';
import { Badge } from '@/components/ui/shadcn/badge';
import { Button } from '@/components/ui/shadcn/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuTrigger,
} from '@/components/ui/shadcn/dropdown-menu';
import {
    HoverCard,
    HoverCardContent,
    HoverCardTrigger,
} from '@/components/ui/shadcn/hover-card';
import {
    Item,
    ItemActions,
    ItemContent,
    ItemDescription,
    ItemHeader,
    ItemTitle,
} from '@/components/ui/shadcn/item';
import { getIdealResponsiveMediaLink, cn } from '@/lib/utils';
import projects from '@/routes/projects';
import type { Project } from '@/types/projects';

type ProjectItemProps = {
    project: Project;
};

export default function ProjectItem({ project }: ProjectItemProps) {
    const [processing, setProcessing] = useState(false);
    const [isDeleteDialogOpen, setIsDeleteDialogOpen] = useState(false);
    const coverImage = project.media.at(0);

    const handleProjectStatus = () => {
        setProcessing(true);
        router.patch(
            projects.status(project.id),
            {},
            {
                preserveScroll: true,
                showProgress: true,
                onError(error) {
                    Object.values(error).forEach((value) => toast.error(value));
                },
                onFinish() {
                    setProcessing(false);
                },
                onSuccess(data) {
                    toast.success(data.props.message as string);
                },
            },
        );
    };

    const deleteProject = () => {
        router.delete(projects.destroy(project.id), {
            preserveScroll: true,
            showProgress: true,
            onError(error) {
                Object.values(error).forEach((value) => toast.error(value));
            },
            onFinish() {
                setProcessing(false);
            },
            onSuccess(data) {
                toast.success(data.props.message as string);
            },
        });
    };

    const handleDeleteProject = () => {
        setIsDeleteDialogOpen(true);
    };

    return (
        <>
            <Item variant="outline" className="items-start gap-0 p-0">
                <ItemHeader>
                    {coverImage ? (
                        <img
                            src={getIdealResponsiveMediaLink(coverImage)}
                            alt={project.name}
                            className="block h-auto w-full rounded-t-md rounded-b-none object-cover"
                            width={coverImage.dimensions.screenshot.width}
                            height={coverImage.dimensions.screenshot.height}
                        />
                    ) : (
                        <div className="h-40 w-full rounded-t-md rounded-b-none bg-muted" />
                    )}
                </ItemHeader>

                <ItemContent className="p-4">
                    <ItemTitle>
                        <HoverCard>
                            <HoverCardTrigger asChild>
                                <Link
                                    href={projects.show(project.id)}
                                    className="text-base hover:underline"
                                >
                                    {project.name}
                                </Link>
                            </HoverCardTrigger>
                            <HoverCardContent className="w-80">
                                <div className="grid gap-3">
                                    <h4 className="flex flex-wrap items-center gap-2 text-lg font-semibold">
                                        {project.name}

                                        <Badge
                                            className={cn(
                                                project.is_published
                                                    ? 'bg-green-100 text-green-800'
                                                    : 'bg-red-100 text-red-800',
                                            )}
                                        >
                                            {project.is_published
                                                ? 'Publicado'
                                                : 'Oculto'}
                                        </Badge>
                                    </h4>

                                    <div className="grid gap-1">
                                        <a
                                            href={project.repository_url}
                                            className="flex items-center gap-2 text-xs text-muted-foreground transition-colors hover:text-primary hover:underline"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            title={`Ir al repositorio de ${project.name}`}
                                        >
                                            <GitBranch />
                                            {project.repository_url}
                                        </a>

                                        <a
                                            href={project.demo_url}
                                            className="flex items-center gap-2 text-xs text-muted-foreground transition-colors hover:text-primary hover:underline"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            title={`Ir a ${project.name}`}
                                        >
                                            <Link2 />
                                            {project.demo_url}
                                        </a>
                                    </div>

                                    <p className="flex flex-wrap items-center gap-2 text-sm font-semibold">
                                        Licencia:
                                        <Badge variant="outline">
                                            {project.license}
                                        </Badge>
                                    </p>
                                    <p className="flex flex-wrap items-center gap-2 text-sm font-semibold">
                                        Versión:
                                        <Badge variant="outline">
                                            {project.version}
                                        </Badge>
                                    </p>
                                    <p className="flex flex-wrap items-center gap-2 text-sm font-semibold">
                                        Stack:
                                        {project.tech_stack.map((item) => (
                                            <Badge key={item} variant="default">
                                                {item}
                                            </Badge>
                                        ))}
                                    </p>
                                </div>
                            </HoverCardContent>
                        </HoverCard>
                    </ItemTitle>

                    <ItemDescription>
                        {project.summary.substring(0, 100)}...
                    </ItemDescription>
                </ItemContent>

                <ItemActions className="p-4">
                    <DropdownMenu modal={false}>
                        <DropdownMenuTrigger asChild>
                            <Button
                                variant="ghost"
                                aria-label="Open menu"
                                size="icon"
                            >
                                <MoreHorizontalIcon />
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent className="w-40" align="end">
                            <DropdownMenuLabel>
                                Opciones del project
                            </DropdownMenuLabel>
                            <DropdownMenuGroup>
                                <Link href={projects.edit(project.id)}>
                                    <DropdownMenuItem>
                                        <Pencil />
                                        Editar
                                    </DropdownMenuItem>
                                </Link>

                                <DropdownMenuItem
                                    onClick={handleProjectStatus}
                                    disabled={processing}
                                    variant={
                                        project.is_published
                                            ? 'destructive'
                                            : 'default'
                                    }
                                >
                                    {project.is_published ? (
                                        <>
                                            <XIcon />
                                            Ocultar
                                        </>
                                    ) : (
                                        <>
                                            <Check />
                                            Publicar
                                        </>
                                    )}
                                </DropdownMenuItem>

                                <DropdownMenuItem
                                    onClick={handleDeleteProject}
                                    disabled={processing}
                                    className="text-red-300 hover:text-red-400"
                                >
                                    <Trash />
                                    Eliminar
                                </DropdownMenuItem>
                            </DropdownMenuGroup>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </ItemActions>
            </Item>

            <ConfirmDialog
                open={isDeleteDialogOpen}
                onOpenChange={setIsDeleteDialogOpen}
                onConfirm={deleteProject}
                title="¿Eliminar proyecto?"
                description="Esta acción eliminará el proyecto de forma permanente."
                confirmLabel="Sí, eliminar"
                confirmDisabled={processing}
            />
        </>
    );
}
