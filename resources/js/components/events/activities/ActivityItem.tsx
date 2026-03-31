import { Link, router } from '@inertiajs/react';
import { HoverCard } from '@radix-ui/react-hover-card';
import { MoreHorizontalIcon, Pencil, Trash } from 'lucide-react';
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
    cn,
} from '@/lib/utils';
import events from '@/routes/events';
import type { EventActivity } from '@/types/events';

type ActivityItemProps = {
    activity: EventActivity;
    eventId: number;
};

export default function ActivityItem({ activity, eventId }: ActivityItemProps) {
    const [processing, setProcessing] = useState(false);
    const [isDeleteDialogOpen, setIsDeleteDialogOpen] = useState(false);

    const handleDeleteActivity = () => {
        setIsDeleteDialogOpen(true);
    };

    const deleteActivity = () => {
        setProcessing(true);
        router.delete(events.activities.destroy({ event: eventId, activity: activity.id }), {
            preserveScroll: true,
            showProgress: true,
            onError(error) {
                Object.values(error).forEach((value) => toast.error(value as string));
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
                            src={activity.image ?? undefined}
                        />
                        <AvatarFallback>
                            {activity.name.substring(0, 1)}
                        </AvatarFallback>
                    </Avatar>
                </ItemMedia>

                <ItemContent>
                    <ItemTitle>
                        <HoverCard>
                            <HoverCardTrigger asChild>
                                <Link
                                    href={events.activities.show({ event: eventId, activity: activity.id })}
                                    className="hover:underline"
                                >
                                    {activity.name}
                                </Link>
                            </HoverCardTrigger>
                            <HoverCardContent className="w-80">
                                <div className="grid gap-3">
                                    <h4 className="flex flex-wrap items-center gap-2 text-lg font-semibold">
                                        {activity.name}

                                        <span
                                            className={cn(
                                                'rounded px-2 py-1 text-xs font-bold',
                                                activity.is_published
                                                    ? 'bg-green-100 text-green-800'
                                                    : 'bg-red-100 text-red-800',
                                            )}
                                        >
                                            {activity.is_published
                                                ? 'Publicado'
                                                : 'Oculto'}
                                        </span>
                                    </h4>

                                    <p className="text-sm">
                                        Del{' '}
                                        {formatDateToLocale(activity.started_at)}{' '}
                                        al {formatDateToLocale(activity.ended_at)}
                                    </p>

                                    <p className="flex flex-wrap items-center gap-2 text-sm">
                                        Ubicación:
                                        <span className="font-bold text-foreground">
                                            {activity.location}
                                        </span>
                                    </p>
                                </div>
                            </HoverCardContent>
                        </HoverCard>
                    </ItemTitle>

                    <ItemDescription>
                        {activity.summary.substring(0, 100)}...
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
                                Opciones de la actividad
                            </DropdownMenuLabel>
                            <DropdownMenuGroup>
                                <Link
                                    href={events.activities.edit({ event: eventId, activity: activity.id })}
                                    disabled={processing}
                                >
                                    <DropdownMenuItem>
                                        <Pencil />
                                        Editar
                                    </DropdownMenuItem>
                                </Link>

                                <DropdownMenuItem
                                    onClick={handleDeleteActivity}
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
                onConfirm={deleteActivity}
                title="¿Eliminar actividad?"
                description="Esta acción eliminará la actividad de forma permanente."
                confirmLabel="Sí, eliminar"
                confirmDisabled={processing}
            />
        </>
    );
}
