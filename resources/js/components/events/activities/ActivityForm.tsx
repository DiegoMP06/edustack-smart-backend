import { format } from 'date-fns';
import { CalendarIcon } from 'lucide-react';
import type {
    Control,
    UseFormRegister,
} from 'react-hook-form';
import { useFormState, useWatch } from 'react-hook-form';
import { Controller } from 'react-hook-form';
import LocationMap from '@/components/leaflet/LocationMap';
import InputError from '@/components/ui/app/input-error';
import { Button } from '@/components/ui/shadcn/button';
import { Calendar } from '@/components/ui/shadcn/calendar';
import { Checkbox } from '@/components/ui/shadcn/checkbox';
import { Input } from '@/components/ui/shadcn/input';
import { Label } from '@/components/ui/shadcn/label';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/shadcn/popover';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/shadcn/select';
import { Switch } from '@/components/ui/shadcn/switch';
import { Textarea } from '@/components/ui/shadcn/textarea';
import { applyTimeToDate, cn, formatTime, applyDateKeepingTime } from '@/lib/utils';
import type { DifficultyLevel, EventActivityCategory, EventActivityFormData, EventActivityType } from '@/types/events';
import ActivitySpeakersInput from './ActivitySpeakersInput';

type ActivityFormProps = {
    control: Control<EventActivityFormData>;
    register: UseFormRegister<EventActivityFormData>;
    difficultyLevels: DifficultyLevel[];
    activityTypes: EventActivityType[];
    categories: EventActivityCategory[];
};

export default function ActivityForm({
    control,
    register,
    difficultyLevels,
    activityTypes,
    categories,
}: ActivityFormProps) {
    const has_teams = useWatch({ control, name: 'has_teams' });
    const is_free = useWatch({ control, name: 'is_free' });
    const is_online = useWatch({ control, name: 'is_online' });
    const with_capacity = useWatch({ control, name: 'with_capacity' });
    const max_team_size = useWatch({ control, name: 'max_team_size' });
    const { errors } = useFormState({ control })

    return (
        <>
            <div className="grid gap-2">
                <Label htmlFor="name">Nombre:</Label>
                <Input
                    {...register('name', {
                        required: 'El nombre es requerido',
                        minLength: {
                            value: 3,
                            message:
                                'El nombre debe tener al menos 3 caracteres',
                        },
                    })}
                    id="name"
                    placeholder="Nombre de la actividad"
                />
                <InputError message={errors.name?.message} />
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
                    placeholder="Resumen de la actividad"
                    className="h-60"
                />

                <InputError message={errors.description?.message} />
            </div>

            <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div className="grid gap-2">
                    <Label htmlFor="registration_started_at">Fecha de Inicio del Registro:</Label>

                    <Controller
                        name="registration_started_at"
                        control={control}
                        rules={{ required: 'La fecha de inicio es requerida' }}
                        render={({ field: { value, onChange } }) => (
                            <div className="space-y-2">
                                <Popover>
                                    <PopoverTrigger asChild>
                                        <Button
                                            type="button"
                                            variant="outline"
                                            className={cn(
                                                'w-full justify-start text-left font-normal',
                                                !value &&
                                                'text-muted-foreground',
                                            )}
                                        >
                                            <CalendarIcon className="mr-2 h-4 w-4" />
                                            {value
                                                ? format(value, 'PPP')
                                                : 'Selecciona una fecha'}
                                        </Button>
                                    </PopoverTrigger>
                                    <PopoverContent
                                        className="w-auto p-0"
                                        align="start"
                                    >
                                        <Calendar
                                            mode="single"
                                            selected={value}
                                            onSelect={(date) =>
                                                date &&
                                                onChange(
                                                    applyDateKeepingTime(
                                                        value,
                                                        date,
                                                    ),
                                                )
                                            }
                                            autoFocus
                                        />
                                    </PopoverContent>
                                </Popover>
                                <Input
                                    type="time"
                                    value={formatTime(value)}
                                    onChange={(e) =>
                                        onChange(
                                            applyTimeToDate(
                                                value,
                                                e.target.value,
                                            ),
                                        )
                                    }
                                />
                            </div>
                        )}
                    />

                    <InputError
                        message={errors.registration_started_at?.message}
                    />
                </div>

                <div className="grid gap-2">
                    <Label htmlFor="registration_ended_at">Fecha de Fin del Registro:</Label>
                    <Controller
                        name="registration_ended_at"
                        control={control}
                        rules={{ required: 'La fecha de fin es requerida' }}
                        render={({ field: { value, onChange } }) => (
                            <div className="space-y-2">
                                <Popover>
                                    <PopoverTrigger asChild>
                                        <Button
                                            type="button"
                                            variant="outline"
                                            className={cn(
                                                'w-full justify-start text-left font-normal',
                                                !value &&
                                                'text-muted-foreground',
                                            )}
                                        >
                                            <CalendarIcon className="mr-2 h-4 w-4" />
                                            {value
                                                ? format(value, 'PPP')
                                                : 'Selecciona una fecha'}
                                        </Button>
                                    </PopoverTrigger>
                                    <PopoverContent
                                        className="w-auto p-0"
                                        align="start"
                                    >
                                        <Calendar
                                            mode="single"
                                            selected={value}
                                            onSelect={(date) =>
                                                date &&
                                                onChange(
                                                    applyDateKeepingTime(
                                                        value,
                                                        date,
                                                    ),
                                                )
                                            }
                                            autoFocus
                                        />
                                    </PopoverContent>
                                </Popover>
                                <Input
                                    type="time"
                                    value={formatTime(value)}
                                    onChange={(e) =>
                                        onChange(
                                            applyTimeToDate(
                                                value,
                                                e.target.value,
                                            ),
                                        )
                                    }
                                />
                            </div>
                        )}
                    />
                    <InputError message={errors.registration_ended_at?.message} />
                </div>
            </div>

            <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div className="grid gap-2">
                    <Label htmlFor="started_at">Fecha de Inicio:</Label>
                    <Controller
                        name="started_at"
                        control={control}
                        rules={{ required: 'La fecha de inicio es requerida' }}
                        render={({ field: { value, onChange } }) => (
                            <div className="space-y-2">
                                <Popover>
                                    <PopoverTrigger asChild>
                                        <Button
                                            type="button"
                                            variant="outline"
                                            className={cn(
                                                'w-full justify-start text-left font-normal',
                                                !value &&
                                                'text-muted-foreground',
                                            )}
                                        >
                                            <CalendarIcon className="mr-2 h-4 w-4" />
                                            {value
                                                ? format(value, 'PPP')
                                                : 'Selecciona una fecha'}
                                        </Button>
                                    </PopoverTrigger>
                                    <PopoverContent
                                        className="w-auto p-0"
                                        align="start"
                                    >
                                        <Calendar
                                            mode="single"
                                            selected={value}
                                            onSelect={(date) =>
                                                date &&
                                                onChange(
                                                    applyDateKeepingTime(
                                                        value,
                                                        date,
                                                    ),
                                                )
                                            }
                                            autoFocus
                                        />
                                    </PopoverContent>
                                </Popover>
                                <Input
                                    type="time"
                                    value={formatTime(value)}
                                    onChange={(e) =>
                                        onChange(
                                            applyTimeToDate(
                                                value,
                                                e.target.value,
                                            ),
                                        )
                                    }
                                />
                            </div>
                        )}
                    />
                    <InputError
                        message={errors.started_at?.message}
                    />
                </div>

                <div className="grid gap-2">
                    <Label htmlFor="ended_at">Fecha de Fin:</Label>
                    <Controller
                        name="ended_at"
                        control={control}
                        rules={{ required: 'La fecha de fin es requerida' }}
                        render={({ field: { value, onChange } }) => (
                            <div className="space-y-2">
                                <Popover>
                                    <PopoverTrigger asChild>
                                        <Button
                                            type="button"
                                            variant="outline"
                                            className={cn(
                                                'w-full justify-start text-left font-normal',
                                                !value &&
                                                'text-muted-foreground',
                                            )}
                                        >
                                            <CalendarIcon className="mr-2 h-4 w-4" />
                                            {value
                                                ? format(value, 'PPP')
                                                : 'Selecciona una fecha'}
                                        </Button>
                                    </PopoverTrigger>
                                    <PopoverContent
                                        className="w-auto p-0"
                                        align="start"
                                    >
                                        <Calendar
                                            mode="single"
                                            selected={value}
                                            onSelect={(date) =>
                                                date &&
                                                onChange(
                                                    applyDateKeepingTime(
                                                        value,
                                                        date,
                                                    ),
                                                )
                                            }
                                            autoFocus
                                        />
                                    </PopoverContent>
                                </Popover>
                                <Input
                                    type="time"
                                    value={formatTime(value)}
                                    onChange={(e) =>
                                        onChange(
                                            applyTimeToDate(
                                                value,
                                                e.target.value,
                                            ),
                                        )
                                    }
                                />
                            </div>
                        )}
                    />
                    <InputError message={errors.ended_at?.message} />
                </div>
            </div>

            <div className="grid gap-2">
                <Label htmlFor="requirements">Requisitos (Opcional):</Label>

                <Textarea
                    {...register('requirements', {
                        minLength: {
                            value: 50,
                            message:
                                'Los requisitos deben tener al menos 100 caracteres',
                        },
                    })}
                    id="requirements"
                    placeholder="Requisitos de la actividad"
                    className="h-60"
                />

                <InputError message={errors.requirements?.message} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="repository_url">Repositorio (opcional):</Label>
                <Input
                    {...register('repository_url', {
                        pattern: {
                            value: /^(https?:\/\/)?(www\.)?[-a-zA-Z0-9@:%._+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_+.~#?&//=]*)$/,
                            message: 'Ingrese una URL válida',
                        },
                    })}
                    type="url"
                    id="repository_url"
                    placeholder="Repositorio de la actividad"
                />
                <InputError
                    message={errors.repository_url?.message}
                />
            </div>


            <div className="grid gap-2">
                <Label htmlFor="speakers">Ponentes:</Label>

                <Controller
                    control={control}
                    name="speakers"
                    render={({ field: { onChange, value } }) => (
                        <ActivitySpeakersInput onChange={onChange} value={value} />
                    )}
                />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="event_activity_type_id">
                    Tipo de Actividad:
                </Label>

                <Controller
                    name="event_activity_type_id"
                    control={control}
                    rules={{ required: 'El tipo es requerido' }}
                    render={({ field: { value, onChange } }) => (
                        <Select
                            onValueChange={onChange}
                            value={value?.toString()}
                        >
                            <SelectTrigger>
                                <SelectValue placeholder="Selecciona un tipo" />
                            </SelectTrigger>
                            <SelectContent>
                                {activityTypes.map((type) => (
                                    <SelectItem
                                        key={type.id}
                                        value={type.id.toString()}
                                    >
                                        {type.name}
                                    </SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                    )}
                />

                <InputError
                    message={errors.event_activity_type_id?.message}
                />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="difficulty_level_id">
                    Nivel de Dificultad:
                </Label>
                <Controller
                    name="difficulty_level_id"
                    control={control}
                    rules={{ required: 'El nivel es requerido' }}
                    render={({ field: { value, onChange } }) => (
                        <Select
                            onValueChange={onChange}
                            value={value?.toString()}
                        >
                            <SelectTrigger>
                                <SelectValue placeholder="Selecciona un nivel" />
                            </SelectTrigger>
                            <SelectContent>
                                {difficultyLevels.map((level) => (
                                    <SelectItem
                                        key={level.id}
                                        value={level.id.toString()}
                                    >
                                        {level.name}
                                    </SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                    )}
                />
                <InputError
                    message={errors.difficulty_level_id?.message}
                />
            </div>

            <div className="grid gap-2">
                <p className="leading-none font-bold text-foreground select-none group-data-[disabled=true]:pointer-events-none group-data-[disabled=true]:opacity-50 peer-disabled:cursor-not-allowed peer-disabled:opacity-50">
                    Categorías:
                </p>

                <Controller
                    control={control}
                    name="categories"
                    rules={{
                        validate: (value) =>
                            value!.length > 0 ||
                            'Debe seleccionar al menos una categoría',
                    }}
                    render={({ field: { value, onChange, disabled } }) => (
                        <div className="grid grid-cols-1 gap-1">
                            {categories.map((category) => (
                                <div
                                    className="flex items-center gap-2"
                                    key={category.id}
                                >
                                    <Checkbox
                                        onCheckedChange={(checked) =>
                                            checked
                                                ? onChange([
                                                    ...(value || []),
                                                    category.id,
                                                ])
                                                : onChange(
                                                    value?.filter(
                                                        (id) =>
                                                            id !==
                                                            category.id,
                                                    ) || [],
                                                )
                                        }
                                        defaultChecked={value?.some(
                                            (id) => id === category.id,
                                        )}
                                        id={category.slug}
                                        disabled={disabled}
                                        value={category.id}
                                    />

                                    <Label
                                        className="text-base font-normal"
                                        htmlFor={category.slug}
                                    >
                                        {category.name}
                                    </Label>
                                </div>
                            ))}
                        </div>
                    )}
                />

                <InputError message={errors.categories?.message} />
            </div>

            <div className="flex items-center gap-2">
                <Controller
                    name="is_free"
                    control={control}
                    render={({ field: { onChange, value } }) => (
                        <Switch
                            id="is_free"
                            checked={value}
                            onCheckedChange={onChange}
                        />
                    )}
                />

                <Label htmlFor="is_free">Actividad Gratis</Label>
            </div>

            {!is_free && (
                <div className="grid gap-2">
                    <Label htmlFor="price">Precio:</Label>

                    <Input
                        {...register('price', {
                            required: 'El precio es requerido',
                            min: {
                                value: 1,
                                message: 'El precio debe ser mayor a 1',
                            },
                            valueAsNumber: true,
                        })}
                        id="price"
                        type="number"
                        step={0.01}
                        min={1}
                        placeholder="Precio de la Actividad"
                    />

                    <InputError message={errors.price?.message} />
                </div>
            )}

            <div className="flex items-center gap-2">
                <Controller
                    name="has_teams"
                    control={control}
                    render={({ field: { value, onChange } }) => (
                        <Switch
                            id="has_teams"
                            checked={value}
                            onCheckedChange={onChange}
                        />
                    )}
                />
                <Label htmlFor="has_teams">Actividad por Equipos</Label>
            </div>

            {has_teams && (
                <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div className="grid gap-2 md:col-span-2">
                        <div className="flex items-center gap-2">
                            <Controller
                                name="requires_team"
                                control={control}
                                render={({ field: { value, onChange } }) => (
                                    <Switch
                                        id="requires_team"
                                        checked={value}
                                        onCheckedChange={onChange}
                                    />
                                )}
                            />
                            <Label htmlFor="requires_team">
                                Participación solo con equipo
                            </Label>
                        </div>
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="min_team_size">
                            Tamaño Mínimo del Equipo:
                        </Label>
                        <Input
                            {...register('min_team_size', {
                                min: {
                                    value: 1,
                                    message:
                                        'El tamaño mínimo del equipo debe ser mayor a 1',
                                },
                                max: {
                                    value: max_team_size || Infinity,
                                    message: 'El tamaño mínimo del equipo no puede ser mayor a la capacidad de la actividad',
                                },
                                valueAsNumber: true,
                            })}
                            type="number"
                            id="min_team_size"
                        />
                        <InputError
                            message={errors.min_team_size?.message}
                        />
                    </div>
                    <div className="grid gap-2">
                        <Label htmlFor="max_team_size">
                            Tamaño Máximo del Equipo:
                        </Label>

                        <Input
                            {...register('max_team_size', {
                                min: {
                                    value: 1,
                                    message:
                                        'El tamaño máximo del equipo debe ser mayor a 1',
                                },
                                valueAsNumber: true,
                            })}
                            type="number"
                            id="max_team_size"
                        />
                        <InputError
                            message={errors.max_team_size?.message}
                        />
                    </div>
                </div>
            )}

            <div className="flex items-center gap-2">
                <Controller
                    name="only_students"
                    control={control}
                    render={({ field: { value, onChange } }) => (
                        <Switch
                            id="only_students"
                            checked={value}
                            onCheckedChange={onChange}
                        />
                    )}
                />
                <Label htmlFor="only_students">Solo Estudiantes</Label>
            </div>

            <div className="flex items-center gap-2">
                <Controller
                    name="with_capacity"
                    control={control}
                    render={({ field: { value, onChange } }) => (
                        <Switch
                            id="with_capacity"
                            checked={value}
                            onCheckedChange={onChange}
                        />
                    )}
                />
                <Label htmlFor="with_capacity">Con Capacidad</Label>
            </div>


            {with_capacity && (
                <div className="grid gap-2">
                    <Label htmlFor="capacity">Capacidad:</Label>

                    <Input
                        {...register('capacity', {
                            required: 'La capacidad es requerida',
                            min: {
                                value: 1,
                                message: 'La capacidad debe ser mayor a 1',
                            },
                            valueAsNumber: true,
                        })}
                        id="capacity"
                        type="number"
                        step={1}
                        min={1}
                        placeholder="Capacidad de la Actividad"
                    />

                    <InputError message={errors.capacity?.message} />
                </div>
            )}

            <div className="flex items-center gap-2">
                <Controller
                    name="is_online"
                    control={control}
                    render={({ field: { value, onChange } }) => (
                        <Switch
                            id="is_online"
                            checked={value}
                            onCheckedChange={onChange}
                        />
                    )}
                />
                <Label htmlFor="is_online">Actividad en Línea</Label>
            </div>

            {is_online ? (
                <div className="grid gap-2">
                    <Label htmlFor="online_link">Enlace en Línea:</Label>
                    <Input
                        {...register('online_link', {
                            required: 'El enlace es requerido',
                            pattern: {
                                value: /^(https?:\/\/)?(www\.)?[-a-zA-Z0-9@:%._+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_+.~#?&//=]*)$/,
                                message: 'Ingrese una URL válida',
                            },
                        })}
                        id="online_link"
                        placeholder="https://..."
                    />
                    <InputError
                        message={errors.online_link?.message}
                    />
                </div>
            ) : (
                <>
                    <div className="grid gap-2">
                        <Label htmlFor="location">Ubicación:</Label>
                        <Input
                            {...register('location', {
                                required: 'La ubicación es requerida',
                                minLength: {
                                    value: 3,
                                    message:
                                        'La ubicación debe tener al menos 3 caracteres',
                                },
                            })}
                            id="location"
                            placeholder="Lugar de la actividad"
                        />
                        <InputError
                            message={errors.location?.message}
                        />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="latLng">Ubicación en mapa:</Label>
                        <Controller
                            name="latLng"
                            control={control}
                            render={({ field: { value, onChange } }) => (
                                <LocationMap
                                    value={value}
                                    onChange={onChange}
                                />
                            )}
                        />
                    </div>
                </>
            )}
        </>
    );
}
