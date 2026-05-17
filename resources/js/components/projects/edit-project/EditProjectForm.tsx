import { router } from '@inertiajs/react';
import { useState } from 'react';
import type { SubmitHandler } from 'react-hook-form';
import { useForm } from 'react-hook-form';
import { toast } from 'sonner';
import ProjectForm from '@/components/projects/ProjectForm';
import { Button } from '@/components/ui/shadcn/button';
import type {
    DraftProjectFormData,
    ProjectCategoryData,
    ProjectData,
    ProjectStatusData,
} from '@/generated/types/App/Modules/Projects/DTOs';
import projects from '@/routes/projects';

type EditProjectFormProps = {
    project: ProjectData;
    statuses: ProjectStatusData[];
    categories: ProjectCategoryData[];
};

export default function EditProjectForm({
    project,
    statuses,
    categories,
}: EditProjectFormProps) {
    const [processing, setProcessing] = useState(false);
    const initialValues: DraftProjectFormData = {
        name: project.name,
        description: project.description,
        repository_url: project.repository_url,
        demo_url: project.demo_url,
        tech_stack: project.tech_stack,
        version: project.version,
        license: project.license,
        project_status_id: project.project_status_id,
        categories: project.categories?.map((category) => category.id) || [],
    };

    const { handleSubmit, control, register } = useForm({
        defaultValues: initialValues,
    });

    const handleEditProject: SubmitHandler<DraftProjectFormData> = (data) => {
        setProcessing(true);
        router.put(projects.update(project.id), data, {
            forceFormData: true,
            preserveScroll: true,
            showProgress: true,
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
        <form
            onSubmit={handleSubmit(handleEditProject)}
            className="mx-auto grid w-full max-w-2xl grid-cols-1 gap-6"
        >
            <ProjectForm
                categories={categories}
                statuses={statuses}
                control={control}
                register={register}
            />

            <Button type="submit" disabled={processing}>
                Guardar Cambios
            </Button>
        </form>
    );
}
