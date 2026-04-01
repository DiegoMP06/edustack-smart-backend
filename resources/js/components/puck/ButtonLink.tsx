import type { ComponentConfig } from '@puckeditor/core';

type ButtonLinkProps = {
    label: string;
    href: string;
    variant: 'primary' | 'secondary' | 'outline' | 'ghost' | 'danger';
    size: 'sm' | 'md' | 'lg';
    align: 'left' | 'center' | 'right' | 'full';
    newTab: boolean;
    icon?: string; // emoji o texto corto antes del label
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

const VARIANT: Record<string, string> = {
    primary: 'bg-indigo-700 text-white hover:bg-indigo-800 border-transparent',
    secondary:
        'bg-indigo-200 text-indigo-800 hover:bg-indigo-300 border-transparent',
    outline:
        'bg-transparent text-indigo-700 hover:bg-indigo-50 border border-indigo-400',
    ghost: 'bg-transparent text-foreground hover:bg-accent border-transparent',
    danger: 'bg-red-600 text-white hover:bg-red-700 border-transparent',
};

const SIZE: Record<string, string> = {
    sm: 'px-3 py-1.5 text-sm',
    md: 'px-5 py-2 text-base',
    lg: 'px-7 py-3 text-lg',
};

const ALIGN_WRAP: Record<string, string> = {
    left: 'flex justify-start',
    center: 'flex justify-center',
    right: 'flex justify-end',
    full: 'block',
};

export default function ButtonLink({
    label,
    href,
    variant,
    size,
    align,
    newTab,
    icon,
}: ButtonLinkProps) {
    const safeHref = normalizeHref(href);

    return (
        <div className={`my-4 ${ALIGN_WRAP[align]}`}>
            <a
                href={safeHref}
                target={newTab ? '_blank' : undefined}
                rel={newTab ? 'noopener noreferrer' : undefined}
                className={[
                    'inline-flex cursor-pointer items-center justify-center gap-2 rounded font-semibold transition-colors',
                    align === 'full' ? 'w-full' : '',
                    VARIANT[variant],
                    SIZE[size],
                ].join(' ')}
            >
                {icon && <span className="shrink-0">{icon}</span>}
                {label}
            </a>
        </div>
    );
}

export const ButtonLinkConfig: ComponentConfig<ButtonLinkProps> = {
    label: 'Botón / CTA',
    fields: {
        label: { type: 'text', label: 'Texto del botón' },
        href: { type: 'text', label: 'URL de destino' },
        icon: { type: 'text', label: 'Ícono (emoji, ej: 🔗 📥)' },
        variant: {
            type: 'select',
            label: 'Estilo',
            options: [
                { label: 'Primario', value: 'primary' },
                { label: 'Secundario', value: 'secondary' },
                { label: 'Contorno', value: 'outline' },
                { label: 'Fantasma', value: 'ghost' },
                { label: 'Peligro', value: 'danger' },
            ],
        },
        size: {
            type: 'radio',
            label: 'Tamaño',
            options: [
                { label: 'SM', value: 'sm' },
                { label: 'MD', value: 'md' },
                { label: 'LG', value: 'lg' },
            ],
        },
        align: {
            type: 'radio',
            label: 'Alineación',
            options: [
                { label: 'Izq', value: 'left' },
                { label: 'Centro', value: 'center' },
                { label: 'Der', value: 'right' },
                { label: 'Ancho completo', value: 'full' },
            ],
        },
        newTab: {
            type: 'radio',
            label: 'Abrir en nueva pestaña',
            options: [
                { label: 'No', value: false },
                { label: 'Sí', value: true },
            ],
        },
    },
    defaultProps: {
        label: 'Ver más',
        href: '#',
        icon: '',
        variant: 'primary',
        size: 'md',
        align: 'center',
        newTab: false,
    },
    render: ButtonLink,
};
