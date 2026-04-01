import { Head, router } from '@inertiajs/react';
import { ChevronLeft } from 'lucide-react';
import { useState } from 'react';
import type { SubmitHandler } from 'react-hook-form';
import { useForm } from 'react-hook-form';
import { toast } from 'sonner';
import LessonForm from '@/components/classroom/LessonForm';
import { Button } from '@/components/ui/shadcn/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/shadcn/card';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import type { Course, CourseLesson, CourseSection } from '@/types/classroom';

type LessonFormData = {
    name: string;
    summary: string;
    type: CourseLesson['type'];
    video_url: string;
    video_duration_seconds: number;
    order: number;
    estimated_minutes: number;
    is_published: boolean;
    is_preview: boolean;
    course_section_id: number | string;
};

type EditLessonProps = {
    course: Course;
    lesson: CourseLesson;
    sections: CourseSection[];
};

const breadcrumbs = (
    course: Course,
    lesson: CourseLesson,
): BreadcrumbItem[] => [
    { title: 'Cursos', href: '/classroom/courses' },
    { title: course.name, href: `/classroom/courses/${course.id}` },
    {
        title: lesson.name,
        href: `/classroom/courses/${course.id}/lessons/${lesson.id}/edit`,
    },
];

export default function EditLesson({
    course,
    lesson,
    sections,
}: EditLessonProps) {
    const [processing, setProcessing] = useState(false);

    const {
        control,
        register,
        formState: { errors },
        handleSubmit,
    } = useForm<LessonFormData>({
        defaultValues: {
            name: lesson.name,
            summary: lesson.summary ?? '',
            type: lesson.type,
            video_url: lesson.video_url ?? '',
            video_duration_seconds: lesson.video_duration_seconds ?? 0,
            order: lesson.order,
            estimated_minutes: lesson.estimated_minutes,
            is_published: lesson.is_published,
            is_preview: lesson.is_preview,
            course_section_id: lesson.course_section_id,
        },
    });

    const handleUpdateLesson: SubmitHandler<LessonFormData> = (data) => {
        setProcessing(true);
        router.patch(
            `/classroom/courses/${course.id}/lessons/${lesson.id}`,
            data,
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

    const handleLessonStatus = () => {
        setProcessing(true);
        router.patch(
            `/classroom/courses/${course.id}/lessons/${lesson.id}/status`,
            {},
            {
                preserveScroll: true,
                showProgress: true,
                onSuccess(params) {
                    toast.success(params.props.message as string);
                },
                onFinish() {
                    setProcessing(false);
                },
            },
        );
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs(course, lesson)}>
            <Head title={`Editar ${lesson.name}`} />

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

            <div className="grid grid-cols-1 gap-12 lg:grid-cols-2 lg:gap-10">
                <form
                    className="mx-auto grid w-full max-w-2xl grid-cols-1 gap-6"
                    onSubmit={handleSubmit(handleUpdateLesson)}
                >
                    <LessonForm
                        sections={sections}
                        control={control}
                        register={register}
                        errors={errors}
                    />
                    <Button type="submit" disabled={processing}>
                        Guardar cambios
                    </Button>
                </form>

                <aside className="mx-auto flex w-full max-w-2xl flex-col gap-6">
                    <Card>
                        <CardHeader>
                            <CardTitle>Editar contenido</CardTitle>
                            <CardDescription>
                                Edita el contenido enriquecido de esta leccion.
                            </CardDescription>
                        </CardHeader>
                        <CardFooter>
                            <Button
                                variant="outline"
                                onClick={() =>
                                    router.visit(
                                        `/classroom/courses/${course.id}/lessons/${lesson.id}/content/edit?edit=1`,
                                    )
                                }
                                disabled={processing}
                            >
                                Editar contenido
                            </Button>
                        </CardFooter>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle>Estado de la leccion</CardTitle>
                            <CardDescription>
                                Publica u oculta esta leccion para estudiantes.
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <p>
                                Estado actual:
                                <strong
                                    className={
                                        lesson.is_published
                                            ? 'text-green-600'
                                            : 'text-red-600'
                                    }
                                >
                                    {lesson.is_published
                                        ? ' Publicada'
                                        : ' Oculta'}
                                </strong>
                            </p>
                        </CardContent>
                        <CardFooter>
                            <Button
                                variant={
                                    lesson.is_published
                                        ? 'destructive'
                                        : 'outline'
                                }
                                onClick={handleLessonStatus}
                                disabled={processing}
                            >
                                {lesson.is_published
                                    ? 'Ocultar leccion'
                                    : 'Publicar leccion'}
                            </Button>
                        </CardFooter>
                    </Card>
                </aside>
            </div>
        </AppLayout>
    );
}
