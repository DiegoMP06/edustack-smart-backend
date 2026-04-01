import { Head, router } from '@inertiajs/react';
import { ChevronLeft } from 'lucide-react';
import { useState } from 'react';
import type { SubmitHandler } from 'react-hook-form';
import { useForm } from 'react-hook-form';
import { toast } from 'sonner';
import AssignmentForm from '@/components/classroom/AssignmentForm';
import type {AssignmentFormData} from '@/components/classroom/AssignmentForm';
import { Button } from '@/components/ui/shadcn/button';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import type { Course, CourseLesson } from '@/types/classroom';

type CreateAssignmentProps = {
    course: Course;
    lessons: CourseLesson[];
};

const breadcrumbs = (course: Course): BreadcrumbItem[] => [
    { title: 'Cursos', href: '/classroom/courses' },
    { title: course.name, href: `/classroom/courses/${course.id}` },
    {
        title: 'Nueva tarea',
        href: `/classroom/courses/${course.id}/assignments/create`,
    },
];

export default function CreateAssignment({
    course,
    lessons,
}: CreateAssignmentProps) {
    const [processing, setProcessing] = useState(false);

    const {
        control,
        register,
        formState: { errors },
        handleSubmit,
    } = useForm<AssignmentFormData>({
        defaultValues: {
            name: '',
            summary: '',
            instructions: '[]',
            max_score: 100,
            passing_score: 60,
            allow_late_submissions: false,
            max_attempts: 1,
            submission_type: 'file',
            is_published: false,
            due_date: '',
            available_from: '',
            course_lesson_id: null,
        },
    });

    const handleCreateAssignment: SubmitHandler<AssignmentFormData> = (
        data,
    ) => {
        setProcessing(true);
        router.post(
            `/classroom/courses/${course.id}/assignments`,
            {
                ...data,
                instructions: (() => {
                    try {
                        const parsed = JSON.parse(data.instructions || '[]');

                        return Array.isArray(parsed) ? parsed : [];
                    } catch {
                        return [];
                    }
                })(),
                course_lesson_id: data.course_lesson_id || null,
                due_date: data.due_date || null,
                available_from: data.available_from || null,
            } as never,
            {
                preserveScroll: true,
                showProgress: true,
                onSuccess(params) {
                    toast.success(params.props.message as string);
                },
                onFinish() {
                    setProcessing(false);
                },
                onError(error) {
                    Object.values(error).forEach((value) => toast.error(value));
                },
            },
        );
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs(course)}>
            <Head title="Nueva tarea" />

            <div className="mb-15">
                <Button
                    onClick={() =>
                        router.visit(`/classroom/courses/${course.id}/edit`)
                    }
                >
                    <ChevronLeft />
                    Volver
                </Button>
            </div>

            <form
                className="mx-auto grid w-full max-w-2xl grid-cols-1 gap-6"
                onSubmit={handleSubmit(handleCreateAssignment)}
            >
                <AssignmentForm
                    lessons={lessons}
                    control={control}
                    register={register}
                    errors={errors}
                />
                <Button type="submit" disabled={processing}>
                    Crear tarea
                </Button>
            </form>
        </AppLayout>
    );
}
