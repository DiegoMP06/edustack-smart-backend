import { router } from '@inertiajs/react';
import { useState } from 'react';
import type { SubmitHandler } from 'react-hook-form';
import { useForm } from 'react-hook-form';
import { toast } from 'sonner';
import ProjectForm from '@/components/projects/ProjectForm';
import { Button } from '@/components/ui/shadcn/button';
import projects from '@/routes/projects';
import type {
    Project,
    ProjectCategory,
    ProjectFormData,
    ProjectStatus,
} from '@/types/projects';

type EditProjectFormProps = {
    project: Project;
    statuses: ProjectStatus[];
    categories: ProjectCategory[];
};

export default function EditProjectForm({
    project,
    statuses,
    categories,
}: EditProjectFormProps) {
    console.log(project);

    const [processing, setProcessing] = useState(false);
    const initialValues: ProjectFormData = {
        name: project.name,
        summary: project.summary,
        repository_url: project.repository_url,
        demo_url: project.demo_url,
        tech_stack: project.tech_stack,
        version: project.version,
        license: project.license,
        project_status_id: project.project_status_id,
        categories: project.categories.map((category) => category.id),
    };

    const {
        handleSubmit,
        control,
        register,
        formState: { errors },
    } = useForm({
        defaultValues: initialValues,
    });

    const handleEditProject: SubmitHandler<ProjectFormData> = (data) => {
        router.post(
            projects.update(project.id, {
                query: {
                    _method: 'PUT',
                },
            }),
            data,
            {
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
            },
        );
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
                errors={errors}
                register={register}
            />

            <Button type="submit" disabled={processing}>
                Guardar Cambios
            </Button>
        </form>
    );
}
