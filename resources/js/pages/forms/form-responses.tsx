import { Head, Link, router } from '@inertiajs/react';
import { ChevronLeft, Eye } from 'lucide-react';
import Pagination from '@/components/ui/app/pagination';
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
import type { BreadcrumbItem, PaginationType } from '@/types';
import type { Form, FormResponse } from '@/types/forms';

type FormResponsesProps = {
    form: Form;
    responses: PaginationType<FormResponse>;
};

const breadcrumbs = (form: Form): BreadcrumbItem[] => [
    { title: 'Formularios', href: forms.index().url },
    { title: form.title, href: forms.show(form.id).url },
    { title: 'Respuestas', href: `/forms/${form.id}/responses` },
];

export default function FormResponses({ form, responses }: FormResponsesProps) {
    return (
        <AppLayout breadcrumbs={breadcrumbs(form)}>
            <Head title={`Respuestas de ${form.title}`} />

            <div className="mb-6">
                <Button
                    variant="outline"
                    onClick={() => router.visit(forms.edit(form.id))}
                >
                    <ChevronLeft />
                    Volver
                </Button>
            </div>

            <div className="grid gap-4">
                {responses.data.length > 0 ? (
                    responses.data.map((response) => (
                        <Card key={response.id}>
                            <CardHeader>
                                <CardTitle className="flex items-center justify-between gap-2">
                                    <span>Respuesta #{response.id}</span>
                                    <Badge variant="outline">
                                        Intento {response.attempt_number}
                                    </Badge>
                                </CardTitle>
                                <CardDescription>
                                    Estado: <strong>{response.status}</strong>
                                </CardDescription>
                            </CardHeader>
                            <CardContent className="grid grid-cols-1 gap-3 text-sm md:grid-cols-2">
                                <p>
                                    Email:{' '}
                                    {response.respondent_email ||
                                        'No proporcionado'}
                                </p>
                                <p>
                                    Usuario:{' '}
                                    {response.user
                                        ? `${response.user.name} ${response.user.father_last_name}`
                                        : 'Anonimo'}
                                </p>
                                <p>
                                    Iniciado:{' '}
                                    {formatDatetimeToLocale(
                                        response.started_at,
                                    )}
                                </p>
                                <p>
                                    Enviado:{' '}
                                    {response.submitted_at
                                        ? formatDatetimeToLocale(
                                              response.submitted_at,
                                          )
                                        : 'No enviado'}
                                </p>
                                <p>
                                    Calificacion:{' '}
                                    {response.score ?? 'Pendiente'}
                                </p>
                                <p>
                                    Porcentaje:{' '}
                                    {response.percentage ?? 'Pendiente'}
                                </p>

                                <div className="md:col-span-2">
                                    <Button variant="outline" asChild>
                                        <Link
                                            href={`/forms/${form.id}/responses/${response.id}`}
                                        >
                                            <Eye />
                                            Ver respuesta
                                        </Link>
                                    </Button>
                                </div>
                            </CardContent>
                        </Card>
                    ))
                ) : (
                    <p className="py-12 text-center text-accent-foreground">
                        No hay respuestas registradas.
                    </p>
                )}
            </div>

            <Pagination pagination={responses} />
        </AppLayout>
    );
}
