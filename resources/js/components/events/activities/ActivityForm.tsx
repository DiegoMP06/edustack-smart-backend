import { format } from 'date-fns';
import { CalendarIcon } from 'lucide-react';
import type {
    Control,
    FieldErrors,
    UseFormRegister,
    UseFormWatch,
} from 'react-hook-form';
import { Controller } from 'react-hook-form';
import DropzoneInput from '@/components/dropzone/DropzoneInput';
import LocationMap from '@/components/leaflet/LocationMap';
import InputError from '@/components/ui/app/input-error';
import { Button } from '@/components/ui/shadcn/button';
import { Calendar } from '@/components/ui/shadcn/calendar';
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
import { applyTimeToDate, cn, formatTime } from '@/lib/utils';
import type { EventActivityFormData, EventCatalogItem } from '@/types/events';

type ActivityFormProps = {
    control: Control<EventActivityFormData>;
    register: UseFormRegister<EventActivityFormData>;
    errors: FieldErrors<EventActivityFormData>;
    watch: UseFormWatch<EventActivityFormData>;
    defaultImage?: string | null;
    statuses: EventCatalogItem[];
    difficultyLevels: EventCatalogItem[];
    activityTypes: EventCatalogItem[];
};

export default function ActivityForm({
    control,
    register,
    errors,
    defaultImage,
    watch,
    statuses,
    difficultyLevels,
    activityTypes,
}: ActivityFormProps) {
    const isOnline = watch('is_online');
    const hasTeams = watch('has_teams');

    const applyDateKeepingTime = (
        currentDate: Date | undefined,
        nextDate: Date,
    ): Date => {
        const dateWithTime = new Date(nextDate);

        if (currentDate) {
            dateWithTime.setHours(
                currentDate.getHours(),
                currentDate.getMinutes(),
                0,
                0,
            );
        }

        return dateWithTime;
    };

    return (
        <>
            <div className="grid gap-2">
                <Label htmlFor="name">Nombre:</Label>
                <Input
                    {...register('name', {
                        required: 'El nombre es requerido',
                    })}
                    id="name"
                    placeholder="Nombre de la actividad"
                />
                <InputError message={errors.name?.message as string} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="summary">Resumen:</Label>
                <Textarea
                    {...register('summary', {
                        required: 'El resumen es requerido',
                    })}
                    id="summary"
                    placeholder="Resumen de la actividad"
                />
                <InputError message={errors.summary?.message as string} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="image">Imagen:</Label>
                <Controller
                    name="image"
                    control={control}
                    render={({ field: { value, onChange } }) => (
                        <DropzoneInput
                            value={value}
                            onChange={onChange}
                            defaultImage={defaultImage}
                        />
                    )}
                />
                <InputError message={errors.image?.message as string} />
            </div>

            <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div className="grid gap-2">
                    <Label htmlFor="event_status_id">Estado:</Label>
                    <Controller
                        name="event_status_id"
                        control={control}
                        rules={{ required: 'El estado es requerido' }}
                        render={({ field: { value, onChange } }) => (
                            <Select
                                onValueChange={onChange}
                                value={value?.toString()}
                            >
                                <SelectTrigger>
                                    <SelectValue placeholder="Selecciona un estado" />
                                </SelectTrigger>
                                <SelectContent>
                                    {statuses.map((status) => (
                                        <SelectItem
                                            key={status.id}
                                            value={status.id.toString()}
                                        >
                                            {status.name}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                        )}
                    />
                    <InputError
                        message={errors.event_status_id?.message as string}
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
                        message={
                            errors.event_activity_type_id?.message as string
                        }
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
                        message={errors.difficulty_level_id?.message as string}
                    />
                </div>
            </div>

            <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div className="grid gap-2">
                    <Label htmlFor="price">Precio:</Label>
                    <Input
                        {...register('price', {
                            required: 'El precio es requerido',
                            valueAsNumber: true,
                        })}
                        type="number"
                        min={0}
                        step={0.01}
                        id="price"
                    />
                    <InputError message={errors.price?.message as string} />
                </div>

                <div className="grid gap-2">
                    <Label htmlFor="capacity">Capacidad máxima:</Label>
                    <Input
                        {...register('capacity', {
                            valueAsNumber: true,
                        })}
                        type="number"
                        min={1}
                        id="capacity"
                    />
                    <InputError message={errors.capacity?.message as string} />
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
                        message={errors.started_at?.message as string}
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
                    <InputError message={errors.ended_at?.message as string} />
                </div>
            </div>

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

            {isOnline ? (
                <div className="grid gap-2">
                    <Label htmlFor="online_link">Enlace en Línea:</Label>
                    <Input
                        {...register('online_link')}
                        id="online_link"
                        placeholder="https://..."
                    />
                    <InputError
                        message={errors.online_link?.message as string}
                    />
                </div>
            ) : (
                <>
                    <div className="grid gap-2">
                        <Label htmlFor="location">Ubicación:</Label>
                        <Input
                            {...register('location')}
                            id="location"
                            placeholder="Lugar de la actividad"
                        />
                        <InputError
                            message={errors.location?.message as string}
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

            <div className="flex items-center gap-2">
                <Controller
                    name="is_competition"
                    control={control}
                    render={({ field: { value, onChange } }) => (
                        <Switch
                            id="is_competition"
                            checked={value}
                            onCheckedChange={onChange}
                        />
                    )}
                />
                <Label htmlFor="is_competition">Es una Competencia</Label>
            </div>

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
                <Label htmlFor="has_teams">Evento por Equipos</Label>
            </div>

            {hasTeams && (
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
                            {...register('min_team_size')}
                            type="number"
                            id="min_team_size"
                        />
                        <InputError
                            message={errors.min_team_size?.message as string}
                        />
                    </div>
                    <div className="grid gap-2">
                        <Label htmlFor="max_team_size">
                            Tamaño Máximo del Equipo:
                        </Label>
                        <Input
                            {...register('max_team_size')}
                            type="number"
                            id="max_team_size"
                        />
                        <InputError
                            message={errors.max_team_size?.message as string}
                        />
                    </div>
                </div>
            )}

            <div className="grid gap-2">
                <Label htmlFor="repository_url">Repositorio (opcional):</Label>
                <Input
                    {...register('repository_url')}
                    type="url"
                    id="repository_url"
                />
                <InputError
                    message={errors.repository_url?.message as string}
                />
            </div>

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
        </>
    );
}
