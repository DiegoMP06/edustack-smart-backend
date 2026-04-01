import { Head, router, useForm } from '@inertiajs/react';
import { ChevronLeft } from 'lucide-react';
import type { FormEvent } from 'react';
import { toast } from 'sonner';
import InputError from '@/components/ui/app/input-error';
import { Button } from '@/components/ui/shadcn/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/shadcn/card';
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
import logicRules from '@/routes/forms/logic-rules';
import questions from '@/routes/forms/questions';
import sections from '@/routes/forms/sections';
import type { BreadcrumbItem } from '@/types';
import type {
    Form,
    FormPayload,
    FormResultsVisibilityValue,
    FormType,
} from '@/types/forms';

type EditFormProps = {
    form: Form;
    types: FormType[];
};

const toServerDateTime = (value: string): string | null => {
    if (!value) {
        return null;
    }

    const withSeconds = value.length === 16 ? `${value}:00` : value;

    return withSeconds.replace('T', ' ');
};

const toInputDateTime = (value: string | null): string => {
    if (!value) {
        return '';
    }

    return value.replace(' ', 'T').slice(0, 16);
};

const breadcrumbs = (form: Form): BreadcrumbItem[] => [
    { title: 'Formularios', href: forms.index().url },
    { title: form.title, href: forms.show(form.id).url },
    { title: 'Editar', href: forms.edit(form.id).url },
];

export default function EditForm({ form, types }: EditFormProps) {
    const { data, setData, patch, processing, errors } = useForm<FormPayload>({
        title: form.title,
        description: form.description ?? '',
        form_type_id: form.form_type_id,
        requires_login: form.requires_login,
        allow_multiple_responses: form.allow_multiple_responses,
        max_responses: form.max_responses,
        collect_email: form.collect_email,
        show_progress_bar: form.show_progress_bar,
        shuffle_sections: form.shuffle_sections,
        available_from: form.available_from,
        available_until: form.available_until,
        confirmation_message: form.confirmation_message,
        redirect_url: form.redirect_url,
        is_quiz_mode: form.is_quiz_mode,
        time_limit_minutes: form.time_limit_minutes,
        max_attempts: form.max_attempts,
        passing_score: form.passing_score,
        randomize_questions: form.randomize_questions,
        randomize_options: form.randomize_options,
        show_results_to_respondent:
            (form.show_results_to_respondent as FormResultsVisibilityValue) ||
            FORM_RESULTS_VISIBILITY.immediately,
        show_correct_answers: form.show_correct_answers,
        show_feedback_after: form.show_feedback_after,
    });

    const handleSubmit = (event: FormEvent<HTMLFormElement>): void => {
        event.preventDefault();

        patch(forms.update(form.id).url, {
            preserveScroll: true,
            onSuccess: (page) => {
                toast.success(
                    (page.props.message as string) ??
                        'Formulario actualizado correctamente.',
                );
            },
            onError: (serverErrors) => {
                Object.values(serverErrors).forEach((value) => {
                    toast.error(value as string);
                });
            },
        });
    };

    const togglePublishStatus = (): void => {
        router.patch(
            forms.status(form.id).url,
            {},
            {
                preserveScroll: true,
                onSuccess: (page) => {
                    toast.success(page.props.message as string);
                },
                onError: (serverErrors) => {
                    Object.values(serverErrors).forEach((value) => {
                        toast.error(value as string);
                    });
                },
            },
        );
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs(form)}>
            <Head title={`Editar ${form.title}`} />

            <div className="mb-15">
                <Button
                    onClick={() => router.visit(forms.index())}
                    variant="outline"
                >
                    <ChevronLeft />
                    Volver
                </Button>
            </div>

            <div className="grid grid-cols-1 gap-10 lg:grid-cols-2">
                <form className="grid gap-6" onSubmit={handleSubmit}>
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
                            <Label>Tipo</Label>
                            <Select
                                value={String(data.form_type_id)}
                                onValueChange={(value) =>
                                    setData('form_type_id', Number(value))
                                }
                            >
                                <SelectTrigger>
                                    <SelectValue />
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
                            <Label>Mostrar resultados</Label>
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
                                    <SelectValue />
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
                            <Label htmlFor="max_attempts">
                                Maximo de intentos
                            </Label>
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
                                Maximo respuestas
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
                                Puntaje aprobacion
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
                            <Label htmlFor="available_from">
                                Disponible desde
                            </Label>
                            <Input
                                id="available_from"
                                type="datetime-local"
                                value={toInputDateTime(data.available_from)}
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
                                value={toInputDateTime(data.available_until)}
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

                    <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
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
                        Guardar Cambios
                    </Button>
                </form>

                <aside className="grid content-start gap-4">
                    <Card>
                        <CardHeader>
                            <CardTitle>Gestionar estructura</CardTitle>
                            <CardDescription>
                                Administra secciones, preguntas y reglas de
                                logica.
                            </CardDescription>
                        </CardHeader>
                        <CardFooter className="grid grid-cols-1 gap-2 sm:grid-cols-2">
                            <Button
                                variant="outline"
                                onClick={() =>
                                    router.visit(sections.index(form.id))
                                }
                            >
                                Secciones
                            </Button>
                            <Button
                                variant="outline"
                                onClick={() =>
                                    router.visit(questions.index(form.id))
                                }
                            >
                                Preguntas
                            </Button>
                            <Button
                                variant="outline"
                                onClick={() =>
                                    router.visit(logicRules.index(form.id))
                                }
                            >
                                Logica
                            </Button>
                            <Button
                                variant="outline"
                                onClick={() =>
                                    router.visit(`/forms/${form.id}/responses`)
                                }
                            >
                                Respuestas
                            </Button>
                        </CardFooter>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle>Estado de publicacion</CardTitle>
                            <CardDescription>
                                Actualmente el formulario esta
                                <strong
                                    className={
                                        form.is_published
                                            ? 'text-green-600'
                                            : 'text-red-600'
                                    }
                                >
                                    {form.is_published
                                        ? ' publicado.'
                                        : ' oculto.'}
                                </strong>
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <Button
                                variant={
                                    form.is_published
                                        ? 'destructive'
                                        : 'outline'
                                }
                                disabled={processing}
                                onClick={togglePublishStatus}
                            >
                                {form.is_published
                                    ? 'Ocultar Formulario'
                                    : 'Publicar Formulario'}
                            </Button>
                        </CardContent>
                    </Card>
                </aside>
            </div>
        </AppLayout>
    );
}
