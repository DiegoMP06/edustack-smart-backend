import { router } from '@inertiajs/react';
import { toast } from 'sonner';
import PuckInput from '@/components/puck/PuckInput';
import usePuckContent from '@/hooks/puck/usePuckContent';
import { db } from '@/lib/dexie';
import { classroomPuckConfig } from '@/lib/puck';
import type { Course, CourseLesson } from '@/types/classroom';

type LessonContentFormProps = {
    course: Course;
    lesson: CourseLesson;
    edit: boolean;
};

export default function LessonContentForm({
    course,
    lesson,
    edit,
}: LessonContentFormProps) {
    const {
        setProcessing,
        initialData,
        content,
        DBId,
        debouncedSaveDB,
        processing,
    } = usePuckContent({
        contentType: 'classroom',
        itemId: lesson.id,
        title: lesson.name,
        serverContent: lesson.content,
    });

    const handleSaveChangesToServer = async () => {
        setProcessing(true);

        router.patch(
            `/classroom/courses/${course.id}/lessons/${lesson.id}/content`,
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
