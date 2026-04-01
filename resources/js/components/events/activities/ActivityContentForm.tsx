import { router } from '@inertiajs/react';
import { toast } from 'sonner';
import PuckInput from '@/components/puck/PuckInput';
import usePuckContent from '@/hooks/puck/usePuckContent';
import { db } from '@/lib/dexie';
import { eventPuckConfig } from '@/lib/puck';
import events from '@/routes/events';
import type { EventActivity } from '@/types';

type ContentFormProps = {
    activity: EventActivity;
    edit: boolean;
};

export default function ActivityContentForm({ activity, edit }: ContentFormProps) {
    const {
        setProcessing,
        initialData,
        content,
        DBId,
        debouncedSaveDB,
        processing,
    } = usePuckContent({
        contentType: 'event-activities',
        itemId: activity.id,
        title: activity.name,
        serverContent: activity.content,
    });

    const handleSaveChangesToServer = async () => {
        setProcessing(true);

        const formData = {
            content,
            edit,
        };

        router.patch(events.activities.content.update({ activity: activity.id, event: activity.event_id }), formData as never, {
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
        });
    };

    return (
        initialData && (
            <PuckInput
                config={eventPuckConfig}
                initialData={initialData}
                onChange={(data) => debouncedSaveDB(data.content)}
                processing={processing}
                handleSaveChangesToServer={handleSaveChangesToServer}
            />
        )
    );
}

