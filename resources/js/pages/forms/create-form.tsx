import { Head, router, useForm } from '@inertiajs/react';
import { ChevronLeft } from 'lucide-react';
import type { FormEvent } from 'react';
import { toast } from 'sonner';
import InputError from '@/components/ui/app/input-error';
import { Button } from '@/components/ui/shadcn/button';
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
import { FORM_RESULTS_VISIBILITY } from '@/consts/forms';
import AppLayout from '@/layouts/app-layout';
import forms from '@/routes/forms';
import type { BreadcrumbItem } from '@/types';
import type {
    FormPayload,
    FormResultsVisibilityValue,
    FormType,
} from '@/types/forms';

type CreateFormProps = {
    types: FormType[];
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Formularios', href: forms.index().url },
    { title: 'Crear Formulario', href: forms.create().url },
];

const toServerDateTime = (value: string): string | null => {
    if (!value) {
        return null;
    }

    const withSeconds = value.length === 16 ? `${value}:00` : value;

    return withSeconds.replace('T', ' ');
};

export default function CreateForm({ types }: CreateFormProps) {
    const { data, setData, post, processing, errors } = useForm<FormPayload>({
        title: '',
        description: '',
        form_type_id: types[0]?.id ?? 1,
        requires_login: true,
        allow_multiple_responses: false,
        max_responses: null,
        collect_email: false,
        show_progress_bar: true,
        shuffle_sections: false,
        available_from: null,
        available_until: null,
        confirmation_message: null,
        redirect_url: null,
        is_quiz_mode: false,
        time_limit_minutes: null,
        max_attempts: 1,
        passing_score: null,
        randomize_questions: false,
        randomize_options: false,
        show_results_to_respondent: FORM_RESULTS_VISIBILITY.immediately,
        show_correct_answers: false,
        show_feedback_after: true,
    });

    const handleSubmit = (event: FormEvent<HTMLFormElement>): void => {
        event.preventDefault();

        post(forms.store.url() as string, {
            preserveScroll: true,
            onSuccess: (page) => {
                toast.success(
                    (page.props.message as string) ??
                        'Formulario creado correctamente.',
                );
            },
            onError: (serverErrors) => {
                Object.values(serverErrors).forEach((value) => {
                    toast.error(value as string);
                });
            },
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Crear Formulario" />

            <div className="mb-15">
                <Button
                    onClick={() => router.visit(forms.index())}
                    variant="outline"
                >
                    <ChevronLeft />
                    Volver
                </Button>
            </div>

            <form
                className="mx-auto grid w-full max-w-3xl grid-cols-1 gap-6"
                onSubmit={handleSubmit}
            >
                <div className="grid gap-2">
                    <Label htmlFor="title">Titulo</Label>
                    <Input
                        id="title"
                        value={data.title}
                        onChange={(event) =>
                            setData('title', event.target.value)
                        }
                    />
                    <InputError message={errors.title} />
                </div>

                <div className="grid gap-2">
                    <Label htmlFor="description">Descripcion</Label>
                    <Textarea
                        id="description"
                        value={data.description}
                        onChange={(event) =>
                            setData('description', event.target.value)
                        }
                    />
                    <InputError message={errors.description} />
                </div>

                <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div className="grid gap-2">
                        <Label>Tipo de formulario</Label>
                        <Select
                            value={String(data.form_type_id)}
                            onValueChange={(value) =>
                                setData('form_type_id', Number(value))
                            }
                        >
                            <SelectTrigger>
                                <SelectValue placeholder="Selecciona un tipo" />
                            </SelectTrigger>
                            <SelectContent>
                                {types.map((type) => (
                                    <SelectItem
                                        key={type.id}
                                        value={String(type.id)}
                                    >
                                        {type.name}
                                    </SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                        <InputError message={errors.form_type_id} />
                    </div>

                    <div className="grid gap-2">
                        <Label>Visibilidad de resultados</Label>
                        <Select
                            value={data.show_results_to_respondent}
                            onValueChange={(value) =>
                                setData(
                                    'show_results_to_respondent',
                                    value as FormResultsVisibilityValue,
                                )
                            }
                        >
                            <SelectTrigger>
                                <SelectValue placeholder="Selecciona una opcion" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="immediately">
                                    Inmediatamente
                                </SelectItem>
                                <SelectItem value="after_close">
                                    Despues de cerrar
                                </SelectItem>
                                <SelectItem value="never">Nunca</SelectItem>
                            </SelectContent>
                        </Select>
                        <InputError
                            message={errors.show_results_to_respondent}
                        />
                    </div>
                </div>

                <div className="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div className="grid gap-2">
                        <Label htmlFor="max_attempts">Maximo de intentos</Label>
                        <Input
                            id="max_attempts"
                            type="number"
                            min={1}
                            value={data.max_attempts}
                            onChange={(event) =>
                                setData(
                                    'max_attempts',
                                    Number(event.target.value || 1),
                                )
                            }
                        />
                        <InputError message={errors.max_attempts} />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="max_responses">
                            Maximo de respuestas
                        </Label>
                        <Input
                            id="max_responses"
                            type="number"
                            min={1}
                            value={data.max_responses ?? ''}
                            onChange={(event) =>
                                setData(
                                    'max_responses',
                                    event.target.value
                                        ? Number(event.target.value)
                                        : null,
                                )
                            }
                        />
                        <InputError message={errors.max_responses} />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="passing_score">
                            Puntaje de aprobacion
                        </Label>
                        <Input
                            id="passing_score"
                            type="number"
                            min={0}
                            max={100}
                            step="0.01"
                            value={data.passing_score ?? ''}
                            onChange={(event) =>
                                setData(
                                    'passing_score',
                                    event.target.value
                                        ? Number(event.target.value)
                                        : null,
                                )
                            }
                        />
                        <InputError message={errors.passing_score} />
                    </div>
                </div>

                <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div className="grid gap-2">
                        <Label htmlFor="available_from">Disponible desde</Label>
                        <Input
                            id="available_from"
                            type="datetime-local"
                            onChange={(event) =>
                                setData(
                                    'available_from',
                                    toServerDateTime(event.target.value),
                                )
                            }
                        />
                        <InputError message={errors.available_from} />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="available_until">
                            Disponible hasta
                        </Label>
                        <Input
                            id="available_until"
                            type="datetime-local"
                            onChange={(event) =>
                                setData(
                                    'available_until',
                                    toServerDateTime(event.target.value),
                                )
                            }
                        />
                        <InputError message={errors.available_until} />
                    </div>
                </div>

                <div className="grid gap-2">
                    <Label htmlFor="confirmation_message">
                        Mensaje de confirmacion
                    </Label>
                    <Textarea
                        id="confirmation_message"
                        value={data.confirmation_message ?? ''}
                        onChange={(event) =>
                            setData(
                                'confirmation_message',
                                event.target.value || null,
                            )
                        }
                    />
                    <InputError message={errors.confirmation_message} />
                </div>

                <div className="grid gap-2">
                    <Label htmlFor="redirect_url">URL de redireccion</Label>
                    <Input
                        id="redirect_url"
                        value={data.redirect_url ?? ''}
                        onChange={(event) =>
                            setData('redirect_url', event.target.value || null)
                        }
                    />
                    <InputError message={errors.redirect_url} />
                </div>

                <div className="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div className="flex items-center gap-2 rounded-md border p-3">
                        <Switch
                            checked={data.requires_login}
                            onCheckedChange={(value) =>
                                setData('requires_login', value)
                            }
                        />
                        <Label>Requiere sesion</Label>
                    </div>

                    <div className="flex items-center gap-2 rounded-md border p-3">
                        <Switch
                            checked={data.allow_multiple_responses}
                            onCheckedChange={(value) =>
                                setData('allow_multiple_responses', value)
                            }
                        />
                        <Label>Multiples respuestas</Label>
                    </div>

                    <div className="flex items-center gap-2 rounded-md border p-3">
                        <Switch
                            checked={data.collect_email}
                            onCheckedChange={(value) =>
                                setData('collect_email', value)
                            }
                        />
                        <Label>Solicitar email</Label>
                    </div>

                    <div className="flex items-center gap-2 rounded-md border p-3">
                        <Switch
                            checked={data.show_progress_bar}
                            onCheckedChange={(value) =>
                                setData('show_progress_bar', value)
                            }
                        />
                        <Label>Barra de progreso</Label>
                    </div>

                    <div className="flex items-center gap-2 rounded-md border p-3">
                        <Switch
                            checked={data.shuffle_sections}
                            onCheckedChange={(value) =>
                                setData('shuffle_sections', value)
                            }
                        />
                        <Label>Mezclar secciones</Label>
                    </div>

                    <div className="flex items-center gap-2 rounded-md border p-3">
                        <Switch
                            checked={data.is_quiz_mode}
                            onCheckedChange={(value) =>
                                setData('is_quiz_mode', value)
                            }
                        />
                        <Label>Modo quiz</Label>
                    </div>

                    <div className="flex items-center gap-2 rounded-md border p-3">
                        <Switch
                            checked={data.randomize_questions}
                            onCheckedChange={(value) =>
                                setData('randomize_questions', value)
                            }
                        />
                        <Label>Aleatorizar preguntas</Label>
                    </div>

                    <div className="flex items-center gap-2 rounded-md border p-3">
                        <Switch
                            checked={data.randomize_options}
                            onCheckedChange={(value) =>
                                setData('randomize_options', value)
                            }
                        />
                        <Label>Aleatorizar opciones</Label>
                    </div>

                    <div className="flex items-center gap-2 rounded-md border p-3">
                        <Switch
                            checked={data.show_correct_answers}
                            onCheckedChange={(value) =>
                                setData('show_correct_answers', value)
                            }
                        />
                        <Label>Mostrar respuestas correctas</Label>
                    </div>

                    <div className="flex items-center gap-2 rounded-md border p-3">
                        <Switch
                            checked={data.show_feedback_after}
                            onCheckedChange={(value) =>
                                setData('show_feedback_after', value)
                            }
                        />
                        <Label>Mostrar retroalimentacion</Label>
                    </div>
                </div>

                <Button type="submit" disabled={processing}>
                    Crear Formulario
                </Button>
            </form>
        </AppLayout>
    );
}
