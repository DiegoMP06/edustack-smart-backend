'use client';
import type { ComponentConfig } from '@puckeditor/core';
import { Check, Copy } from 'lucide-react';
import type { PropsWithChildren } from 'react';
import { useState } from 'react';

type CodeBlockProps = {
    lang: string;
    showCopyButton: boolean;
    children: string;
};

export default function CodeBlock({
    lang = 'code',
    children,
    showCopyButton = true,
}: PropsWithChildren<CodeBlockProps>) {
    const [copied, setCopied] = useState(false);
    const handleCopy = async () => {
        const text = typeof children === 'string' ? children : '';

        if (!text) {
            return;
        }

        await navigator.clipboard.writeText(text);
        setCopied(true);
        setTimeout(() => setCopied(false), 2000);
    };

    return (
        <div className="mx-4 my-6 w-full overflow-hidden rounded border border-purple-500 bg-purple-950">
            <div className="flex items-center justify-between bg-purple-800 px-3 py-1.5">
                <span className="font-mono text-xs font-semibold text-purple-200">
                    {lang}
                </span>
                {showCopyButton && (
                    <button
                        type="button"
                        onClick={handleCopy}
                        className="flex items-center gap-1 rounded px-2 py-0.5 text-xs text-purple-200 transition-colors hover:bg-purple-700"
                    >
                        {copied ? (
                            <>
                                <Check size={11} />
                                <span>Copiado</span>
                            </>
                        ) : (
                            <>
                                <Copy size={11} />
                                <span>Copiar</span>
                            </>
                        )}
                    </button>
                )}
            </div>
            <div className="overflow-x-auto p-4">
                <pre>
                    <code className="font-mono text-sm text-cyan-300">
                        {children}
                    </code>
                </pre>
            </div>
        </div>
    );
}

export const CodeBlockConfig: ComponentConfig<CodeBlockProps> = {
    label: 'Bloque de Código',
    fields: {
        children: {
            type: 'textarea',
            label: 'Código',
            placeholder: 'Escribe aquí tu código...',
        },
        lang: {
            type: 'text',
            label: 'Lenguaje de programación',
            placeholder: 'Escribe aquí el lenguaje de programación...',
        },
        showCopyButton: {
            type: 'radio',
            label: 'Mostrar botón de copiar',
            options: [
                { label: 'Sí', value: true },
                { label: 'No', value: false },
            ],
        },
    },
    defaultProps: {
        children: "console.log('Hello, world!')",
        lang: 'JS',
        showCopyButton: false,
    },
    render: CodeBlock,
};
