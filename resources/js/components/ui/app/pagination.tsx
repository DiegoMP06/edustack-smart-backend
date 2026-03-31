import { router } from '@inertiajs/react';
import { Button } from '@/components/ui/shadcn/button';
import type { PaginationType } from '@/types/ui';

type QueryParams = Record<string, string | number | boolean | null | undefined>;

type PaginationProps<T = unknown> = {
    pagination: PaginationType<T>;
    queryParams?: QueryParams;
};

export default function Pagination<T>({
    pagination,
    queryParams = {},
}: PaginationProps<T>) {
    if (pagination.meta.last_page <= 1) {
        return null;
    }

    return (
        <nav className="mt-8 flex flex-wrap items-center gap-2">
            {pagination.meta.links.map((link, index) => (
                <Button
                    key={`${link.label}-${index}`}
                    size="sm"
                    variant={link.active ? 'default' : 'outline'}
                    disabled={link.page === null}
                    onClick={() => {
                        if (link.page === null) {
                            return;
                        }

                        router.get(
                            window.location.pathname,
                            {
                                ...queryParams,
                                page: link.page,
                            },
                            {
                                preserveState: true,
                                preserveScroll: true,
                                replace: true,
                            },
                        );
                    }}
                >
                    <span dangerouslySetInnerHTML={{ __html: link.label }} />
                </Button>
            ))}
        </nav>
    );
}
