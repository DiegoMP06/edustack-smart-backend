import { Link, router } from '@inertiajs/react';
import { HoverCard } from '@radix-ui/react-hover-card';
import { Check, MoreHorizontalIcon, Pencil, Trash, XIcon } from 'lucide-react';
import { useState } from 'react';
import { toast } from 'sonner';
import ConfirmDialog from '@/components/ui/app/confirm-dialog';
import {
    Avatar,
    AvatarFallback,
    AvatarImage,
} from '@/components/ui/shadcn/avatar';
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
    HoverCardContent,
    HoverCardTrigger,
} from '@/components/ui/shadcn/hover-card';
import {
    Item,
    ItemActions,
    ItemContent,
    ItemDescription,
    ItemMedia,
    ItemTitle,
} from '@/components/ui/shadcn/item';
import {
    formatDateToLocale,
    formatCurrency,
    getIdealResponsiveMediaLink,
    cn,
} from '@/lib/utils';
import events from '@/routes/events';
import type { Event } from '@/types';

type EventItemProps = {
    event: Event;
};

export default function EventItem({ event }: EventItemProps) {
    const [processing, setProcessing] = useState(false);
    const [isDeleteDialogOpen, setIsDeleteDialogOpen] = useState(false);
    const handleEventStatus = () => {
        setProcessing(true);
        router.patch(
            events.status(event.id),
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

    const handleDeleteEvent = () => {
        setIsDeleteDialogOpen(true);
    };

    const deleteEvent = () => {
        setProcessing(true);
        router.delete(events.destroy(event.id), {
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
            <Item variant="outline">
                <ItemMedia>
                    <Avatar className="size-10">
                        <AvatarImage
                            src={getIdealResponsiveMediaLink(
                                event.media.at(0)!,
                            )}
                        />
                        <AvatarFallback>
                            {event.name.substring(0, 1)}
                        </AvatarFallback>
                    </Avatar>
                </ItemMedia>

                <ItemContent>
                    <ItemTitle>
                        <HoverCard>
                            <HoverCardTrigger asChild>
                                <Link
                                    href={events.show(event.id)}
                                    className="hover:underline"
                                >
                                    {event.name}
                                </Link>
                            </HoverCardTrigger>
                            <HoverCardContent className="w-80">
                                <div className="grid gap-3">
                                    <h4 className="flex flex-wrap items-center gap-2 text-lg font-semibold">
                                        {event.name}

                                        <span
                                            className={cn(
                                                'rounded px-2 py-1 text-xs font-bold',
                                                event.is_published
                                                    ? 'bg-green-100 text-green-800'
                                                    : 'bg-red-100 text-red-800',
                                            )}
                                        >
                                            {event.is_published
                                                ? 'Publicado'
                                                : 'Oculto'}
                                        </span>
                                    </h4>

                                    <p className="text-sm">
                                        Del{' '}
                                        {formatDateToLocale(event.start_date)}{' '}
                                        al {formatDateToLocale(event.end_date)}
                                    </p>

                                    <p className="flex flex-wrap items-center gap-2 text-sm">
                                        Precio:
                                        <span className="font-bold text-foreground">
                                            {event.price === 0
                                                ? 'Gratis'
                                                : formatCurrency(
                                                      event.price *
                                                          (1 -
                                                              event.percent_off /
                                                                  100),
                                                  )}
                                        </span>
                                    </p>

                                    <p className="flex flex-wrap items-center gap-2 text-sm">
                                        Ubicación:
                                        <span className="font-bold text-foreground">
                                            {event.location}
                                        </span>
                                    </p>
                                </div>
                            </HoverCardContent>
                        </HoverCard>
                    </ItemTitle>

                    <ItemDescription>
                        {event.description.substring(0, 100)}...
                    </ItemDescription>
                </ItemContent>

                <ItemActions>
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
                                Opciones del event
                            </DropdownMenuLabel>
                            <DropdownMenuGroup>
                                <Link
                                    href={events.edit(event.id)}
                                    disabled={processing}
                                >
                                    <DropdownMenuItem>
                                        <Pencil />
                                        Editar
                                    </DropdownMenuItem>
                                </Link>

                                <DropdownMenuItem
                                    onClick={handleEventStatus}
                                    disabled={processing}
                                    className={
                                        event.is_published
                                            ? 'text-red-300 hover:text-red-400'
                                            : 'text-green-300 hover:text-green-400'
                                    }
                                >
                                    {event.is_published ? (
                                        <>
                                            <XIcon className="fill-red-300" />
                                            Ocultar
                                        </>
                                    ) : (
                                        <>
                                            <Check className="fill-green-300" />
                                            Publicar
                                        </>
                                    )}
                                </DropdownMenuItem>

                                <DropdownMenuItem
                                    onClick={handleDeleteEvent}
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
                onConfirm={deleteEvent}
                title="¿Eliminar evento?"
                description="Esta acción eliminará el evento de forma permanente."
                confirmLabel="Sí, eliminar"
                confirmDisabled={processing}
            />
        </>
    );
}
