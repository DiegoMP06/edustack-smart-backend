import { Head, router } from '@inertiajs/react';
import { ChevronLeft } from 'lucide-react';
import { useState } from 'react';
import type { SubmitHandler } from 'react-hook-form';
import { Controller, useForm } from 'react-hook-form';
import { toast } from 'sonner';

import DropzoneInput from '@/components/dropzone/DropzoneInput';
import ProjectForm from '@/components/projects/ProjectForm';
import InputError from '@/components/ui/app/input-error';
import { Button } from '@/components/ui/shadcn/button';
import { Label } from '@/components/ui/shadcn/label';
import type {
    DraftProjectFormData,
    ProjectCategoryData,
    ProjectStatusData,
} from '@/generated/types/App/Modules/Projects/DTOs';
import useMediaUpload from '@/hooks/media/useMediaUpload';
import ProjectsLayout from '@/layouts/projects/ProjectsLayout';
import projects from '@/routes/projects';
import type { BreadcrumbItem } from '@/types';

type CreateProjectProps = {
    categories: ProjectCategoryData[];
    statuses: ProjectStatusData[];
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Proyectos',
        href: projects.index().url,
    },
    {
        title: 'Crear Proyecto',
        href: projects.create().url,
    },
];

export default function CreateProject({
    categories,
    statuses,
}: CreateProjectProps) {
    const [processing, setProcessing] = useState(false);

    const initialValues: DraftProjectFormData = {
        name: '',
        description: '',
        repository_url: '',
        demo_url: '',
        tech_stack: [],
        version: '1.0.0',
        license: 'MIT',
        images: [],
        categories: [],
        project_status_id: 1,
    };

    const { uploadImages } = useMediaUpload();

    const {
        control,
        register,
        formState: { errors },
        handleSubmit,
    } = useForm({
        defaultValues: initialValues,
    });

    const handleCreateProject: SubmitHandler<DraftProjectFormData> = async (
        data,
    ) => {
        setProcessing(true);

        const keys = await uploadImages(data.images || []);
        const formData = {
            ...data,
            images: keys,
        };

        router.post(projects.store(), formData, {
            preserveScroll: true,
            showProgress: true,
            forceFormData: true,
            onSuccess: (data) => {
                toast.success(data.props.message as string);
            },
            onFinish: () => setProcessing(false),
            onError: (error) => {
                Object.values(error).forEach((value) => toast.error(value));
            },
        });
    };

    return (
        <ProjectsLayout breadcrumbs={breadcrumbs}>
            <Head title="Crear Proyecto" />

            <div className="mb-15">
                <Button onClick={() => router.visit(projects.index())}>
                    <ChevronLeft />
                    Volver
                </Button>
            </div>

            <form
                className="mx-auto grid w-full max-w-2xl grid-cols-1 gap-6"
                onSubmit={handleSubmit(handleCreateProject)}
            >
                <ProjectForm
                    categories={categories}
                    statuses={statuses}
                    control={control}
                    register={register}
                />

                <div className="grid gap-2">
                    <Label htmlFor="images">Imágenes: </Label>

                    <Controller
                        name="images"
                        control={control}
                        rules={{
                            validate: (value) =>
                                value!.length > 0 ||
                                'Debe seleccionar al menos una imagen',
                        }}
                        render={({ field: { value, onChange } }) => (
                            <DropzoneInput
                                value={value || []}
                                onChange={onChange}
                                multipleFiles
                            />
                        )}
                    />

                    <InputError message={errors.images?.message} />
                </div>

                <Button type="submit" disabled={processing}>
                    Crear Proyecto
                </Button>
            </form>
        </ProjectsLayout>
    );
}
