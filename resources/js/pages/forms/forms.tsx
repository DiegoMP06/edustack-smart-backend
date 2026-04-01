import { Head, router } from '@inertiajs/react';
import { Plus } from 'lucide-react';
import { useState } from 'react';
import { toast } from 'sonner';
import FormItem from '@/components/forms/FormItem';
import ConfirmDialog from '@/components/ui/app/confirm-dialog';
import Pagination from '@/components/ui/app/pagination';
import { Button } from '@/components/ui/shadcn/button';
import AppLayout from '@/layouts/app-layout';
import forms from '@/routes/forms';
import type { BreadcrumbItem, PaginationType } from '@/types';
import type { Form } from '@/types/forms';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Formularios',
        href: forms.index().url,
    },
];

type FormsPageProps = {
    forms: PaginationType<Form>;
    filter?: Record<string, string>;
};

export default function FormsPage({
    forms: formsPagination,
    filter,
}: FormsPageProps) {
    const [processingId, setProcessingId] = useState<number | null>(null);
    const [formToDelete, setFormToDelete] = useState<Form | null>(null);

    const handleStatus = (form: Form): void => {
        setProcessingId(form.id);
        router.patch(
            forms.status(form.id).url,
            {},
            {
                preserveScroll: true,
                showProgress: true,
                onSuccess: (page) => {
                    toast.success(page.props.message as string);
                },
                onError: (errors) => {
                    Object.values(errors).forEach((value) => {
                        toast.error(value as string);
                    });
                },
                onFinish: () => {
                    setProcessingId(null);
                },
            },
        );
    };

    const handleDelete = (): void => {
        if (!formToDelete) {
            return;
        }

        setProcessingId(formToDelete.id);
        router.delete(forms.destroy(formToDelete.id).url, {
            preserveScroll: true,
            showProgress: true,
            onSuccess: (page) => {
                toast.success(page.props.message as string);
            },
            onError: (errors) => {
                Object.values(errors).forEach((value) => {
                    toast.error(value as string);
                });
            },
            onFinish: () => {
                setProcessingId(null);
                setFormToDelete(null);
            },
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs} collectionName="forms" withSearch>
            <Head title="Formularios" />

            <div className="mb-15">
                <Button onClick={() => router.visit(forms.create())}>
                    <Plus />
                    Crear Formulario
                </Button>
            </div>

            {formsPagination.data.length > 0 ? (
                <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    {formsPagination.data.map((form) => (
                        <FormItem
                            key={form.id}
                            form={form}
                            processing={processingId === form.id}
                            onStatus={handleStatus}
                            onDelete={setFormToDelete}
                        />
                    ))}
                </div>
            ) : (
                <p className="my-20 text-center text-accent-foreground">
                    No hay formularios
                </p>
            )}

            <Pagination
                pagination={formsPagination}
                queryParams={{ ...(filter ?? {}) }}
            />

            <ConfirmDialog
                open={Boolean(formToDelete)}
                onOpenChange={(open) => {
                    if (!open) {
                        setFormToDelete(null);
                    }
                }}
                onConfirm={handleDelete}
                title="Eliminar formulario"
                description="Esta accion eliminara el formulario de forma permanente."
                confirmLabel="Si, eliminar"
                confirmDisabled={
                    formToDelete !== null && processingId === formToDelete.id
                }
            />
        </AppLayout>
    );
}
