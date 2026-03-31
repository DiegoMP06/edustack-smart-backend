import type { Control, FieldErrors, UseFormRegister } from 'react-hook-form';
import { Controller } from 'react-hook-form';
import InputError from '@/components/ui/app/input-error';
import { Checkbox } from '@/components/ui/shadcn/checkbox';
import { Input } from '@/components/ui/shadcn/input';
import { Label } from '@/components/ui/shadcn/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/shadcn/select';
import { Textarea } from '@/components/ui/shadcn/textarea';
import type { PostCategory, PostFormData, PostType } from '@/types/blog';

type PostFormProps = {
    types: PostType[];
    categories: PostCategory[];
    register: UseFormRegister<PostFormData>;
    control: Control<PostFormData>;
    errors: FieldErrors<PostFormData>;
};

export default function PostForm({
    types,
    register,
    control,
    errors,
    categories,
}: PostFormProps) {
    return (
        <>
            <div className="grid gap-2">
                <Label htmlFor="name">Título:</Label>

                <Input
                    {...register('name', {
                        required: 'El título es requerido',
                        minLength: {
                            value: 3,
                            message:
                                'El título debe tener al menos 3 caracteres',
                        },
                    })}
                    id="name"
                    type="text"
                    placeholder="Titulo del Post"
                />

                <InputError message={errors.name?.message} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="summary">Resumen:</Label>

                <Textarea
                    {...register('summary', {
                        required: 'El resumen es requerido',
                        minLength: {
                            value: 50,
                            message:
                                'El resumen debe tener al menos 50 caracteres',
                        },
                    })}
                    id="summary"
                    placeholder="Resumen del Post"
                    className="h-60"
                />

                <InputError message={errors.summary?.message} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="reading_time_minutes">Tiempo de lectura (minutos):</Label>

                <Input
                    {...register('reading_time_minutes', {
                        valueAsNumber: true,
                        required: 'El tiempo de lectura es requerido',
                        min: {
                            value: 1,
                            message: 'El tiempo de lectura debe ser al menos 1 minuto',
                        },
                    })}
                    id="reading_time_minutes"
                    type="number"
                    placeholder="Tiempo de lectura"
                />

                <InputError message={errors.reading_time_minutes?.message} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="post_type_id">Tipo: </Label>

                <Controller
                    name="post_type_id"
                    control={control}
                    rules={{ required: 'El tipo es requerido' }}
                    render={({ field: { value, onChange } }) => (
                        <Select
                            value={value?.toString()}
                            onValueChange={onChange}
                        >
                            <SelectTrigger>
                                <SelectValue placeholder="Tipo de Post" />
                            </SelectTrigger>
                            <SelectContent>
                                {types.map((type) => (
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

                <InputError message={errors.post_type_id?.message} />
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
        </>
    );
}
