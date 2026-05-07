import { router } from '@inertiajs/react';
import { toast } from 'sonner';
import PuckInput from '@/components/puck/PuckInput';
import usePuckContent from '@/hooks/puck/usePuckContent';
import { db } from '@/lib/dexie';
import { eventPuckConfig } from '@/lib/puck';
import events from '@/routes/events';
import type { CompetitionRound, Event } from '@/types';

type ContentFormProps = {
    round: CompetitionRound;
    eventId: Event['id'];
    edit: boolean;
};

export default function RoundContentForm({ round, edit, eventId }: ContentFormProps) {
    const {
        setProcessing,
        initialData,
        content,
        DBId,
        debouncedSaveDB,
        processing,
    } = usePuckContent({
        contentType: 'competition-rounds',
        itemId: round.id,
        title: round.name,
        serverContent: round.content,
    });

    const handleSaveChangesToServer = async () => {
        setProcessing(true);

        const formData = {
            content,
            edit,
        };

        router.patch(events.activities.rounds.content.update({ round: round.id, event: eventId, activity: round.event_activity_id }), formData as never, {
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

