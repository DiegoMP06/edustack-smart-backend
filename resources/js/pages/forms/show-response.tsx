import { Head, router, useForm } from '@inertiajs/react';
import { ChevronLeft, Save } from 'lucide-react';
import type { FormEvent } from 'react';
import { toast } from 'sonner';
import { Badge } from '@/components/ui/shadcn/badge';
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
import { Switch } from '@/components/ui/shadcn/switch';
import { Textarea } from '@/components/ui/shadcn/textarea';
import AppLayout from '@/layouts/app-layout';
import { formatDatetimeToLocale } from '@/lib/utils';
import forms from '@/routes/forms';
import type { BreadcrumbItem } from '@/types';
import type { Form, FormResponse, FormResponseAnswer } from '@/types/forms';

type ShowResponseProps = {
    form: Form;
    response: FormResponse;
};

type GradePayload = {
    is_correct: boolean | null;
    score_awarded: number;
    feedback: string;
};

const breadcrumbs = (form: Form, response: FormResponse): BreadcrumbItem[] => [
    { title: 'Formularios', href: forms.index().url },
    { title: form.title, href: forms.show(form.id).url },
    { title: 'Respuestas', href: `/forms/${form.id}/responses` },
    {
        title: `Respuesta #${response.id}`,
        href: `/forms/${form.id}/responses/${response.id}`,
    },
];

export default function ShowResponse({ form, response }: ShowResponseProps) {
    const answers = response.answers ?? [];

    return (
        <AppLayout breadcrumbs={breadcrumbs(form, response)}>
            <Head title={`Respuesta #${response.id}`} />

            <div className="mb-6">
                <Button
                    variant="outline"
                    onClick={() => router.visit(`/forms/${form.id}/responses`)}
                >
                    <ChevronLeft />
                    Volver
                </Button>
            </div>

            <div className="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">
                <Card>
                    <CardHeader>
                        <CardTitle>Estado</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <Badge>{response.status}</Badge>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Progreso</CardTitle>
                    </CardHeader>
                    <CardContent className="space-y-1 text-sm">
                        <p>Intento: {response.attempt_number}</p>
                        <p>
                            Iniciado:{' '}
                            {formatDatetimeToLocale(response.started_at)}
                        </p>
                        <p>
                            Enviado:{' '}
                            {response.submitted_at
                                ? formatDatetimeToLocale(response.submitted_at)
                                : 'Sin envio'}
                        </p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Resultado</CardTitle>
                    </CardHeader>
                    <CardContent className="space-y-1 text-sm">
                        <p>Puntaje: {response.score ?? 'Pendiente'}</p>
                        <p>Maximo: {response.max_score ?? 'Pendiente'}</p>
                        <p>Porcentaje: {response.percentage ?? 'Pendiente'}</p>
                        <p>
                            Aprobado:{' '}
                            {response.passed === null
                                ? 'N/A'
                                : response.passed
                                  ? 'Si'
                                  : 'No'}
                        </p>
                    </CardContent>
                </Card>
            </div>

            <div className="grid gap-4">
                {answers.length > 0 ? (
                    answers.map((answer) => (
                        <AnswerGradeCard
                            key={answer.id}
                            form={form}
                            response={response}
                            answer={answer}
                        />
                    ))
                ) : (
                    <p className="py-12 text-center text-accent-foreground">
                        No hay respuestas registradas para este intento.
                    </p>
                )}
            </div>
        </AppLayout>
    );
}

function AnswerGradeCard({
    form,
    response,
    answer,
}: {
    form: Form;
    response: FormResponse;
    answer: FormResponseAnswer;
}) {
    const gradingForm = useForm<GradePayload>({
        is_correct: answer.is_correct,
        score_awarded: Number(answer.score_awarded ?? 0),
        feedback: answer.feedback ?? '',
    });

    const submitGrade = (event: FormEvent<HTMLFormElement>): void => {
        event.preventDefault();

        gradingForm.patch(
            forms.responses.answers.update({
                form: form.id,
                response: response.id,
                answer: answer.id,
            }).url,
            {
                preserveScroll: true,
                onSuccess: (page) => {
                    toast.success(page.props.message as string);
                },
                onError: (errors) => {
                    Object.values(errors).forEach((value) =>
                        toast.error(value as string),
                    );
                },
            },
        );
    };

    return (
        <Card>
            <CardHeader>
                <CardTitle>
                    {answer.question?.title ||
                        `Pregunta #${answer.form_question_id}`}
                </CardTitle>
                <CardDescription>
                    Tipo: {answer.question?.question_type || 'N/A'}
                </CardDescription>
            </CardHeader>
            <CardContent className="grid gap-4">
                <div className="grid grid-cols-1 gap-3 text-sm md:grid-cols-2">
                    <p>Texto: {answer.text_answer || '---'}</p>
                    <p>Numero: {answer.number_answer ?? '---'}</p>
                    <p>Fecha: {answer.date_answer || '---'}</p>
                    <p>Hora: {answer.time_answer || '---'}</p>
                    <p>Fecha y hora: {answer.datetime_answer || '---'}</p>
                    <p>Omitida: {answer.was_skipped ? 'Si' : 'No'}</p>
                    <p className="md:col-span-2">
                        Opciones seleccionadas:{' '}
                        {answer.selected_option_ids?.length
                            ? answer.selected_option_ids.join(', ')
                            : '---'}
                    </p>
                    <p className="md:col-span-2">
                        Respuesta estructurada:{' '}
                        {answer.structured_answer
                            ? JSON.stringify(answer.structured_answer)
                            : '---'}
                    </p>
                </div>

                <form
                    className="grid gap-3 rounded-md border p-4"
                    onSubmit={submitGrade}
                >
                    <div className="flex items-center gap-2">
                        <Switch
                            checked={Boolean(gradingForm.data.is_correct)}
                            onCheckedChange={(value) =>
                                gradingForm.setData('is_correct', value)
                            }
                        />
                        <Label>Respuesta correcta</Label>
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor={`score-${answer.id}`}>
                            Puntaje otorgado
                        </Label>
                        <Input
                            id={`score-${answer.id}`}
                            type="number"
                            min={0}
                            step="0.01"
                            value={gradingForm.data.score_awarded}
                            onChange={(event) =>
                                gradingForm.setData(
                                    'score_awarded',
                                    Number(event.target.value || 0),
                                )
                            }
                        />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor={`feedback-${answer.id}`}>
                            Retroalimentacion
                        </Label>
                        <Textarea
                            id={`feedback-${answer.id}`}
                            value={gradingForm.data.feedback}
                            onChange={(event) =>
                                gradingForm.setData(
                                    'feedback',
                                    event.target.value,
                                )
                            }
                        />
                    </div>

                    <Button type="submit" disabled={gradingForm.processing}>
                        <Save />
                        Guardar calificacion
                    </Button>
                </form>
            </CardContent>
        </Card>
    );
}
