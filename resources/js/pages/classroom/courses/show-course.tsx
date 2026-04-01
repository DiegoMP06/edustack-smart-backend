import { Head, router } from '@inertiajs/react';
import { Render } from '@puckeditor/core';
import { ChevronLeft } from 'lucide-react';
import GalleryContent from '@/components/ui/app/gallery-content';
import { Badge } from '@/components/ui/shadcn/badge';
import { Button } from '@/components/ui/shadcn/button';
import AppLayout from '@/layouts/app-layout';
import { puckConfig } from '@/lib/puck';
import type { BreadcrumbItem } from '@/types';
import type { Course } from '@/types/classroom';

type ShowCourseProps = {
    course: Course;
};

const breadcrumbs = (course: Course): BreadcrumbItem[] => [
    { title: 'Cursos', href: '/classroom/courses' },
    { title: course.name, href: `/classroom/courses/${course.id}` },
];

export default function ShowCourse({ course }: ShowCourseProps) {
    return (
        <AppLayout breadcrumbs={breadcrumbs(course)}>
            <Head title={course.name} />

            <div className="mb-15">
                <Button onClick={() => router.visit('/classroom/courses')}>
                    <ChevronLeft />
                    Volver
                </Button>
            </div>

            <div className="grid grid-cols-1 gap-10 md:grid-cols-3">
                <div className="md:col-span-2">
                    <main className="mb-10">
                        <h1 className="mt-6 mb-4 text-center text-3xl leading-normal font-bold text-pretty text-foreground">
                            {course.name}
                        </h1>
                        <p className="my-6 text-justify leading-normal whitespace-pre-wrap text-muted-foreground">
                            {course.summary}
                        </p>
                    </main>

                    <GalleryContent
                        media={course.media}
                        alt={course.name}
                        imageKey="main"
                    />

                    <section className="my-10">
                        <Render
                            config={puckConfig}
                            data={{ content: course.content }}
                        />
                    </section>
                </div>

                <aside className="flex flex-col items-center justify-start gap-6 md:items-stretch">
                    {course.status ? (
                        <div>
                            <h3 className="text-lg font-bold">Estado</h3>
                            <p className="mt-2 text-sm text-muted-foreground">
                                {course.status.name}
                            </p>
                        </div>
                    ) : null}

                    {course.category ? (
                        <div>
                            <h3 className="text-lg font-bold">Categoria</h3>
                            <div className="mt-2">
                                <Badge variant="secondary">
                                    {course.category.name}
                                </Badge>
                            </div>
                        </div>
                    ) : null}

                    <div>
                        <h3 className="text-lg font-bold">Datos generales</h3>
                        <div className="mt-2 grid gap-1 text-sm text-muted-foreground">
                            <p>Creditos: {course.credits}</p>
                            <p>Periodo: {course.period || 'No definido'}</p>
                            <p>
                                Precio:{' '}
                                {Number(course.price) > 0
                                    ? `$${course.price}`
                                    : 'Gratuito'}
                            </p>
                            <p>Capacidad: {course.capacity ?? 'Sin limite'}</p>
                        </div>
                    </div>
                </aside>
            </div>
        </AppLayout>
    );
}
