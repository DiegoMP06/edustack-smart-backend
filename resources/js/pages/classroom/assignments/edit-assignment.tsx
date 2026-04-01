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
import type { Assignment, Course, CourseLesson } from '@/types/classroom';

type EditAssignmentProps = {
    course: Course;
    assignment: Assignment;
    lessons: CourseLesson[];
};

const breadcrumbs = (
    course: Course,
    assignment: Assignment,
): BreadcrumbItem[] => [
    { title: 'Cursos', href: '/classroom/courses' },
    { title: course.name, href: `/classroom/courses/${course.id}` },
    {
        title: assignment.name,
        href: `/classroom/courses/${course.id}/assignments/${assignment.id}/edit`,
    },
];

export default function EditAssignment({
    course,
    assignment,
    lessons,
}: EditAssignmentProps) {
    const [processing, setProcessing] = useState(false);

    const {
        control,
        register,
        formState: { errors },
        handleSubmit,
    } = useForm<AssignmentFormData>({
        defaultValues: {
            name: assignment.name,
            summary: assignment.summary ?? '',
            instructions: JSON.stringify(
                assignment.instructions ?? [],
                null,
                2,
            ),
            max_score: Number(assignment.max_score),
            passing_score: Number(assignment.passing_score),
            allow_late_submissions: assignment.allow_late_submissions,
            max_attempts: assignment.max_attempts,
            submission_type: assignment.submission_type,
            is_published: assignment.is_published,
            due_date: assignment.due_date ?? '',
            available_from: assignment.available_from ?? '',
            course_lesson_id: assignment.course_lesson_id,
        },
    });

    const handleUpdateAssignment: SubmitHandler<AssignmentFormData> = (
        data,
    ) => {
        setProcessing(true);
        router.patch(
            `/classroom/courses/${course.id}/assignments/${assignment.id}`,
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
        <AppLayout breadcrumbs={breadcrumbs(course, assignment)}>
            <Head title={`Editar ${assignment.name}`} />

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
                onSubmit={handleSubmit(handleUpdateAssignment)}
            >
                <AssignmentForm
                    lessons={lessons}
                    control={control}
                    register={register}
                    errors={errors}
                />
                <Button type="submit" disabled={processing}>
                    Guardar cambios
                </Button>
            </form>
        </AppLayout>
    );
}
