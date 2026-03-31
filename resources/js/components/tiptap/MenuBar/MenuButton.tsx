import type { ComponentProps } from 'react';
import { cn } from '@/lib/utils';

type MenuButtonProps = {
    isActive?: boolean;
    tooltip?: string;
} & ComponentProps<'button'>;

export default function MenuButton({
    isActive,
    className,
    children,
    tooltip,
    title,
    ...props
}: MenuButtonProps) {
    return (
        <button
            type="button"
            title={tooltip ?? title}
            aria-label={tooltip ?? title}
            aria-pressed={isActive}
            className={cn(
                'inline-flex h-7 min-w-7 items-center justify-center rounded px-1.5',
                'text-xs font-bold whitespace-nowrap',
                'cursor-pointer disabled:cursor-not-allowed disabled:opacity-40',
                'transition-colors',
                isActive
                    ? 'bg-indigo-700 text-white hover:bg-indigo-800'
                    : 'bg-indigo-200 text-indigo-700 hover:bg-indigo-300',
                className,
            )}
            {...props}
        >
            {children}
        </button>
    );
}
