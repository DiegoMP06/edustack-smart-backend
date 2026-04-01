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
import type { CourseLesson, CourseSection } from '@/types/classroom';

type LessonFormData = {
    name: string;
    summary: string;
    type: CourseLesson['type'];
    video_url: string;
    video_duration_seconds: number;
    order: number;
    estimated_minutes: number;
    is_published: boolean;
    is_preview: boolean;
    course_section_id: number | string;
};

type LessonFormProps = {
    sections: CourseSection[];
    register: UseFormRegister<LessonFormData>;
    control: Control<LessonFormData>;
    errors: FieldErrors<LessonFormData>;
};

export default function LessonForm({
    sections,
    register,
    control,
    errors,
}: LessonFormProps) {
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

            <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div className="grid gap-2">
                    <Label>Tipo</Label>
                    <Controller
                        name="type"
                        control={control}
                        render={({ field: { onChange, value } }) => (
                            <Select value={value} onValueChange={onChange}>
                                <SelectTrigger>
                                    <SelectValue placeholder="Tipo de leccion" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="text">Texto</SelectItem>
                                    <SelectItem value="video">Video</SelectItem>
                                    <SelectItem value="activity">
                                        Actividad
                                    </SelectItem>
                                    <SelectItem value="live">
                                        En vivo
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        )}
                    />
                    <InputError message={errors.type?.message} />
                </div>

                <div className="grid gap-2">
                    <Label>Seccion</Label>
                    <Controller
                        name="course_section_id"
                        control={control}
                        render={({ field: { onChange, value } }) => (
                            <Select
                                value={value?.toString()}
                                onValueChange={onChange}
                            >
                                <SelectTrigger>
                                    <SelectValue placeholder="Selecciona seccion" />
                                </SelectTrigger>
                                <SelectContent>
                                    {sections.map((section) => (
                                        <SelectItem
                                            key={section.id}
                                            value={section.id.toString()}
                                        >
                                            {section.name}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                        )}
                    />
                    <InputError message={errors.course_section_id?.message} />
                </div>
            </div>

            <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div className="grid gap-2">
                    <Label htmlFor="video_url">URL de video</Label>
                    <Input id="video_url" {...register('video_url')} />
                    <InputError message={errors.video_url?.message} />
                </div>

                <div className="grid gap-2">
                    <Label htmlFor="video_duration_seconds">
                        Duracion de video (seg)
                    </Label>
                    <Input
                        id="video_duration_seconds"
                        type="number"
                        {...register('video_duration_seconds', {
                            valueAsNumber: true,
                        })}
                    />
                    <InputError
                        message={errors.video_duration_seconds?.message}
                    />
                </div>
            </div>

            <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div className="grid gap-2">
                    <Label htmlFor="order">Orden</Label>
                    <Input
                        id="order"
                        type="number"
                        {...register('order', { valueAsNumber: true })}
                    />
                </div>
                <div className="grid gap-2">
                    <Label htmlFor="estimated_minutes">Minutos estimados</Label>
                    <Input
                        id="estimated_minutes"
                        type="number"
                        {...register('estimated_minutes', {
                            valueAsNumber: true,
                        })}
                    />
                </div>
            </div>

            <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
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
                    <Label>Publicada</Label>
                </div>
                <div className="flex items-center gap-2 rounded-md border p-3">
                    <Controller
                        name="is_preview"
                        control={control}
                        render={({ field: { onChange, value } }) => (
                            <Switch
                                checked={Boolean(value)}
                                onCheckedChange={onChange}
                            />
                        )}
                    />
                    <Label>Disponible como vista previa</Label>
                </div>
            </div>
        </>
    );
}
