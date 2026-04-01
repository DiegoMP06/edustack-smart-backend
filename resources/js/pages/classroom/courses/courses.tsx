import { Head, router } from '@inertiajs/react';
import { Plus } from 'lucide-react';
import CourseItem from '@/components/classroom/CourseItem';
import Pagination from '@/components/ui/app/pagination';
import { Button } from '@/components/ui/shadcn/button';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem, PaginationType } from '@/types';
import type { Course } from '@/types/classroom';

type CoursesProps = {
    courses: PaginationType<Course>;
    filter: { [key: string]: string };
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Cursos',
        href: '/classroom/courses',
    },
];

export default function Courses({ courses, filter }: CoursesProps) {
    return (
        <AppLayout
            breadcrumbs={breadcrumbs}
            collectionName="courses"
            withSearch
        >
            <Head title="Cursos" />

            <div className="mb-15">
                <Button
                    onClick={() => router.visit('/classroom/courses/create')}
                >
                    <Plus />
                    Crear curso
                </Button>
            </div>

            {courses.data.length > 0 ? (
                <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    {courses.data.map((course) => (
                        <CourseItem key={course.id} course={course} />
                    ))}
                </div>
            ) : (
                <p className="my-20 text-center text-accent-foreground">
                    No hay cursos
                </p>
            )}

            <Pagination pagination={courses} queryParams={{ ...filter }} />
        </AppLayout>
    );
}
