import { MoreHorizontalIcon, UserMinus2, UserPlus2 } from 'lucide-react';
import { useMemo, useState } from 'react';
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
    ItemTitle,
} from '@/components/ui/shadcn/item';
import type { Collaborator, UserData } from '@/types';
import AddCollaboratorModal from './AddCollaboratorModal';

type UserCollaboratorItemProps = {
    user: UserData;
    collaborators: Collaborator[];
    variant?: 'default' | 'outline' | 'muted' | null | undefined;
    processing: boolean;
    roles: Record<string, string>;
    onAddCollaborator: (userId: UserData['id'], role: string) => void
    onDeleteCollaborator: (userId: UserData['id']) => void
};

export default function UserCollaboratorItem({
    user,
    collaborators,
    variant = 'outline',
    processing,
    roles,
    onAddCollaborator,
    onDeleteCollaborator,
}: UserCollaboratorItemProps) {
    const [isModalActive, setIsModalActive] = useState(false);

    const isCollaborator = useMemo(
        () => collaborators.some((collaborator) => collaborator.id === user.id),
        [collaborators, user],
    );

    const userRole = useMemo(
        () =>
            collaborators.find((collaborator) => collaborator.id === user.id)
                ?.pivot.role,
        [collaborators, user],
    );

    const handleCollaborator = () => {
        if (isCollaborator) {
            onDeleteCollaborator(user.id);
        } else {
            setIsModalActive(true);
        }
    };

    return (
        <>
            <Item variant={variant}>
                <ItemContent>
                    <ItemTitle className="flex flex-wrap items-center gap-2">
                        {user.name} {user.father_last_name}{' '}
                        {user.mother_last_name}
                        {isCollaborator && (
                            <Badge variant="secondary">
                                {roles[userRole!]}
                            </Badge>
                        )}
                    </ItemTitle>

                    <ItemDescription>{user.email}</ItemDescription>
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
                                Opciones del Usuario
                            </DropdownMenuLabel>
                            <DropdownMenuGroup>
                                <DropdownMenuItem
                                    disabled={processing}
                                    onSelect={handleCollaborator}
                                    className={
                                        isCollaborator
                                            ? 'text-red-300 hover:text-red-400'
                                            : 'text-green-300 hover:text-green-400'
                                    }
                                >
                                    {isCollaborator ? (
                                        <>
                                            <UserMinus2 />
                                            Eliminar como Colaborador
                                        </>
                                    ) : (
                                        <>
                                            <UserPlus2 />
                                            Añadir como Colaborador
                                        </>
                                    )}
                                </DropdownMenuItem>
                            </DropdownMenuGroup>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </ItemActions>
            </Item>

            <AddCollaboratorModal
                isModalActive={isModalActive}
                userId={user.id}
                roles={roles}
                processing={processing}
                setIsModalActive={setIsModalActive}
                onAddCollaborator={onAddCollaborator}
            />
        </>
    );
}
