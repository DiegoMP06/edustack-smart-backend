import { Head, router, useForm } from '@inertiajs/react';
import { ChevronLeft, Plus } from 'lucide-react';
import type { FormEvent } from 'react';
import { useState } from 'react';
import { toast } from 'sonner';
import EditableSectionCard from '@/components/forms/EditableSectionCard';
import type { SectionPayload } from '@/components/forms/EditableSectionCard';
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
import { Switch } from '@/components/ui/shadcn/switch';
import { Textarea } from '@/components/ui/shadcn/textarea';
import AppLayout from '@/layouts/app-layout';
import forms from '@/routes/forms';
import sections from '@/routes/forms/sections';
import type { BreadcrumbItem } from '@/types';
import type { Form, FormSection } from '@/types/forms';

type FormSectionsProps = {
    form: Form;
};

const breadcrumbs = (form: Form): BreadcrumbItem[] => [
    { title: 'Formularios', href: forms.index().url },
    { title: form.title, href: forms.show(form.id).url },
    { title: 'Secciones', href: sections.index(form.id).url },
];

const getSections = (form: Form): FormSection[] =>
    form.sections ?? form.form_sections ?? [];

export default function FormSections({ form }: FormSectionsProps) {
    const sectionsList = getSections(form);
    const [sectionToDelete, setSectionToDelete] = useState<FormSection | null>(
        null,
    );

    const createForm = useForm<SectionPayload>({
        title: '',
        description: '',
        order: sectionsList.length,
        is_visible: true,
    });

    const updateSection = (
        section: FormSection,
        payload: SectionPayload,
    ): void => {
        router.patch(
            sections.update({ form: form.id, section: section.id }).url,
            payload,
            {
                preserveScroll: true,
                onSuccess: (page) => {
                    toast.success(page.props.message as string);
                },
                onError: (errors) => {
                    Object.values(errors).forEach((value) => {
                        toast.error(value as string);
                    });
                },
            },
        );
    };

    const createSection = (event: FormEvent<HTMLFormElement>): void => {
        event.preventDefault();
        createForm.post(sections.store(form.id).url, {
            preserveScroll: true,
            onSuccess: (page) => {
                toast.success(
                    (page.props.message as string) ??
                        'Seccion creada correctamente.',
                );
                createForm.reset();
                createForm.setData('is_visible', true);
            },
            onError: (errors) => {
                Object.values(errors).forEach((value) => {
                    toast.error(value as string);
                });
            },
        });
    };

    const deleteSection = (): void => {
        if (!sectionToDelete) {
            return;
        }

        router.delete(
            sections.destroy({ form: form.id, section: sectionToDelete.id })
                .url,
            {
                preserveScroll: true,
                onSuccess: (page) => {
                    toast.success(page.props.message as string);
                },
                onError: (errors) => {
                    Object.values(errors).forEach((value) => {
                        toast.error(value as string);
                    });
                },
                onFinish: () => setSectionToDelete(null),
            },
        );
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs(form)}>
            <Head title={`Secciones de ${form.title}`} />

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
                <Card className="lg:col-span-1">
                    <CardHeader>
                        <CardTitle>Nueva seccion</CardTitle>
                        <CardDescription>
                            Crea una nueva seccion para este formulario.
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <form className="grid gap-4" onSubmit={createSection}>
                            <div className="grid gap-2">
                                <Label htmlFor="new-section-title">
                                    Titulo
                                </Label>
                                <Input
                                    id="new-section-title"
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
                                <Label htmlFor="new-section-description">
                                    Descripcion
                                </Label>
                                <Textarea
                                    id="new-section-description"
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
                                <Label htmlFor="new-section-order">Orden</Label>
                                <Input
                                    id="new-section-order"
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

                            <div className="flex items-center gap-2 rounded-md border p-3">
                                <Switch
                                    checked={createForm.data.is_visible}
                                    onCheckedChange={(value) =>
                                        createForm.setData('is_visible', value)
                                    }
                                />
                                <Label>Visible</Label>
                            </div>

                            <Button
                                type="submit"
                                disabled={createForm.processing}
                            >
                                <Plus />
                                Agregar Seccion
                            </Button>
                        </form>
                    </CardContent>
                </Card>

                <div className="grid gap-4 lg:col-span-2">
                    {sectionsList.length > 0 ? (
                        sectionsList.map((section) => (
                            <EditableSectionCard
                                key={section.id}
                                section={section}
                                onUpdate={updateSection}
                                onDelete={() => setSectionToDelete(section)}
                            />
                        ))
                    ) : (
                        <p className="py-12 text-center text-accent-foreground">
                            No hay secciones registradas.
                        </p>
                    )}
                </div>
            </div>

            <ConfirmDialog
                open={Boolean(sectionToDelete)}
                onOpenChange={(open) => {
                    if (!open) {
                        setSectionToDelete(null);
                    }
                }}
                onConfirm={deleteSection}
                title="Eliminar seccion"
                description="Esta accion eliminara la seccion seleccionada."
                confirmLabel="Si, eliminar"
            />
        </AppLayout>
    );
}
