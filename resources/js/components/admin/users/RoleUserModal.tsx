import { router } from '@inertiajs/react';
import type { Dispatch, SetStateAction } from 'react';
import { useState } from 'react';
import type { SubmitHandler } from 'react-hook-form';
import { Controller, useForm } from 'react-hook-form';
import { toast } from 'sonner';
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
import { ROLES } from '@/consts/roles';
import users from '@/routes/admin/users';
import type { ChangeRoleForm, Role, User } from '@/types';

type RoleUserModalProps = {
    isModalActive: boolean;
    setIsModalActive: Dispatch<SetStateAction<boolean>>;
    roles: Role[];
    currentRole: Role['name'];
    userId: User['id'];
};

export default function RoleUserModal({
    isModalActive,
    setIsModalActive,
    roles,
    currentRole,
    userId,
}: RoleUserModalProps) {
    const [processing, setProcessing] = useState(false);
    const initialValues: ChangeRoleForm = {
        role: currentRole,
    };

    const {
        control,
        formState: { errors },
        handleSubmit,
    } = useForm({
        defaultValues: initialValues,
    });

    const handleChangeRole: SubmitHandler<ChangeRoleForm> = (data) => {
        setProcessing(true);
        router.patch(users.role(userId), data, {
            forceFormData: false,
            preserveScroll: true,
            showProgress: true,
            onFinish() {
                setProcessing(false);
            },
            onError(error) {
                Object.values(error).forEach((value) => toast.error(value));
            },
            onSuccess(data) {
                toast.success(data.props.message as string);
                setIsModalActive(false);
            },
        });
    };

    return (
        <Dialog open={isModalActive} onOpenChange={setIsModalActive}>
            <DialogContent className="sm:max-w-xl">
                <DialogHeader>
                    <DialogTitle>Cambiar de Rol al Usuario</DialogTitle>
                    <DialogDescription>
                        Cambia de rol a los usuarios que estén registrados.
                        Cuando se cambia el rol de un usuario, se le notificará
                        al usuario.
                    </DialogDescription>
                </DialogHeader>

                <form
                    onSubmit={handleSubmit(handleChangeRole)}
                    className="grid grid-cols-1 gap-6"
                >
                    <div className="grid gap-2">
                        <Label htmlFor="role">Rol: </Label>

                        <Controller
                            name="role"
                            control={control}
                            render={({ field: { value, onChange } }) => (
                                <Select
                                    value={value.toString()}
                                    onValueChange={onChange}
                                >
                                    <SelectTrigger>
                                        <SelectValue placeholder="Rol de Usuario" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {roles.map((role) => (
                                            <SelectItem
                                                key={role.id}
                                                value={role.name}
                                            >
                                                {ROLES[role.name]}
                                            </SelectItem>
                                        ))}
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
