import type { Dispatch, SetStateAction } from 'react';
import { Button } from '@/components/ui/shadcn/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/shadcn/dialog';
import type { Collaborator, UserData } from '@/types';
import UserCollaboratorItem from './UserCollaboratorItem';

type ProjectCollaboratorsModalProps = {
    isModalActive: boolean;
    collaborators: Collaborator[];
    processing: boolean;
    roles: Record<string, string>;
    setIsModalActive: Dispatch<SetStateAction<boolean>>;
    onAddCollaborator: (userId: UserData['id'], role: string) => void
    onDeleteCollaborator: (userId: UserData['id']) => void
};

export default function ShowCollaboratorsModal({
    isModalActive,
    collaborators,
    processing,
    roles,
    setIsModalActive,
    onAddCollaborator,
    onDeleteCollaborator,
}: ProjectCollaboratorsModalProps) {
    return (
        <Dialog open={isModalActive} onOpenChange={setIsModalActive}>
            <DialogContent className="sm:max-w-xl">
                <DialogHeader>
                    <DialogTitle>Colaboradores</DialogTitle>
                    <DialogDescription>
                        Aquí puedes gestionar los colaboradores
                    </DialogDescription>
                </DialogHeader>

                {collaborators.length > 0 ? (
                    <div className="grid grid-cols-1 gap-4">
                        {collaborators.map((collaborator) => (
                            <UserCollaboratorItem
                                key={collaborator.id}
                                user={collaborator}
                                collaborators={collaborators}
                                onAddCollaborator={onAddCollaborator}
                                onDeleteCollaborator={onDeleteCollaborator}
                                variant="default"
                                processing={processing}
                                roles={roles}
                            />
                        ))}
                    </div>
                ) : (
                    <p className="my-20 text-center text-accent-foreground">
                        No hay colaboradores
                    </p>
                )}

                <DialogFooter>
                    <DialogClose asChild>
                        <Button variant="outline">Cerrar</Button>
                    </DialogClose>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    );
}
