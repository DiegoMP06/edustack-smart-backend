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
import { Label } from '@/components/ui/shadcn/label';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import type {
    CourseCategory,
    CourseFormData,
    CourseStatus,
} from '@/types/classroom';

type CreateCourseProps = {
    statuses: CourseStatus[];
    categories: CourseCategory[];
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Cursos', href: '/classroom/courses' },
    { title: 'Crear curso', href: '/classroom/courses/create' },
];

export default function CreateCourse({
    statuses,
    categories,
}: CreateCourseProps) {
    const [processing, setProcessing] = useState(false);

    const {
        control,
        register,
        formState: { errors },
        handleSubmit,
    } = useForm<CourseFormData>({
        defaultValues: {
            name: '',
            cover: [],
            summary: '',
            code: '',
            credits: 0,
            period: '',
            price: 0,
            is_free: true,
            capacity: null,
            course_status_id: statuses[0]?.id ?? 1,
            course_category_id: categories[0]?.id ?? null,
            start_date: '',
            end_date: '',
            enrollment_start_date: '',
            enrollment_end_date: '',
            is_published: false,
        },
    });

    const handleCreateCourse: SubmitHandler<CourseFormData> = (data) => {
        setProcessing(true);

        router.post(
            '/classroom/courses',
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

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Crear curso" />

            <div className="mb-15">
                <Button onClick={() => router.visit('/classroom/courses')}>
                    <ChevronLeft />
                    Volver
                </Button>
            </div>

            <form
                className="mx-auto grid w-full max-w-2xl grid-cols-1 gap-6"
                onSubmit={handleSubmit(handleCreateCourse)}
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
                        rules={{
                            validate: (value) =>
                                value.length > 0 ||
                                'Debes seleccionar una portada',
                        }}
                        render={({ field: { value, onChange } }) => (
                            <DropzoneInput
                                value={value || []}
                                onChange={onChange}
                            />
                        )}
                    />
                    <InputError message={errors.cover?.message} />
                </div>

                <Button type="submit" disabled={processing}>
                    Crear curso
                </Button>
            </form>
        </AppLayout>
    );
}
