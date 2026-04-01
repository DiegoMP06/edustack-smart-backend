import { Link, router } from '@inertiajs/react';
import { Check, MoreHorizontalIcon, Pencil, Trash, XIcon } from 'lucide-react';
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
    Item,
    ItemActions,
    ItemContent,
    ItemDescription,
    ItemHeader,
    ItemTitle,
} from '@/components/ui/shadcn/item';
import { getIdealResponsiveMediaLink } from '@/lib/utils';
import type { Course } from '@/types/classroom';

type CourseItemProps = {
    course: Course;
};

export default function CourseItem({ course }: CourseItemProps) {
    const [processing, setProcessing] = useState(false);
    const [isDeleteDialogOpen, setIsDeleteDialogOpen] = useState(false);
    const coverImage = course.media?.at(0);

    const handleStatus = () => {
        setProcessing(true);
        router.patch(
            `/classroom/courses/${course.id}/status`,
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

    const deleteCourse = () => {
        setProcessing(true);
        router.delete(`/classroom/courses/${course.id}`, {
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

    return (
        <>
            <Item variant="outline" className="items-start gap-0 p-0">
                <ItemHeader>
                    {coverImage ? (
                        <img
                            src={getIdealResponsiveMediaLink(coverImage)}
                            alt={course.name}
                            className="block h-40 w-full rounded-t-md rounded-b-none object-cover"
                        />
                    ) : (
                        <div className="h-40 w-full rounded-t-md rounded-b-none bg-muted" />
                    )}
                </ItemHeader>

                <ItemContent className="p-4">
                    <ItemTitle>
                        <Link
                            href={`/classroom/courses/${course.id}`}
                            className="hover:underline"
                        >
                            {course.name}
                        </Link>
                    </ItemTitle>
                    <ItemDescription>
                        {course.summary.slice(0, 100)}...
                    </ItemDescription>

                    <div className="mt-3 flex flex-wrap items-center gap-2">
                        {course.status ? (
                            <Badge variant="secondary">
                                {course.status.name}
                            </Badge>
                        ) : null}
                        {course.category ? (
                            <Badge variant="outline">
                                {course.category.name}
                            </Badge>
                        ) : null}
                        <Badge
                            variant={
                                course.is_published ? 'default' : 'destructive'
                            }
                        >
                            {course.is_published ? 'Publicado' : 'Oculto'}
                        </Badge>
                    </div>
                </ItemContent>

                <ItemActions className="p-4">
                    <DropdownMenu modal={false}>
                        <DropdownMenuTrigger asChild>
                            <Button variant="ghost" size="icon">
                                <MoreHorizontalIcon />
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end" className="w-44">
                            <DropdownMenuLabel>
                                Opciones del curso
                            </DropdownMenuLabel>
                            <DropdownMenuGroup>
                                <DropdownMenuItem asChild disabled={processing}>
                                    <Link
                                        href={`/classroom/courses/${course.id}/edit`}
                                    >
                                        <Pencil />
                                        Editar
                                    </Link>
                                </DropdownMenuItem>
                                <DropdownMenuItem
                                    onClick={handleStatus}
                                    disabled={processing}
                                >
                                    {course.is_published ? (
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
                                    onClick={() => setIsDeleteDialogOpen(true)}
                                    disabled={processing}
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
                onConfirm={deleteCourse}
                title="¿Eliminar curso?"
                description="Esta accion eliminara el curso de forma permanente."
                confirmLabel="Si, eliminar"
                confirmDisabled={processing}
            />
        </>
    );
}
