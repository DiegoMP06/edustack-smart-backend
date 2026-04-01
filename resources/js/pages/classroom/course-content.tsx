import { Head, router } from '@inertiajs/react';
import { ChevronLeft } from 'lucide-react';
import CourseContentForm from '@/components/classroom/CourseContentForm';
import { Button } from '@/components/ui/shadcn/button';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import type { Course } from '@/types/classroom';

type CourseContentProps = {
    course: Course;
    edit: boolean;
};

const breadcrumbs = (course: Course): BreadcrumbItem[] => [
    { title: 'Cursos', href: '/classroom/courses' },
    { title: course.name, href: `/classroom/courses/${course.id}` },
    {
        title: 'Contenido',
        href: `/classroom/courses/${course.id}/content/edit`,
    },
];

export default function CourseContent({ course, edit }: CourseContentProps) {
    return (
        <AppLayout breadcrumbs={breadcrumbs(course)}>
            <Head title={`Contenido del curso ${course.name}`} />

            {edit ? (
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
            ) : null}

            <CourseContentForm course={course} edit={edit} />
        </AppLayout>
    );
}
