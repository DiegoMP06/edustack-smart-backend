import { Link } from '@inertiajs/react';
import {
    Check,
    FileText,
    MoreHorizontalIcon,
    Pencil,
    Trash,
    X,
} from 'lucide-react';
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
    ItemMedia,
    ItemTitle,
} from '@/components/ui/shadcn/item';
import forms from '@/routes/forms';
import type { Form } from '@/types/forms';

type FormItemProps = {
    form: Form;
    processing: boolean;
    onStatus: (form: Form) => void;
    onDelete: (form: Form) => void;
};

export default function FormItem({
    form,
    processing,
    onStatus,
    onDelete,
}: FormItemProps) {
    return (
        <Item variant="outline" className="items-start">
            <ItemHeader>
                <ItemMedia>
                    <div className="rounded-md bg-muted p-2">
                        <FileText className="size-5" />
                    </div>
                </ItemMedia>
            </ItemHeader>

            <ItemContent>
                <ItemTitle>
                    <Link
                        href={forms.show(form.id)}
                        className="hover:underline"
                    >
                        {form.title}
                    </Link>
                </ItemTitle>

                <ItemDescription>
                    {form.description?.substring(0, 90) || 'Sin descripcion'}
                </ItemDescription>

                <div className="mt-3 flex flex-wrap gap-2">
                    <Badge variant="outline">
                        {form.type?.name || form.form_type?.name || 'Tipo'}
                    </Badge>
                    <Badge
                        className={
                            form.is_published
                                ? 'bg-green-100 text-green-800'
                                : 'bg-red-100 text-red-800'
                        }
                    >
                        {form.is_published ? 'Publicado' : 'Oculto'}
                    </Badge>
                </div>
            </ItemContent>

            <ItemActions>
                <DropdownMenu modal={false}>
                    <DropdownMenuTrigger asChild>
                        <Button
                            variant="ghost"
                            size="icon"
                            aria-label="Opciones de formulario"
                        >
                            <MoreHorizontalIcon />
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end" className="w-44">
                        <DropdownMenuLabel>Opciones</DropdownMenuLabel>
                        <DropdownMenuGroup>
                            <DropdownMenuItem asChild disabled={processing}>
                                <Link href={forms.edit(form.id)}>
                                    <Pencil />
                                    Editar
                                </Link>
                            </DropdownMenuItem>

                            <DropdownMenuItem
                                onClick={() => onStatus(form)}
                                disabled={processing}
                                variant={
                                    form.is_published
                                        ? 'destructive'
                                        : 'default'
                                }
                            >
                                {form.is_published ? (
                                    <>
                                        <X />
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
                                onClick={() => onDelete(form)}
                                disabled={processing}
                                variant="destructive"
                            >
                                <Trash />
                                Eliminar
                            </DropdownMenuItem>
                        </DropdownMenuGroup>
                    </DropdownMenuContent>
                </DropdownMenu>
            </ItemActions>
        </Item>
    );
}
