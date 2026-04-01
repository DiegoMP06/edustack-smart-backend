import { Head, router } from '@inertiajs/react';
import { ChevronLeft } from 'lucide-react';
import { useState } from 'react';
import type { SubmitHandler } from 'react-hook-form';
import { Controller, useForm } from 'react-hook-form';
import { toast } from 'sonner';
import CourseForm from '@/components/classroom/CourseForm';
import DropzoneInput from '@/components/dropzone/DropzoneInput';
import InputError from '@/components/ui/app/input-error';
import { Button } from '@/components/ui/shadcn/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/shadcn/card';
import { Label } from '@/components/ui/shadcn/label';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import type {
    Course,
    CourseCategory,
    CourseFormData,
    CourseStatus,
} from '@/types/classroom';

type EditCourseProps = {
    course: Course;
    statuses: CourseStatus[];
    categories: CourseCategory[];
};

const breadcrumbs = (course: Course): BreadcrumbItem[] => [
    { title: 'Cursos', href: '/classroom/courses' },
    { title: course.name, href: `/classroom/courses/${course.id}` },
    { title: 'Editar', href: `/classroom/courses/${course.id}/edit` },
];

export default function EditCourse({
    course,
    statuses,
    categories,
}: EditCourseProps) {
    const [processing, setProcessing] = useState(false);

    const {
        control,
        register,
        formState: { errors },
        handleSubmit,
    } = useForm<CourseFormData>({
        defaultValues: {
            name: course.name,
            cover: [],
            summary: course.summary,
            code: course.code ?? '',
            credits: course.credits,
            period: course.period ?? '',
            price: Number(course.price),
            is_free: course.is_free,
            capacity: course.capacity,
            course_status_id: course.course_status_id,
            course_category_id: course.course_category_id,
            start_date: course.start_date ?? '',
            end_date: course.end_date ?? '',
            enrollment_start_date: course.enrollment_start_date ?? '',
            enrollment_end_date: course.enrollment_end_date ?? '',
            is_published: course.is_published,
        },
    });

    const handleUpdateCourse: SubmitHandler<CourseFormData> = (data) => {
        setProcessing(true);

        router.patch(
            `/classroom/courses/${course.id}`,
            {
                ...data,
                cover: data.cover.length > 0 ? data.cover[0] : null,
                course_category_id: data.course_category_id || null,
                capacity: data.capacity || null,
                price: data.is_free ? 0 : data.price,
            },
            {
                preserveScroll: true,
                showProgress: true,
                forceFormData: true,
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

    const handleStatus = () => {
        setProcessing(true);
        router.patch(
            `/classroom/courses/${course.id}/status`,
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
        <AppLayout breadcrumbs={breadcrumbs(course)}>
            <Head title={`Editar ${course.name}`} />

            <div className="mb-15">
                <Button onClick={() => router.visit('/classroom/courses')}>
                    <ChevronLeft />
                    Volver
                </Button>
            </div>

            <div className="grid grid-cols-1 gap-12 lg:grid-cols-2 lg:gap-10">
                <form
                    className="mx-auto grid w-full max-w-2xl grid-cols-1 gap-6"
                    onSubmit={handleSubmit(handleUpdateCourse)}
                >
                    <CourseForm
                        statuses={statuses}
                        categories={categories}
                        register={register}
                        control={control}
                        errors={errors}
                    />

                    <div className="grid gap-2">
                        <Label htmlFor="cover">Portada</Label>
                        <Controller
                            name="cover"
                            control={control}
                            render={({ field: { value, onChange } }) => (
                                <DropzoneInput
                                    value={value || []}
                                    onChange={onChange}
                                    defaultImage={
                                        course.media?.[0]?.urls?.original
                                    }
                                />
                            )}
                        />
                        <InputError message={errors.cover?.message} />
                    </div>

                    <Button type="submit" disabled={processing}>
                        Guardar cambios
                    </Button>
                </form>

                <aside className="mx-auto flex w-full max-w-2xl flex-col gap-6">
                    <Card>
                        <CardHeader>
                            <CardTitle>Editar contenido</CardTitle>
                            <CardDescription>
                                Gestiona el contenido enriquecido del curso con
                                el editor visual.
                            </CardDescription>
                        </CardHeader>
                        <CardFooter>
                            <Button
                                variant="outline"
                                onClick={() =>
                                    router.visit(
                                        `/classroom/courses/${course.id}/content/edit?edit=1`,
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
                            <CardTitle>Lecciones y tareas</CardTitle>
                            <CardDescription>
                                Agrega y organiza lecciones y tareas del curso.
                            </CardDescription>
                        </CardHeader>
                        <CardContent className="flex flex-wrap gap-2">
                            <Button
                                variant="outline"
                                onClick={() =>
                                    router.visit(
                                        `/classroom/courses/${course.id}/lessons/create`,
                                    )
                                }
                                disabled={processing}
                            >
                                Nueva leccion
                            </Button>
                            <Button
                                variant="outline"
                                onClick={() =>
                                    router.visit(
                                        `/classroom/courses/${course.id}/assignments/create`,
                                    )
                                }
                                disabled={processing}
                            >
                                Nueva tarea
                            </Button>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle>Estado del curso</CardTitle>
                            <CardDescription>
                                Activa o desactiva la publicacion del curso.
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <p>
                                Estado actual:
                                <strong
                                    className={
                                        course.is_published
                                            ? 'text-green-600'
                                            : 'text-red-600'
                                    }
                                >
                                    {course.is_published
                                        ? ' Publicado'
                                        : ' Oculto'}
                                </strong>
                            </p>
                        </CardContent>
                        <CardFooter>
                            <Button
                                variant={
                                    course.is_published
                                        ? 'destructive'
                                        : 'outline'
                                }
                                onClick={handleStatus}
                                disabled={processing}
                            >
                                {course.is_published
                                    ? 'Ocultar curso'
                                    : 'Publicar curso'}
                            </Button>
                        </CardFooter>
                    </Card>
                </aside>
            </div>
        </AppLayout>
    );
}
