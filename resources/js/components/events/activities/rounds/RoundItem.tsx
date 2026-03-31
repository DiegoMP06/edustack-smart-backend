import { Link, router } from '@inertiajs/react';
import { MoreHorizontalIcon, Pencil, Trash } from 'lucide-react';
import { useState } from 'react';
import { toast } from 'sonner';
import ConfirmDialog from '@/components/ui/app/confirm-dialog';
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
    ItemTitle,
} from '@/components/ui/shadcn/item';
import { formatDateToLocale } from '@/lib/utils';
import events from '@/routes/events';
import type { EventRound } from '@/types/events';

type RoundItemProps = {
    round: EventRound;
    eventId: number;
    activityId: number;
};

export default function RoundItem({
    round,
    eventId,
    activityId,
}: RoundItemProps) {
    const [processing, setProcessing] = useState(false);
    const [isDeleteDialogOpen, setIsDeleteDialogOpen] = useState(false);

    const handleDeleteRound = () => {
        setIsDeleteDialogOpen(true);
    };

    const deleteRound = () => {
        setProcessing(true);
        router.delete(
            events.activities.rounds.destroy({
                event: eventId,
                activity: activityId,
                round: round.id,
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
            <Item variant="outline">
                <ItemContent>
                    <ItemTitle>
                        <Link
                            href={events.activities.rounds.show({
                                event: eventId,
                                activity: activityId,
                                round: round.id,
                            })}
                            className="hover:underline"
                        >
                            Ronda #{round.round_number}: {round.name}
                        </Link>
                    </ItemTitle>

                    <ItemDescription>
                        <div className="mt-2 text-xs text-muted-foreground">
                            Inicio: {formatDateToLocale(round.started_at)} |
                            Fin: {formatDateToLocale(round.ended_at)}
                        </div>
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
                                Opciones de la ronda
                            </DropdownMenuLabel>
                            <DropdownMenuGroup>
                                <Link
                                    href={events.activities.rounds.edit({
                                        event: eventId,
                                        activity: activityId,
                                        round: round.id,
                                    })}
                                    disabled={processing}
                                >
                                    <DropdownMenuItem>
                                        <Pencil />
                                        Editar
                                    </DropdownMenuItem>
                                </Link>

                                <DropdownMenuItem
                                    onClick={handleDeleteRound}
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
                onConfirm={deleteRound}
                title="¿Eliminar ronda?"
                description="Esta acción eliminará la ronda de forma permanente."
                confirmLabel="Sí, eliminar"
                confirmDisabled={processing}
            />
        </>
    );
}
