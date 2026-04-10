import { Link, router } from '@inertiajs/react';
import { HoverCard } from '@radix-ui/react-hover-card';
import { Check, Link2, MoreHorizontalIcon, Pencil, Trash, XIcon } from 'lucide-react';
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
    HoverCardContent,
    HoverCardTrigger,
} from '@/components/ui/shadcn/hover-card';
import {
    Item,
    ItemActions,
    ItemContent,
    ItemDescription,
    ItemHeader,
    ItemTitle
} from '@/components/ui/shadcn/item';
import { cn, formatDateToLocale, getIdealResponsiveMediaLink } from '@/lib/utils';
import events from '@/routes/events';
import type { EventActivity } from '@/types/events';

type ActivityItemProps = {
    activity: EventActivity;
    eventId: number;
};

export default function ActivityItem({ activity, eventId }: ActivityItemProps) {
    const [processing, setProcessing] = useState(false);
    const [isDeleteDialogOpen, setIsDeleteDialogOpen] = useState(false);

    const handleEventStatus = () => {
        setProcessing(true);
        router.patch(
            events.activities.status({ activity: activity.id, event: eventId }),
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

    const handleDeleteActivity = () => {
        setIsDeleteDialogOpen(true);
    };

    const deleteActivity = () => {
        setProcessing(true);
        router.delete(
            events.activities.destroy({
                event: eventId,
                activity: activity.id,
            }),
            {
                preserveScroll: true,
                showProgress: true,
                onError(error) {
                    Object.values(error).forEach((value) =>
                        toast.error(value as string),
                    );
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

    return (
        <>
            <Item variant="outline" className="items-start gap-0 p-0">
                <ItemHeader>
                    <img
                        src={getIdealResponsiveMediaLink(activity.media.at(0))}
                        alt={activity.name}
                        className="block h-auto w-full rounded-t-md rounded-b-none object-cover"
                        width={activity.media.at(0)?.dimensions.main.width}
                        height={activity.media.at(0)?.dimensions.main.height}
                    />
                </ItemHeader>

                <ItemContent className='p-4'>
                    <ItemTitle>
                        <HoverCard>
                            <HoverCardTrigger asChild>
                                <Link
                                    href={events.activities.show({
                                        event: eventId,
                                        activity: activity.id,
                                    })}
                                    className="hover:underline text-base"
                                >
                                    {activity.name}
                                </Link>
                            </HoverCardTrigger>
                            <HoverCardContent className="w-80">
                                <div className="grid gap-3">
                                    <h4 className="flex flex-wrap items-center gap-2 text-lg font-semibold">
                                        {activity.name}

                                        <Badge
                                            className={cn(
                                                activity.is_published
                                                    ? 'bg-green-100 text-green-800'
                                                    : 'bg-red-100 text-red-800',
                                            )}
                                        >
                                            {activity.is_published
                                                ? 'Publicado'
                                                : 'Oculto'}
                                        </Badge>
                                    </h4>

                                    <p className="text-sm">
                                        Del{' '}
                                        {formatDateToLocale(
                                            activity.started_at,
                                        )}{' '}
                                        al{' '}
                                        {formatDateToLocale(activity.ended_at)}
                                    </p>

                                    {activity.is_online ? (
                                        <a
                                            href={activity.online_link || '#'}
                                            className="flex items-center gap-2 text-xs text-muted-foreground transition-colors hover:text-primary hover:underline"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            title={`Ir a ${activity.name}`}
                                        >
                                            <Link2 />
                                            {activity.online_link}
                                        </a>
                                    ) : (
                                        <p className="flex flex-wrap items-center gap-2 text-sm">
                                            Ubicación:
                                            <Badge variant="secondary">
                                                {activity.location}
                                            </Badge>
                                        </p>
                                    )}

                                </div>
                            </HoverCardContent>
                        </HoverCard>
                    </ItemTitle>

                    <ItemDescription>
                        {activity.description.substring(0, 100)}...
                    </ItemDescription>
                </ItemContent>

                <ItemActions className='p-4'>
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
                                Opciones de la actividad
                            </DropdownMenuLabel>

                            <DropdownMenuGroup>
                                <Link
                                    href={events.activities.edit({
                                        event: eventId,
                                        activity: activity.id,
                                    })}
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
                                        activity.is_published
                                            ? 'text-red-300 hover:text-red-400'
                                            : 'text-green-300 hover:text-green-400'
                                    }
                                >
                                    {activity.is_published ? (
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
                                    onClick={handleDeleteActivity}
                                    disabled={processing}
                                    variant='destructive'
                                >
                                    <Trash />
                                    Eliminar
                                </DropdownMenuItem>
                            </DropdownMenuGroup>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </ItemActions>
            </Item >

            <ConfirmDialog
                open={isDeleteDialogOpen}
                onOpenChange={setIsDeleteDialogOpen}
                onConfirm={deleteActivity}
                title="¿Eliminar actividad?"
                description="Esta acción eliminará la actividad de forma permanente."
                confirmLabel="Sí, eliminar"
                confirmDisabled={processing}
            />
        </>
    );
}
