import '@puckeditor/core/puck.css';

import type { Config, Data } from '@puckeditor/core';
import { Puck } from '@puckeditor/core';
import { Save } from 'lucide-react';
import { puckConfig } from '@/lib/puck';
import type { ComponentProps } from '@/lib/puck';
import { cn } from '@/lib/utils';

type PuckInputProps = {
    initialData: Partial<Data | Data<ComponentProps>>;
    onChange: (data: Data<ComponentProps>) => void;
    processing: boolean;
    handleSaveChangesToServer: () => void;
    handlePublish?: () => void;
    isPublished?: boolean;
    config?: Config;
};

export default function PuckInput({
    initialData,
    onChange,
    processing,
    handleSaveChangesToServer,
    handlePublish,
    isPublished,
    config = puckConfig,
}: PuckInputProps) {
    return (
        <div className="dark:text-indigo-700 bg-background">
            <Puck
                config={config}
                data={initialData}
                onChange={(data) => onChange(data as Data<ComponentProps>)}
                overrides={{
                    headerActions: () => (
                        <div className="flex items-center gap-2">
                            {processing && (
                                <span className="animate-pulse text-xs text-muted-foreground">
                                    Guardando…
                                </span>
                            )}

                            <button
                                onClick={handleSaveChangesToServer}
                                disabled={processing}
                                type="button"
                                className="flex cursor-pointer items-center gap-2 rounded bg-indigo-200 px-3 py-1.5 text-sm font-bold text-indigo-800 transition-colors hover:bg-indigo-300 disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                <Save className="size-6" />
                                Guardar borrador
                            </button>

                            {handlePublish && (
                                <button
                                    onClick={handlePublish}
                                    disabled={processing}
                                    type="button"
                                    className={cn(
                                        'cursor-pointer rounded px-3 py-1.5 text-sm font-bold transition-colors disabled:cursor-not-allowed disabled:opacity-50',
                                        isPublished
                                            ? 'bg-red-100 text-red-800 hover:bg-red-200'
                                            : 'bg-indigo-700 text-white hover:bg-indigo-800',
                                    )}
                                >
                                    {isPublished ? 'Ocultar' : 'Publicar'}
                                </button>
                            )}
                        </div>
                    ),
                }}
            />
        </div>
    );
}
