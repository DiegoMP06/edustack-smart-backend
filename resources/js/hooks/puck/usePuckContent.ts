import type { Content, Data } from '@puckeditor/core';
import { useCallback, useEffect, useMemo, useState } from 'react';
import { useDebouncedCallback } from 'use-debounce';
import { useConfirmDialog } from '@/components/ui/app/confirm-dialog-provider';
import { db } from '@/lib/dexie';
import type { TableContent } from '@/lib/dexie';
import type { ComponentProps } from '@/lib/puck';

type ContentTypes = 'posts' | 'projects' | 'events';

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
    const [initialData, setInitialData] = useState<Partial<
        Data<ComponentProps>
    > | null>(null);
    const [content, setContent] = useState<Content<ComponentProps>>([]);
    const [processing, setProcessing] = useState(false);
    const confirmDialog = useConfirmDialog();

    const DBId = useMemo(
        () => `${contentType}_${itemId}`,
        [contentType, itemId],
    );

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

    const loadLocalContent = useCallback(
        async (localContent: TableContent) => {
            setInitialData({
                root: {
                    props: {
                        title,
                    },
                },
                content: localContent.content,
            });
            setContent(localContent.content);
        },
        [title],
    );

    const loadServerContent = useCallback(() => {
        setInitialData({
            root: {
                props: {
                    title,
                },
            },
            content: serverContent,
        });
        setContent(serverContent);
    }, [serverContent, title]);

    const debouncedSaveDB = useDebouncedCallback(handleSaveToIndexDB, 1000);

    useEffect(() => {
        return () => {
            debouncedSaveDB.flush();
        };
    }, [debouncedSaveDB]);

    useEffect(() => {
        (async () => {
            const localPost = await db.contents.get(DBId);

            if (localPost && localPost.content.length > 0) {
                const shouldUseLocalContent = await confirmDialog({
                    title: 'Contenido local sin guardar',
                    description:
                        'Encontramos contenido local pendiente. ¿Quieres continuar con ese contenido?',
                    confirmLabel: 'Usar contenido local',
                    cancelLabel: 'Descartar y continuar',
                    confirmVariant: 'default',
                });

                if (shouldUseLocalContent) {
                    loadLocalContent(localPost);
                } else {
                    await db.contents.delete(DBId);
                    loadServerContent();
                }
            } else {
                loadServerContent();
            }
        })();
    }, [DBId, confirmDialog, loadLocalContent, loadServerContent]);

    return {
        DBId,
        initialData,
        content,
        processing,
        setProcessing,
        debouncedSaveDB,
    };
}
