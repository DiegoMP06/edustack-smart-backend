import type { Control, UseFormRegister } from 'react-hook-form';
import { Controller, useFormState, useWatch } from 'react-hook-form';
import DropzoneInput from '@/components/dropzone/DropzoneInput';
import LocationMap from '@/components/leaflet/LocationMap';
import InputError from '@/components/ui/app/input-error';
import { Input } from '@/components/ui/shadcn/input';
import { Label } from '@/components/ui/shadcn/label';
import { Switch } from '@/components/ui/shadcn/switch';
import { Textarea } from '@/components/ui/shadcn/textarea';
import {
    toDateLocal,
    toDatetimeLocal
} from '@/lib/utils';
import type { EventFormData } from '@/types/events';

type EventFormProps = {
    control: Control<EventFormData, unknown, EventFormData>;
    register: UseFormRegister<EventFormData>;
    edit?: boolean;
    defaultImage?: string | null;
};

export default function EventForm({
    control,
    register,
    defaultImage,
    edit,
}: EventFormProps) {
    const { errors } = useFormState({ control })
    const is_free = useWatch({ control, name: 'is_free' });
    const is_online = useWatch({ control, name: 'is_online' });
    const with_capacity = useWatch({ control, name: 'with_capacity' });

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
                                'El título debe tener al menos 3 caracteres',
                        },
                    })}
                    id="name"
                    type="text"
                    placeholder="Nombre del Evento"
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
                    placeholder="Resumen del Evento"
                    className="h-60"
                />

                <InputError message={errors.description?.message} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="logo">Logotipo: </Label>

                <Controller
                    name="logo"
                    control={control}
                    rules={edit ? {} : { required: 'La imagen es requerida' }}
                    render={({ field: { value, onChange } }) => (
                        <DropzoneInput
                            value={value}
                            onChange={onChange}
                            defaultImage={defaultImage}
                        />
                    )}
                />

                <InputError message={errors.logo?.message} />
            </div>

            <div className="grid grid-cols-1 gap-4 md:grid-cols-2">

                <div className="grid flex-1 gap-2">
                    <Label htmlFor="registration_started_at">
                        Fecha de inicio del Registro:{' '}
                    </Label>

                    <Controller
                        name="registration_started_at"
                        control={control}
                        rules={{ required: 'La Fecha es requerida' }}
                        render={({ field: { value, onChange } }) => (
                            <Input
                                id="registration_started_at"
                                type="datetime-local"
                                value={toDatetimeLocal(value)}
                                onChange={(event) =>
                                    onChange(new Date(event.target.value))
                                }
                            />
                        )}
                    />

                    <InputError message={errors.start_date?.message} />
                </div>

                <div className="grid flex-1 gap-2">
                    <Label htmlFor="registration_ended_at">
                        Fecha de fin del Registro:{' '}
                    </Label>

                    <Controller
                        name="registration_ended_at"
                        control={control}
                        rules={{ required: 'La Fecha es requerida' }}
                        render={({ field: { value, onChange } }) => (
                            <Input
                                id="registration_ended_at"
                                type="datetime-local"
                                value={toDatetimeLocal(value)}
                                onChange={(event) =>
                                    onChange(new Date(event.target.value))
                                }
                            />
                        )}
                    />

                    <InputError message={errors.end_date?.message} />
                </div>
            </div>

            <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div className="grid flex-1 gap-2">
                    <Label htmlFor="start_date">Fecha de inicio: </Label>

                    <Controller
                        name="start_date"
                        control={control}
                        rules={{ required: 'La Fecha es requerida' }}
                        render={({ field: { value, onChange } }) => (
                            <Input
                                id="start_date"
                                type="date"
                                value={toDateLocal(value)}
                                onChange={(event) =>
                                    onChange(new Date(event.target.value))
                                }
                            />
                        )}
                    />

                    <InputError message={errors.start_date?.message} />
                </div>

                <div className="grid flex-1 gap-2">
                    <Label htmlFor="end_date">Fecha de fin: </Label>

                    <Controller
                        name="end_date"
                        control={control}
                        rules={{ required: 'La Fecha es requerida' }}
                        render={({ field: { value, onChange } }) => (
                            <Input
                                id="end_date"
                                type="date"
                                value={toDateLocal(value)}
                                onChange={(event) =>
                                    onChange(new Date(event.target.value))
                                }
                            />
                        )}
                    />

                    <InputError message={errors.end_date?.message} />
                </div>
            </div>

            <div className="flex items-center gap-2">
                <Controller
                    name="with_capacity"
                    control={control}
                    render={({ field: { onChange, value } }) => (
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
                        placeholder="Capacidad del Evento"
                    />

                    <InputError message={errors.price?.message} />
                </div>
            )}

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

                <Label htmlFor="is_free">Evento Gratis</Label>
            </div>

            {!is_free && (
                <div className='grid grid-cols-1 gap-4 md:grid-cols-2'>
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
                            placeholder="Precio del Evento"
                        />

                        <InputError message={errors.price?.message} />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="percent_off">Descuento (%):</Label>

                        <Input
                            {...register('percent_off', {
                                required: 'El descuento es requerido',
                                min: {
                                    value: 0,
                                    message: 'El descuento debe ser mayor a 0',
                                },
                                max: {
                                    value: 100,
                                    message:
                                        'El descuento debe ser menor a 100',
                                },
                                valueAsNumber: true,
                            })}
                            id="percent_off"
                            type="number"
                            step={0.01}
                            min={0}
                            max={100}
                            placeholder="Precio del Evento"
                        />

                        <InputError message={errors.percent_off?.message} />
                    </div>
                </div>
            )}

            <div className="flex items-center gap-2">
                <Controller
                    name="is_online"
                    control={control}
                    render={({ field: { onChange, value } }) => (
                        <Switch
                            id="is_online"
                            checked={value}
                            onCheckedChange={onChange}
                        />
                    )}
                />

                <Label htmlFor="is_online">Evento En Linea</Label>
            </div>

            {is_online ? (
                <div className="grid gap-2">
                    <Label htmlFor="online_link">Enlace del Evento:</Label>

                    <Input
                        {...register('online_link', {
                            required: 'El enlace es requerido',
                            pattern: {
                                value: /^(https?:\/\/)?(www\.)?[-a-zA-Z0-9@:%._+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_+.~#?&//=]*)$/,
                                message: 'Ingrese una URL válida',
                            },
                        })}
                        id="demo_url"
                        type="url"
                        placeholder="Enlace del Evento (http://...)"
                    />

                    <InputError message={errors.online_link?.message} />
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
                            type="text"
                            placeholder="Ubicación del Evento"
                        />

                        <InputError message={errors.location?.message} />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="latLng">
                            Ubicación en el mapa (Mueve el Pin):{' '}
                        </Label>

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
