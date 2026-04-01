import { Head, router, useForm } from '@inertiajs/react';
import { ChevronLeft, Plus } from 'lucide-react';
import type { FormEvent } from 'react';
import { useState } from 'react';
import { toast } from 'sonner';
import EditableQuestionCard from '@/components/forms/EditableQuestionCard';
import type { QuestionPayload } from '@/components/forms/EditableQuestionCard';
import ConfirmDialog from '@/components/ui/app/confirm-dialog';
import { Button } from '@/components/ui/shadcn/button';
import {
    Card,
    CardContent,
    CardDescription,
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
import { FORM_QUESTION_TYPES } from '@/consts/forms';
import AppLayout from '@/layouts/app-layout';
import forms from '@/routes/forms';
import questions from '@/routes/forms/questions';
import type { BreadcrumbItem } from '@/types';
import type {
    Form,
    FormQuestion,
    FormQuestionTypeValue,
    FormSection,
} from '@/types/forms';

type FormQuestionsProps = {
    form: Form;
};

const breadcrumbs = (form: Form): BreadcrumbItem[] => [
    { title: 'Formularios', href: forms.index().url },
    { title: form.title, href: forms.show(form.id).url },
    { title: 'Preguntas', href: questions.index(form.id).url },
];

const getSections = (form: Form): FormSection[] =>
    form.sections ?? form.form_sections ?? [];
const getQuestions = (form: Form): FormQuestion[] =>
    form.questions ?? form.form_questions ?? [];

const questionTypes = Object.values(FORM_QUESTION_TYPES);

export default function FormQuestions({ form }: FormQuestionsProps) {
    const sectionsList = getSections(form);
    const questionsList = getQuestions(form);
    const [questionToDelete, setQuestionToDelete] =
        useState<FormQuestion | null>(null);

    const createForm = useForm<QuestionPayload>({
        title: '',
        description: '',
        question_type: FORM_QUESTION_TYPES.short_text,
        is_required: false,
        is_visible: true,
        order: questionsList.length,
        settings: null,
        has_correct_answer: false,
        score: 1,
        explanation: '',
        form_section_id: null,
    });

    const createQuestion = (event: FormEvent<HTMLFormElement>): void => {
        event.preventDefault();

        createForm.post(questions.store(form.id).url, {
            preserveScroll: true,
            onSuccess: (page) => {
                toast.success(
                    (page.props.message as string) ??
                        'Pregunta creada correctamente.',
                );
                createForm.reset();
                createForm.setData(
                    'question_type',
                    FORM_QUESTION_TYPES.short_text,
                );
                createForm.setData('is_visible', true);
            },
            onError: (errors) => {
                Object.values(errors).forEach((value) => {
                    toast.error(value as string);
                });
            },
        });
    };

    const updateQuestion = (
        question: FormQuestion,
        payload: QuestionPayload,
    ): void => {
        router.patch(
            questions.update({ form: form.id, question: question.id }).url,
            payload,
            {
                preserveScroll: true,
                onSuccess: (page) =>
                    toast.success(page.props.message as string),
                onError: (errors) => {
                    Object.values(errors).forEach((value) =>
                        toast.error(value as string),
                    );
                },
            },
        );
    };

    const deleteQuestion = (): void => {
        if (!questionToDelete) {
            return;
        }

        router.delete(
            questions.destroy({ form: form.id, question: questionToDelete.id })
                .url,
            {
                preserveScroll: true,
                onSuccess: (page) =>
                    toast.success(page.props.message as string),
                onError: (errors) => {
                    Object.values(errors).forEach((value) =>
                        toast.error(value as string),
                    );
                },
                onFinish: () => setQuestionToDelete(null),
            },
        );
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs(form)}>
            <Head title={`Preguntas de ${form.title}`} />

            <div className="mb-6">
                <Button
                    variant="outline"
                    onClick={() => router.visit(forms.edit(form.id))}
                >
                    <ChevronLeft />
                    Volver
                </Button>
            </div>

            <div className="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <Card>
                    <CardHeader>
                        <CardTitle>Nueva pregunta</CardTitle>
                        <CardDescription>
                            Agrega una pregunta al formulario.
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <form className="grid gap-4" onSubmit={createQuestion}>
                            <div className="grid gap-2">
                                <Label>Titulo</Label>
                                <Input
                                    value={createForm.data.title}
                                    onChange={(event) =>
                                        createForm.setData(
                                            'title',
                                            event.target.value,
                                        )
                                    }
                                />
                            </div>

                            <div className="grid gap-2">
                                <Label>Descripcion</Label>
                                <Textarea
                                    value={createForm.data.description}
                                    onChange={(event) =>
                                        createForm.setData(
                                            'description',
                                            event.target.value,
                                        )
                                    }
                                />
                            </div>

                            <div className="grid gap-2">
                                <Label>Tipo</Label>
                                <Select
                                    value={createForm.data.question_type}
                                    onValueChange={(value) =>
                                        createForm.setData(
                                            'question_type',
                                            value as FormQuestionTypeValue,
                                        )
                                    }
                                >
                                    <SelectTrigger>
                                        <SelectValue />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {questionTypes.map((type) => (
                                            <SelectItem key={type} value={type}>
                                                {type}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </div>

                            <div className="grid grid-cols-2 gap-2">
                                <div className="grid gap-2">
                                    <Label>Orden</Label>
                                    <Input
                                        type="number"
                                        min={0}
                                        value={createForm.data.order}
                                        onChange={(event) =>
                                            createForm.setData(
                                                'order',
                                                Number(event.target.value || 0),
                                            )
                                        }
                                    />
                                </div>
                                <div className="grid gap-2">
                                    <Label>Puntaje</Label>
                                    <Input
                                        type="number"
                                        min={0}
                                        step="0.01"
                                        value={createForm.data.score}
                                        onChange={(event) =>
                                            createForm.setData(
                                                'score',
                                                Number(event.target.value || 0),
                                            )
                                        }
                                    />
                                </div>
                            </div>

                            <div className="grid gap-2">
                                <Label>Seccion</Label>
                                <Select
                                    value={
                                        createForm.data.form_section_id
                                            ? String(
                                                  createForm.data
                                                      .form_section_id,
                                              )
                                            : 'none'
                                    }
                                    onValueChange={(value) =>
                                        createForm.setData(
                                            'form_section_id',
                                            value === 'none'
                                                ? null
                                                : Number(value),
                                        )
                                    }
                                >
                                    <SelectTrigger>
                                        <SelectValue />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="none">
                                            Sin seccion
                                        </SelectItem>
                                        {sectionsList.map((section) => (
                                            <SelectItem
                                                key={section.id}
                                                value={String(section.id)}
                                            >
                                                {section.title}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </div>

                            <div className="grid gap-2">
                                <Label>Explicacion</Label>
                                <Textarea
                                    value={createForm.data.explanation}
                                    onChange={(event) =>
                                        createForm.setData(
                                            'explanation',
                                            event.target.value,
                                        )
                                    }
                                />
                            </div>

                            <div className="grid grid-cols-1 gap-2">
                                <div className="flex items-center gap-2 rounded-md border p-3">
                                    <Switch
                                        checked={createForm.data.is_required}
                                        onCheckedChange={(value) =>
                                            createForm.setData(
                                                'is_required',
                                                value,
                                            )
                                        }
                                    />
                                    <Label>Requerida</Label>
                                </div>
                                <div className="flex items-center gap-2 rounded-md border p-3">
                                    <Switch
                                        checked={createForm.data.is_visible}
                                        onCheckedChange={(value) =>
                                            createForm.setData(
                                                'is_visible',
                                                value,
                                            )
                                        }
                                    />
                                    <Label>Visible</Label>
                                </div>
                                <div className="flex items-center gap-2 rounded-md border p-3">
                                    <Switch
                                        checked={
                                            createForm.data.has_correct_answer
                                        }
                                        onCheckedChange={(value) =>
                                            createForm.setData(
                                                'has_correct_answer',
                                                value,
                                            )
                                        }
                                    />
                                    <Label>Tiene respuesta correcta</Label>
                                </div>
                            </div>

                            <Button
                                type="submit"
                                disabled={createForm.processing}
                            >
                                <Plus />
                                Agregar Pregunta
                            </Button>
                        </form>
                    </CardContent>
                </Card>

                <div className="grid gap-4 lg:col-span-2">
                    {questionsList.length > 0 ? (
                        questionsList.map((question) => (
                            <EditableQuestionCard
                                key={question.id}
                                formId={form.id}
                                sections={sectionsList}
                                question={question}
                                onUpdate={updateQuestion}
                                onDelete={() => setQuestionToDelete(question)}
                            />
                        ))
                    ) : (
                        <p className="py-12 text-center text-accent-foreground">
                            No hay preguntas registradas.
                        </p>
                    )}
                </div>
            </div>

            <ConfirmDialog
                open={Boolean(questionToDelete)}
                onOpenChange={(open) => {
                    if (!open) {
                        setQuestionToDelete(null);
                    }
                }}
                onConfirm={deleteQuestion}
                title="Eliminar pregunta"
                description="Esta accion eliminara la pregunta seleccionada."
                confirmLabel="Si, eliminar"
            />
        </AppLayout>
    );
}
