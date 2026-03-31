import {
    createContext,
    useCallback,
    useContext,
    useRef,
    useState,
    type ReactNode,
} from 'react';
import ConfirmDialog from '@/components/ui/app/confirm-dialog';

type ConfirmDialogOptions = {
    title: string;
    description: string;
    confirmLabel?: string;
    cancelLabel?: string;
    confirmVariant?:
    | 'default'
    | 'destructive'
    | 'outline'
    | 'secondary'
    | 'ghost'
    | 'link'
    | null;
};

type ConfirmDialogContextValue = (
    options: ConfirmDialogOptions,
) => Promise<boolean>;

const ConfirmDialogContext = createContext<ConfirmDialogContextValue | null>(
    null,
);

export function ConfirmDialogProvider({ children }: { children: ReactNode }) {
    const [dialogOptions, setDialogOptions] =
        useState<ConfirmDialogOptions | null>(null);
    const resolverRef = useRef<((confirmed: boolean) => void) | null>(null);

    const closeWithResult = useCallback((result: boolean) => {
        const resolver = resolverRef.current;

        resolverRef.current = null;
        setDialogOptions(null);

        if (resolver) {
            resolver(result);
        }
    }, []);

    const confirm = useCallback((options: ConfirmDialogOptions) => {
        return new Promise<boolean>((resolve) => {
            resolverRef.current = resolve;
            setDialogOptions(options);
        });
    }, []);

    return (
        <ConfirmDialogContext.Provider value={confirm}>
            {children}

            <ConfirmDialog
                open={dialogOptions !== null}
                onOpenChange={(open) => {
                    if (!open) {
                        closeWithResult(false);
                    }
                }}
                onConfirm={() => closeWithResult(true)}
                title={dialogOptions?.title ?? ''}
                description={dialogOptions?.description ?? ''}
                confirmLabel={dialogOptions?.confirmLabel}
                cancelLabel={dialogOptions?.cancelLabel}
                confirmVariant={dialogOptions?.confirmVariant}
            />
        </ConfirmDialogContext.Provider>
    );
}

export function useConfirmDialog(): ConfirmDialogContextValue {
    const context = useContext(ConfirmDialogContext);

    if (!context) {
        throw new Error('useConfirmDialog must be used within ConfirmDialogProvider');
    }

    return context;
}
