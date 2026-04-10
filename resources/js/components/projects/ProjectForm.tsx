import type { Control, UseFormRegister } from 'react-hook-form';
import { Controller, useFormState } from 'react-hook-form';
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
import type {
    ProjectCategory,
    ProjectFormData,
    ProjectStatus,
} from '@/types/projects';
import TechStackInput from './TechStackInput';

type ProjectFormProps = {
    statuses: ProjectStatus[];
    categories: ProjectCategory[];
    register: UseFormRegister<ProjectFormData>;
    control: Control<ProjectFormData, unknown, ProjectFormData>;
};

export default function ProjectForm({
    register,
    control,
    categories,
    statuses,
}: ProjectFormProps) {
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
                    type="text"
                    placeholder="Nombre del Proyecto"
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
                    placeholder="Resumen del Proyecto"
                    className="h-60"
                />

                <InputError message={errors.description?.message} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="repository_url">
                    Repositorio (GitHub, GitLab, etc.):
                </Label>

                <Input
                    {...register('repository_url', {
                        required: 'El repositorio es requerido',
                        pattern: {
                            value: /^(https?:\/\/)?(www\.)?[-a-zA-Z0-9@:%._+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_+.~#?&//=]*)$/,
                            message: 'Ingrese una URL válida',
                        },
                    })}
                    id="repository_url"
                    type="url"
                    placeholder="Repositorio del Proyecto (http://...)"
                />

                <InputError message={errors.repository_url?.message} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="demo_url">URL del Proyecto:</Label>

                <Input
                    {...register('demo_url', {
                        required: 'El repositorio es requerido',
                        pattern: {
                            value: /^(https?:\/\/)?(www\.)?[-a-zA-Z0-9@:%._+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_+.~#?&//=]*)$/,
                            message: 'Ingrese una URL válida',
                        },
                    })}
                    id="demo_url"
                    type="url"
                    placeholder="URL del Proyecto (http://...)"
                />

                <InputError message={errors.demo_url?.message} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="license">Licencia:</Label>

                <Input
                    {...register('license', {
                        required: 'La licencia es requerida',
                    })}
                    id="license"
                    type="text"
                    placeholder="Licencia del Proyecto"
                />

                <InputError message={errors.license?.message} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="version">Versión:</Label>

                <Input
                    {...register('version', {
                        required: 'La versión es requerida',
                        pattern: {
                            value: /^v?\d+\.\d+\.\d+(-[\w.]+)?$/,
                            message: 'Ingrese una versión válida',
                        },
                    })}
                    id="version"
                    type="text"
                    placeholder="Versión del Proyecto"
                />

                <InputError message={errors.version?.message} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="tech_stack">TechStack:</Label>

                <Controller
                    control={control}
                    name="tech_stack"
                    rules={{
                        validate: (value) =>
                            value!.length > 0 ||
                            'Debe seleccionar al menos una tecnología',
                    }}
                    render={({ field: { onChange, value } }) => (
                        <TechStackInput onChange={onChange} value={value} />
                    )}
                />

                <InputError message={errors.tech_stack?.message} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="project_status_id">Estatus: </Label>

                <Controller
                    name="project_status_id"
                    control={control}
                    rules={{ required: 'El tipo es requerido' }}
                    render={({ field: { value, onChange } }) => (
                        <Select
                            value={value?.toString()}
                            onValueChange={onChange}
                        >
                            <SelectTrigger>
                                <SelectValue placeholder="Estatus del Proyecto" />
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

                <InputError message={errors.project_status_id?.message} />
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
