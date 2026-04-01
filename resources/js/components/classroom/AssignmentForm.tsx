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
import type { CourseLesson } from '@/types/classroom';

export type AssignmentFormData = {
    name: string;
    summary: string;
    instructions: string;
    max_score: number;
    passing_score: number;
    allow_late_submissions: boolean;
    max_attempts: number;
    submission_type: 'file' | 'text' | 'url' | 'form' | 'mixed';
    is_published: boolean;
    due_date: string;
    available_from: string;
    course_lesson_id: number | string | null;
};

type AssignmentFormProps = {
    lessons: CourseLesson[];
    register: UseFormRegister<AssignmentFormData>;
    control: Control<AssignmentFormData>;
    errors: FieldErrors<AssignmentFormData>;
};

export default function AssignmentForm({
    lessons,
    register,
    control,
    errors,
}: AssignmentFormProps) {
    return (
        <>
            <div className="grid gap-2">
                <Label htmlFor="name">Nombre</Label>
                <Input id="name" {...register('name')} />
                <InputError message={errors.name?.message} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="summary">Resumen</Label>
                <Textarea id="summary" {...register('summary')} />
                <InputError message={errors.summary?.message} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="instructions">Instrucciones (JSON)</Label>
                <Textarea
                    id="instructions"
                    {...register('instructions')}
                    className="h-40"
                />
                <InputError message={errors.instructions?.message} />
            </div>

            <div className="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div className="grid gap-2">
                    <Label htmlFor="max_score">Puntaje maximo</Label>
                    <Input
                        id="max_score"
                        type="number"
                        step="0.01"
                        {...register('max_score', { valueAsNumber: true })}
                    />
                </div>
                <div className="grid gap-2">
                    <Label htmlFor="passing_score">Puntaje aprobatorio</Label>
                    <Input
                        id="passing_score"
                        type="number"
                        step="0.01"
                        {...register('passing_score', { valueAsNumber: true })}
                    />
                </div>
                <div className="grid gap-2">
                    <Label htmlFor="max_attempts">Intentos maximos</Label>
                    <Input
                        id="max_attempts"
                        type="number"
                        {...register('max_attempts', { valueAsNumber: true })}
                    />
                </div>
            </div>

            <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div className="grid gap-2">
                    <Label htmlFor="available_from">Disponible desde</Label>
                    <Input
                        id="available_from"
                        type="datetime-local"
                        {...register('available_from')}
                    />
                </div>
                <div className="grid gap-2">
                    <Label htmlFor="due_date">Fecha limite</Label>
                    <Input
                        id="due_date"
                        type="datetime-local"
                        {...register('due_date')}
                    />
                </div>
            </div>

            <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div className="grid gap-2">
                    <Label>Tipo de entrega</Label>
                    <Controller
                        name="submission_type"
                        control={control}
                        render={({ field: { onChange, value } }) => (
                            <Select value={value} onValueChange={onChange}>
                                <SelectTrigger>
                                    <SelectValue placeholder="Tipo" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="file">
                                        Archivo
                                    </SelectItem>
                                    <SelectItem value="text">Texto</SelectItem>
                                    <SelectItem value="url">URL</SelectItem>
                                    <SelectItem value="form">
                                        Formulario
                                    </SelectItem>
                                    <SelectItem value="mixed">Mixto</SelectItem>
                                </SelectContent>
                            </Select>
                        )}
                    />
                </div>
                <div className="grid gap-2">
                    <Label>Leccion vinculada</Label>
                    <Controller
                        name="course_lesson_id"
                        control={control}
                        render={({ field: { onChange, value } }) => (
                            <Select
                                value={value?.toString()}
                                onValueChange={onChange}
                            >
                                <SelectTrigger>
                                    <SelectValue placeholder="Opcional" />
                                </SelectTrigger>
                                <SelectContent>
                                    {lessons.map((lesson) => (
                                        <SelectItem
                                            key={lesson.id}
                                            value={lesson.id.toString()}
                                        >
                                            {lesson.name}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                        )}
                    />
                </div>
            </div>

            <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div className="flex items-center gap-2 rounded-md border p-3">
                    <Controller
                        name="allow_late_submissions"
                        control={control}
                        render={({ field: { onChange, value } }) => (
                            <Switch
                                checked={Boolean(value)}
                                onCheckedChange={onChange}
                            />
                        )}
                    />
                    <Label>Permitir entregas tardias</Label>
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
                    <Label>Publicar tarea</Label>
                </div>
            </div>
        </>
    );
}
