import type { Dispatch, SetStateAction } from 'react';
import { Controller, useForm } from 'react-hook-form';
import InputError from '@/components/ui/app/input-error';
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
import { Label } from '@/components/ui/shadcn/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/shadcn/select';
import type { UserData } from '@/types';

type AddCollaboratorModalProps = {
    userId: UserData['id'];
    isModalActive: boolean;
    roles: Record<string, string>
    processing: boolean;
    setIsModalActive: Dispatch<SetStateAction<boolean>>;
    onAddCollaborator: (userId: UserData['id'], role: string) => void
};

type AddCollaboratorFormData = {
    role: string;
};

export default function AddCollaboratorModal({
    isModalActive,
    userId,
    roles,
    processing,
    onAddCollaborator,
    setIsModalActive,
}: AddCollaboratorModalProps) {
    const initialValues: AddCollaboratorFormData = {
        role: Object.keys(roles)[0],
    };

    const {
        control,
        formState: { errors },
        handleSubmit,
    } = useForm({
        defaultValues: initialValues,
    });

    const onSubmit = ({ role }: AddCollaboratorFormData) => {
        onAddCollaborator(userId, role);
        setIsModalActive(false);
    }

    return (
        <Dialog open={isModalActive} onOpenChange={setIsModalActive}>
            <DialogContent className="sm:max-w-xl">
                <DialogHeader>
                    <DialogTitle>Agregar Colaborador</DialogTitle>
                    <DialogDescription>
                        Aquí puedes agregar un nuevo colaborador
                    </DialogDescription>
                </DialogHeader>

                <form
                    onSubmit={handleSubmit(onSubmit)}
                    className="grid grid-cols-1 gap-6"
                >
                    <div className="grid gap-2">
                        <Label htmlFor="role">Rol: </Label>

                        <Controller
                            name="role"
                            control={control}
                            rules={{ required: 'El rol es requerido' }}
                            render={({ field: { value, onChange } }) => (
                                <Select
                                    value={value?.toString()}
                                    onValueChange={onChange}
                                >
                                    <SelectTrigger>
                                        <SelectValue placeholder="Selecciona un rol" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {Object.entries(roles).map(
                                            ([key, role]) => (
                                                <SelectItem
                                                    key={key}
                                                    value={key.toString()}
                                                >
                                                    {role}
                                                </SelectItem>
                                            ),
                                        )}
                                    </SelectContent>
                                </Select>
                            )}
                        />

                        <InputError message={errors.role?.message} />
                    </div>

                    <DialogFooter>
                        <DialogClose asChild>
                            <Button disabled={processing} variant="outline">
                                Cancelar
                            </Button>
                        </DialogClose>

                        <Button type="submit" disabled={processing}>
                            Confirmar
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    );
}
