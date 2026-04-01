import { Head, Link, router } from '@inertiajs/react';
import {
    ChevronLeft,
    Edit,
    FileText,
    GitBranch,
    MessageSquareText,
    ScrollText,
} from 'lucide-react';
import { Badge } from '@/components/ui/shadcn/badge';
import { Button } from '@/components/ui/shadcn/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/shadcn/card';
import AppLayout from '@/layouts/app-layout';
import { formatDatetimeToLocale } from '@/lib/utils';
import forms from '@/routes/forms';
import logicRules from '@/routes/forms/logic-rules';
import questions from '@/routes/forms/questions';
import responses from '@/routes/forms/responses';
import sections from '@/routes/forms/sections';
import type { BreadcrumbItem } from '@/types';
import type {
    Form,
    FormLogicRule,
    FormQuestion,
    FormResponse,
    FormSection,
} from '@/types/forms';

type ShowFormProps = {
    form: Form;
};

const breadcrumbs = (form: Form): BreadcrumbItem[] => [
    { title: 'Formularios', href: forms.index().url },
    { title: form.title, href: forms.show(form.id).url },
];

const getSections = (form: Form): FormSection[] =>
    form.sections ?? form.form_sections ?? [];
const getQuestions = (form: Form): FormQuestion[] =>
    form.questions ?? form.form_questions ?? [];
const getLogicRules = (form: Form): FormLogicRule[] =>
    form.logicRules ?? form.logic_rules ?? [];
const getResponses = (form: Form): FormResponse[] =>
    form.responses ?? form.form_responses ?? [];

export default function ShowForm({ form }: ShowFormProps) {
    const sectionsList = getSections(form);
    const questionsList = getQuestions(form);
    const logicRulesList = getLogicRules(form);
    const responsesList = getResponses(form);

    return (
        <AppLayout breadcrumbs={breadcrumbs(form)}>
            <Head title={form.title} />

            <div className="mb-15 flex flex-wrap gap-3">
                <Button
                    variant="outline"
                    onClick={() => router.visit(forms.index())}
                >
                    <ChevronLeft />
                    Volver
                </Button>

                <Button onClick={() => router.visit(forms.edit(form.id))}>
                    <Edit />
                    Editar
                </Button>
            </div>

            <div className="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <div className="space-y-6 lg:col-span-2">
                    <Card>
                        <CardHeader>
                            <CardTitle className="flex items-center gap-2">
                                <FileText className="size-5" />
                                {form.title}
                            </CardTitle>
                            <CardDescription>
                                {form.description ||
                                    'Este formulario no tiene descripcion.'}
                            </CardDescription>
                        </CardHeader>
                        <CardContent className="flex flex-wrap gap-2">
                            <Badge variant="outline">
                                {form.type?.name ||
                                    form.form_type?.name ||
                                    'Tipo de formulario'}
                            </Badge>
                            <Badge
                                className={
                                    form.is_published
                                        ? 'bg-green-100 text-green-800'
                                        : 'bg-red-100 text-red-800'
                                }
                            >
                                {form.is_published ? 'Publicado' : 'Oculto'}
                            </Badge>
                            <Badge
                                variant={
                                    form.is_active ? 'default' : 'destructive'
                                }
                            >
                                {form.is_active ? 'Activo' : 'Inactivo'}
                            </Badge>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle>Configuracion</CardTitle>
                        </CardHeader>
                        <CardContent className="grid grid-cols-1 gap-3 text-sm md:grid-cols-2">
                            <p>
                                Requiere sesion:{' '}
                                {form.requires_login ? 'Si' : 'No'}
                            </p>
                            <p>
                                Permite multiples respuestas:{' '}
                                {form.allow_multiple_responses ? 'Si' : 'No'}
                            </p>
                            <p>
                                Maximo de respuestas:{' '}
                                {form.max_responses ?? 'Sin limite'}
                            </p>
                            <p>Maximo de intentos: {form.max_attempts}</p>
                            <p>
                                Puntaje de aprobacion:{' '}
                                {form.passing_score ?? 'No aplica'}
                            </p>
                            <p>Modo quiz: {form.is_quiz_mode ? 'Si' : 'No'}</p>
                            <p>
                                Disponible desde:{' '}
                                {form.available_from
                                    ? formatDatetimeToLocale(
                                          form.available_from,
                                      )
                                    : 'Sin fecha'}
                            </p>
                            <p>
                                Disponible hasta:{' '}
                                {form.available_until
                                    ? formatDatetimeToLocale(
                                          form.available_until,
                                      )
                                    : 'Sin fecha'}
                            </p>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle>Resumen de estructura</CardTitle>
                        </CardHeader>
                        <CardContent className="grid grid-cols-1 gap-2 text-sm md:grid-cols-2">
                            <p>Secciones: {sectionsList.length}</p>
                            <p>Preguntas: {questionsList.length}</p>
                            <p>Reglas de logica: {logicRulesList.length}</p>
                            <p>
                                Respuestas registradas: {responsesList.length}
                            </p>
                        </CardContent>
                    </Card>
                </div>

                <aside className="space-y-6">
                    <Card>
                        <CardHeader>
                            <CardTitle>Gestionar formulario</CardTitle>
                            <CardDescription>
                                Accede rapido a los modulos del formulario.
                            </CardDescription>
                        </CardHeader>
                        <CardContent className="grid grid-cols-1 gap-2">
                            <Button variant="outline" asChild>
                                <Link href={sections.index(form.id)}>
                                    <ScrollText />
                                    Secciones
                                </Link>
                            </Button>

                            <Button variant="outline" asChild>
                                <Link href={questions.index(form.id)}>
                                    <FileText />
                                    Preguntas
                                </Link>
                            </Button>

                            <Button variant="outline" asChild>
                                <Link href={logicRules.index(form.id)}>
                                    <GitBranch />
                                    Logica
                                </Link>
                            </Button>

                            <Button variant="outline" asChild>
                                <Link href={responses.index(form.id)}>
                                    <MessageSquareText />
                                    Respuestas
                                </Link>
                            </Button>
                        </CardContent>
                    </Card>
                </aside>
            </div>
        </AppLayout>
    );
}
