import type { Control, FieldErrors, UseFormRegister } from 'react-hook-form';
import { Controller } from 'react-hook-form';
import InputError from '@/components/ui/app/input-error';
import { Input } from '@/components/ui/shadcn/input';
import { Label } from '@/components/ui/shadcn/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/shadcn/select';
import { Switch } from '@/components/ui/shadcn/switch';
import { Textarea } from '@/components/ui/shadcn/textarea';
import type {
    CourseCategory,
    CourseFormData,
    CourseStatus,
} from '@/types/classroom';

type CourseFormProps = {
    statuses: CourseStatus[];
    categories: CourseCategory[];
    register: UseFormRegister<CourseFormData>;
    control: Control<CourseFormData>;
    errors: FieldErrors<CourseFormData>;
};

export default function CourseForm({
    statuses,
    categories,
    register,
    control,
    errors,
}: CourseFormProps) {
    return (
        <>
            <div className="grid gap-2">
                <Label htmlFor="name">Nombre</Label>
                <Input
                    id="name"
                    {...register('name')}
                    placeholder="Nombre del curso"
                />
                <InputError message={errors.name?.message} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="summary">Resumen</Label>
                <Textarea
                    id="summary"
                    {...register('summary')}
                    className="h-44"
                />
                <InputError message={errors.summary?.message} />
            </div>

            <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div className="grid gap-2">
                    <Label htmlFor="code">Codigo</Label>
                    <Input
                        id="code"
                        {...register('code')}
                        placeholder="CS-101"
                    />
                    <InputError message={errors.code?.message} />
                </div>

                <div className="grid gap-2">
                    <Label htmlFor="period">Periodo</Label>
                    <Input
                        id="period"
                        {...register('period')}
                        placeholder="2026-A"
                    />
                    <InputError message={errors.period?.message} />
                </div>
            </div>

            <div className="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div className="grid gap-2">
                    <Label htmlFor="credits">Creditos</Label>
                    <Input
                        id="credits"
                        type="number"
                        {...register('credits', { valueAsNumber: true })}
                    />
                    <InputError message={errors.credits?.message} />
                </div>

                <div className="grid gap-2">
                    <Label htmlFor="price">Precio</Label>
                    <Input
                        id="price"
                        type="number"
                        step="0.01"
                        {...register('price', { valueAsNumber: true })}
                    />
                    <InputError message={errors.price?.message} />
                </div>

                <div className="grid gap-2">
                    <Label htmlFor="capacity">Capacidad</Label>
                    <Input
                        id="capacity"
                        type="number"
                        {...register('capacity', { valueAsNumber: true })}
                    />
                    <InputError message={errors.capacity?.message} />
                </div>
            </div>

            <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div className="grid gap-2">
                    <Label>Estatus</Label>
                    <Controller
                        name="course_status_id"
                        control={control}
                        render={({ field: { onChange, value } }) => (
                            <Select
                                value={value?.toString()}
                                onValueChange={onChange}
                            >
                                <SelectTrigger>
                                    <SelectValue placeholder="Selecciona estatus" />
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
                    <InputError message={errors.course_status_id?.message} />
                </div>

                <div className="grid gap-2">
                    <Label>Categoria</Label>
                    <Controller
                        name="course_category_id"
                        control={control}
                        render={({ field: { onChange, value } }) => (
                            <Select
                                value={value?.toString()}
                                onValueChange={onChange}
                            >
                                <SelectTrigger>
                                    <SelectValue placeholder="Selecciona categoria" />
                                </SelectTrigger>
                                <SelectContent>
                                    {categories.map((category) => (
                                        <SelectItem
                                            key={category.id}
                                            value={category.id.toString()}
                                        >
                                            {category.name}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                        )}
                    />
                    <InputError message={errors.course_category_id?.message} />
                </div>
            </div>

            <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div className="grid gap-2">
                    <Label htmlFor="start_date">Inicio</Label>
                    <Input
                        id="start_date"
                        type="date"
                        {...register('start_date')}
                    />
                    <InputError message={errors.start_date?.message} />
                </div>

                <div className="grid gap-2">
                    <Label htmlFor="end_date">Fin</Label>
                    <Input
                        id="end_date"
                        type="date"
                        {...register('end_date')}
                    />
                    <InputError message={errors.end_date?.message} />
                </div>
            </div>

            <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div className="grid gap-2">
                    <Label htmlFor="enrollment_start_date">
                        Inicio de inscripciones
                    </Label>
                    <Input
                        id="enrollment_start_date"
                        type="date"
                        {...register('enrollment_start_date')}
                    />
                    <InputError
                        message={errors.enrollment_start_date?.message}
                    />
                </div>

                <div className="grid gap-2">
                    <Label htmlFor="enrollment_end_date">
                        Fin de inscripciones
                    </Label>
                    <Input
                        id="enrollment_end_date"
                        type="date"
                        {...register('enrollment_end_date')}
                    />
                    <InputError message={errors.enrollment_end_date?.message} />
                </div>
            </div>

            <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div className="flex items-center gap-2 rounded-md border p-3">
                    <Controller
                        name="is_free"
                        control={control}
                        render={({ field: { onChange, value } }) => (
                            <Switch
                                checked={Boolean(value)}
                                onCheckedChange={onChange}
                            />
                        )}
                    />
                    <Label>Curso gratuito</Label>
                </div>

                <div className="flex items-center gap-2 rounded-md border p-3">
                    <Controller
                        name="is_published"
                        control={control}
                        render={({ field: { onChange, value } }) => (
                            <Switch
                                checked={Boolean(value)}
                                onCheckedChange={onChange}
                            />
                        )}
                    />
                    <Label>Publicar curso</Label>
                </div>
            </div>
        </>
    );
}
