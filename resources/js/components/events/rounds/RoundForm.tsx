import type { Control, UseFormRegister } from 'react-hook-form';
import { Controller, useFormState, useWatch } from 'react-hook-form';
import InputError from '@/components/ui/app/input-error';
import { Input } from '@/components/ui/shadcn/input';
import { Label } from '@/components/ui/shadcn/label';
import { Switch } from '@/components/ui/shadcn/switch';
import { Textarea } from '@/components/ui/shadcn/textarea';
import { toDatetimeLocal } from '@/lib/utils';
import type { CompetitionRoundFormData } from '@/types/events';

type RoundFormProps = {
    control: Control<CompetitionRoundFormData>;
    register: UseFormRegister<CompetitionRoundFormData>;
};

export default function RoundForm({
    control,
    register,
}: RoundFormProps) {
    const { errors } = useFormState({ control })
    const perParts = useWatch({ control, name: 'per_parts' })
    const isTheFinal = useWatch({ control, name: 'is_the_final' })
    const winnersCount = useWatch({ control, name: 'winners_count' })

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

            <div className="grid gap-2">
                <Label htmlFor="description">Resumen:</Label>

                <Textarea
                    {...register('description', {
                        required: 'El resumen es requerido',
                        minLength: {
                            value: 50,
                            message:
                                'El resumen debe tener al menos 100 caracteres',
                        },
                    })}
                    id="description"
                    placeholder="Resumen del Evento"
                    className="h-60"
                />

                <InputError message={errors.description?.message} />
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

            <div className="grid gap-2">
                <Label htmlFor="winners_count">Ganadores:</Label>
                <Input
                    {...register('winners_count', {
                        required: 'Este valor es requerido',
                        min: {
                            value: 1,
                            message: 'El valor mínimo es 1',
                        },
                        valueAsNumber: true,
                    })}
                    id="winners_count"
                    type="number"
                    min={1}
                />
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
                <Label htmlFor="is_the_final">Es la ronda Final</Label>
            </div>

            <div className="grid gap-2">
                <Label htmlFor="qualified_participants">
                    {isTheFinal ?
                        'Participantes ganadores' :
                        'Participantes calificados'
                    }
                </Label>

                <Input
                    {...register('qualified_participants', {
                        required: 'Este valor es requerido',
                        min: {
                            value: winnersCount,
                            message: `El valor mínimo es ${winnersCount}`,
                        },
                        valueAsNumber: true,
                    })}
                    id="qualified_participants"
                    type="number"
                    min={winnersCount}
                />
            </div>

            <div className="flex items-center gap-2">
                <Controller
                    name="per_parts"
                    control={control}
                    render={({ field: { value, onChange } }) => (
                        <Switch
                            id="per_parts"
                            checked={value}
                            onCheckedChange={onChange}
                        />
                    )}
                />
                <Label htmlFor="per_parts">
                    Dividir ronda en partes
                </Label>
            </div>

            {perParts && (
                <>
                    <div className="grid gap-2">
                        <Label htmlFor="participants_per_round">
                            Participantes por parte:
                        </Label>

                        <Input
                            {...register('participants_per_round', {
                                required: 'Este valor es requerido',
                                min: {
                                    value: 2,
                                    message: 'El valor mínimo es 2',
                                },
                                valueAsNumber: true,
                            })}
                            id="participants_per_round"
                            type="number"
                            min={2}
                        />
                    </div>

                    <div className="flex items-center gap-2">
                        <Controller
                            name="rate_by_part"
                            control={control}
                            render={({ field: { value, onChange } }) => (
                                <Switch
                                    id="rate_by_part"
                                    checked={value}
                                    onCheckedChange={onChange}
                                />
                            )}
                        />
                        <Label htmlFor="rate_by_part">
                            Clasificar por partes de la ronda, no por ronda completa
                        </Label>
                    </div>
                </>
            )}

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
                    Inicia desde cero puntos
                </Label>
            </div>
        </>
    );
}
