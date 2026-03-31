import type { Control, FieldErrors, UseFormRegister } from 'react-hook-form';
import { Controller } from 'react-hook-form';
import InputError from '@/components/ui/app/input-error';
import { Input } from '@/components/ui/shadcn/input';
import { Label } from '@/components/ui/shadcn/label';
import { Switch } from '@/components/ui/shadcn/switch';
import type { EventRoundFormData } from '@/types/events';

type RoundFormProps = {
    control: Control<EventRoundFormData>;
    register: UseFormRegister<EventRoundFormData>;
    errors: FieldErrors<EventRoundFormData>;
};

function toDatetimeLocal(value: Date): string {
    const year = value.getFullYear();
    const month = `${value.getMonth() + 1}`.padStart(2, '0');
    const day = `${value.getDate()}`.padStart(2, '0');
    const hours = `${value.getHours()}`.padStart(2, '0');
    const minutes = `${value.getMinutes()}`.padStart(2, '0');

    return `${year}-${month}-${day}T${hours}:${minutes}`;
}

export default function RoundForm({
    control,
    register,
    errors,
}: RoundFormProps) {
    return (
        <>
            <div className="grid gap-2">
                <Label htmlFor="name">Nombre:</Label>
                <Input
                    {...register('name', {
                        required: 'El nombre es requerido',
                    })}
                    id="name"
                    placeholder="Nombre de la ronda"
                />
                <InputError message={errors.name?.message as string} />
            </div>

            <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div className="grid gap-2">
                    <Label htmlFor="started_at">Fecha de inicio:</Label>
                    <Controller
                        name="started_at"
                        control={control}
                        render={({ field: { value, onChange } }) => (
                            <Input
                                id="started_at"
                                type="datetime-local"
                                value={toDatetimeLocal(value)}
                                onChange={(event) =>
                                    onChange(new Date(event.target.value))
                                }
                            />
                        )}
                    />
                    <InputError
                        message={errors.started_at?.message as string}
                    />
                </div>

                <div className="grid gap-2">
                    <Label htmlFor="ended_at">Fecha de fin:</Label>
                    <Controller
                        name="ended_at"
                        control={control}
                        render={({ field: { value, onChange } }) => (
                            <Input
                                id="ended_at"
                                type="datetime-local"
                                value={toDatetimeLocal(value)}
                                onChange={(event) =>
                                    onChange(new Date(event.target.value))
                                }
                            />
                        )}
                    />
                    <InputError message={errors.ended_at?.message as string} />
                </div>
            </div>

            <div className="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div className="grid gap-2">
                    <Label htmlFor="participants_per_round">
                        Participantes por ronda:
                    </Label>
                    <Input
                        {...register('participants_per_round', {
                            valueAsNumber: true,
                        })}
                        id="participants_per_round"
                        type="number"
                        min={2}
                    />
                </div>

                <div className="grid gap-2">
                    <Label htmlFor="qualified_participants">
                        Clasificados:
                    </Label>
                    <Input
                        {...register('qualified_participants', {
                            required: 'Este valor es requerido',
                            valueAsNumber: true,
                        })}
                        id="qualified_participants"
                        type="number"
                        min={1}
                    />
                </div>

                <div className="grid gap-2">
                    <Label htmlFor="winners_count">Ganadores:</Label>
                    <Input
                        {...register('winners_count', {
                            required: 'Este valor es requerido',
                            valueAsNumber: true,
                        })}
                        id="winners_count"
                        type="number"
                        min={1}
                    />
                </div>
            </div>

            <div className="flex items-center gap-8">
                <div className="flex items-center gap-2">
                    <Controller
                        name="starting_from_scratch"
                        control={control}
                        render={({ field: { value, onChange } }) => (
                            <Switch
                                id="starting_from_scratch"
                                checked={value}
                                onCheckedChange={onChange}
                            />
                        )}
                    />
                    <Label htmlFor="starting_from_scratch">
                        Inicia desde cero
                    </Label>
                </div>

                <div className="flex items-center gap-2">
                    <Controller
                        name="is_the_final"
                        control={control}
                        render={({ field: { value, onChange } }) => (
                            <Switch
                                id="is_the_final"
                                checked={value}
                                onCheckedChange={onChange}
                            />
                        )}
                    />
                    <Label htmlFor="is_the_final">Es final</Label>
                </div>
            </div>
        </>
    );
}
