import type { HTMLAttributes } from 'react';
import { cn } from '@/lib/utils';

export default function InputError({
    message,
    className = '',
    ...props
}: HTMLAttributes<HTMLParagraphElement> & { message?: string }) {
    return message ? (
        <p
            {...props}
            className={cn('text-xs font-bold border-l-8 border-red-700 dark:border-red-100 bg-red-200 dark:bg-red-800 text-left w-full block rounded text-red-700 dark:text-red-100 pl-4 pr-8 py-1.5 ', className)}
        >
            {message}
        </p>
    ) : null;
}
