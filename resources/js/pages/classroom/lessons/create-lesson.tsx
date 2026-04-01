import { Head, router } from '@inertiajs/react';
import { ChevronLeft } from 'lucide-react';
import { useState } from 'react';
import type { SubmitHandler } from 'react-hook-form';
import { useForm } from 'react-hook-form';
import { toast } from 'sonner';
import LessonForm from '@/components/classroom/LessonForm';
import { Button } from '@/components/ui/shadcn/button';
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

type CreateLessonProps = {
    course: Course;
    sections: CourseSection[];
};

const breadcrumbs = (course: Course): BreadcrumbItem[] => [
    { title: 'Cursos', href: '/classroom/courses' },
    { title: course.name, href: `/classroom/courses/${course.id}` },
    {
        title: 'Nueva leccion',
        href: `/classroom/courses/${course.id}/lessons/create`,
    },
];

export default function CreateLesson({ course, sections }: CreateLessonProps) {
    const [processing, setProcessing] = useState(false);

    const {
        control,
        register,
        formState: { errors },
        handleSubmit,
    } = useForm<LessonFormData>({
        defaultValues: {
            name: '',
            summary: '',
            type: 'text',
            video_url: '',
            video_duration_seconds: 0,
            order: 0,
            estimated_minutes: 10,
            is_published: false,
            is_preview: false,
            course_section_id: sections[0]?.id ?? '',
        },
    });

    const handleCreateLesson: SubmitHandler<LessonFormData> = (data) => {
        setProcessing(true);
        router.post(`/classroom/courses/${course.id}/lessons`, data, {
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
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs(course)}>
            <Head title="Nueva leccion" />

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
                onSubmit={handleSubmit(handleCreateLesson)}
            >
                <LessonForm
                    sections={sections}
                    control={control}
                    register={register}
                    errors={errors}
                />
                <Button type="submit" disabled={processing}>
                    Crear leccion
                </Button>
            </form>
        </AppLayout>
    );
}
