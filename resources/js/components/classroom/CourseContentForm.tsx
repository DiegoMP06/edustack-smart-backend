import { router } from '@inertiajs/react';
import { toast } from 'sonner';
import PuckInput from '@/components/puck/PuckInput';
import usePuckContent from '@/hooks/puck/usePuckContent';
import { db } from '@/lib/dexie';
import { classroomPuckConfig } from '@/lib/puck';
import type { Course } from '@/types/classroom';

type CourseContentFormProps = {
    course: Course;
    edit: boolean;
};

export default function CourseContentForm({
    course,
    edit,
}: CourseContentFormProps) {
    const {
        setProcessing,
        initialData,
        content,
        DBId,
        debouncedSaveDB,
        processing,
    } = usePuckContent({
        contentType: 'classroom',
        itemId: course.id,
        title: course.name,
        serverContent: course.content,
    });

    const handleSaveChangesToServer = async () => {
        setProcessing(true);

        router.patch(
            `/classroom/courses/${course.id}/content`,
            { content, edit } as never,
            {
                preserveScroll: true,
                showProgress: true,
                forceFormData: false,
                onSuccess: async (data) => {
                    await db.contents.delete(DBId);
                    toast.success(data.props.message as string);
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

    return initialData ? (
        <PuckInput
            config={classroomPuckConfig}
            initialData={initialData}
            onChange={(data) => debouncedSaveDB(data.content)}
            processing={processing}
            handleSaveChangesToServer={handleSaveChangesToServer}
        />
    ) : null;
}
