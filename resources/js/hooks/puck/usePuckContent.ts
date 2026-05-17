import type { Content, Data } from '@puckeditor/core';
import { useCallback, useEffect, useMemo, useRef, useState } from 'react';
import { useDebouncedCallback } from 'use-debounce';
import { useConfirmDialog } from '@/components/ui/app/confirm-dialog-provider';
import { db } from '@/lib/dexie';
import type { ComponentProps } from '@/lib/puck';

type ContentTypes =
    | 'posts'
    | 'projects'
    | 'events'
    | 'event-activities'
    | 'competition-rounds'
    | 'classroom';

type UsePuckContentProps = {
    contentType: ContentTypes;
    itemId: number;
    title: string;
    serverContent: Content<ComponentProps>;
};

export default function usePuckContent({
    contentType,
    itemId,
    title,
    serverContent,
}: UsePuckContentProps) {
    const [initialData, setInitialData] = useState<Data<ComponentProps> | null>(
        null,
    );
    const [content, setContent] = useState<Content<ComponentProps>>([]);
    const [processing, setProcessing] = useState(false);
    const confirmDialog = useConfirmDialog();

    const confirmDialogRef = useRef(confirmDialog);
    const serverContentRef = useRef(serverContent);
    const titleRef = useRef(title);
    const initialized = useRef(false);

    useEffect(() => {
        confirmDialogRef.current = confirmDialog;
    }, [confirmDialog]);

    useEffect(() => {
        serverContentRef.current = serverContent;
    }, [serverContent]);

    useEffect(() => {
        titleRef.current = title;
    }, [title]);

    const DBId = useMemo(
        () => `${contentType}_${itemId}`,
        [contentType, itemId],
    );

    const DBIdRef = useRef(DBId);
    useEffect(() => {
        DBIdRef.current = DBId;
    }, [DBId]);

    const handleSaveToIndexDB = useCallback(
        async (data: Content<ComponentProps>) => {
            await db.contents.put({
                id: DBId,
                content: data,
                updatedAt: Date.now(),
            });
            setContent(data);
        },
        [DBId],
    );

    const debouncedSaveDB = useDebouncedCallback(handleSaveToIndexDB, 1000);

    useEffect(() => {
        return () => {
            debouncedSaveDB.flush();
        };
    }, [debouncedSaveDB]);

    useEffect(() => {
        if (initialized.current) {
            return;
        }

        initialized.current = true;

        (async () => {
            const id = DBIdRef.current;
            const localPost = await db.contents.get(id);

            if (localPost && localPost.content.length > 0) {
                const shouldUseLocalContent = await confirmDialogRef.current({
                    title: 'Contenido local sin guardar',
                    description:
                        'Encontramos contenido local pendiente. ¿Quieres continuar con ese contenido?',
                    confirmLabel: 'Usar contenido local',
                    cancelLabel: 'Descartar y continuar',
                    confirmVariant: 'default',
                });

                if (shouldUseLocalContent) {
                    const t = titleRef.current;
                    setInitialData({
                        root: { props: { title: t } },
                        content: localPost.content,
                    });
                    setContent(localPost.content);
                } else {
                    await db.contents.delete(id);
                    const t = titleRef.current;
                    const sc = serverContentRef.current;
                    setInitialData({
                        root: { props: { title: t } },
                        content: sc,
                    });
                    setContent(sc);
                }
            } else {
                const t = titleRef.current;
                const sc = serverContentRef.current;
                setInitialData({
                    root: { props: { title: t } },
                    content: sc,
                });
                setContent(sc);
            }
        })();
    }, []);

    return {
        DBId,
        initialData,
        content,
        processing,
        setProcessing,
        debouncedSaveDB,
    };
}
