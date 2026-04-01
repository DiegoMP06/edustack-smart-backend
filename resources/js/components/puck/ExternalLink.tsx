import type { ComponentConfig } from '@puckeditor/core';
import { cn } from '@/lib/utils';

type ExternalLinkProps = {
    children: string;
    href: string;
};

function normalizeHref(href: string): string {
    const trimmed = href.trim();

    if (trimmed === '') {
        return '#';
    }

    if (trimmed.startsWith('http://') || trimmed.startsWith('https://')) {
        return trimmed;
    }

    return `https://${trimmed}`;
}

export default function ExternalLink({ children, href }: ExternalLinkProps) {
    const safeHref = normalizeHref(href);

    return (
        <a
            target="_blank"
            rel="noopener noreferrer"
            className={cn(
                'cursor-pointer text-justify text-base leading-relaxed text-sky-400 underline transition-colors hover:text-sky-500',
            )}
            href={safeHref}
        >
            {children}
        </a>
    );
}

export const ExternalLinkConfig: ComponentConfig<ExternalLinkProps> = {
    label: 'Enlace externo',
    fields: {
        children: {
            type: 'text',
            label: 'Texto del enlace',
            placeholder: 'Escribe aquí el texto del enlace...',
        },
        href: {
            type: 'text',
            label: 'URL (https://...)',
            placeholder: 'Escribe aquí la URL del enlace...',
        },
    },
    defaultProps: { children: 'Enlace externo', href: 'https://example.com' },
    render: ExternalLink,
};
