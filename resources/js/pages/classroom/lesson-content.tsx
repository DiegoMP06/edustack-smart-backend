import { Head, router } from '@inertiajs/react';
import { ChevronLeft } from 'lucide-react';
import LessonContentForm from '@/components/classroom/LessonContentForm';
import { Button } from '@/components/ui/shadcn/button';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import type { Course, CourseLesson } from '@/types/classroom';

type LessonContentProps = {
    course: Course;
    lesson: CourseLesson;
    edit: boolean;
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
    {
        title: 'Contenido',
        href: `/classroom/courses/${course.id}/lessons/${lesson.id}/content/edit`,
    },
];

export default function LessonContent({
    course,
    lesson,
    edit,
}: LessonContentProps) {
    return (
        <AppLayout breadcrumbs={breadcrumbs(course, lesson)}>
            <Head title={`Contenido de ${lesson.name}`} />

            {edit ? (
                <div className="mb-15">
                    <Button
                        onClick={() =>
                            router.visit(
                                `/classroom/courses/${course.id}/lessons/${lesson.id}/edit`,
                            )
                        }
                    >
                        <ChevronLeft />
                        Volver
                    </Button>
                </div>
            ) : null}

            <LessonContentForm course={course} lesson={lesson} edit={edit} />
        </AppLayout>
    );
}
