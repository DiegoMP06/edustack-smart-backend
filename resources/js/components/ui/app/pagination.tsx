import { router } from '@inertiajs/react';
import { Button } from '@/components/ui/shadcn/button';
import type { LinkMetaPagination, PaginationType } from '@/types/ui';

type QueryParams = Record<string, string | number | boolean | null | undefined>;

type PaginationProps<T = unknown> = {
    pagination: PaginationType<T>;
    queryParams?: QueryParams;
    withVirtualDOM?: boolean
    handleSearch?: (page: number) => void
};

export default function Pagination<T>({
    pagination,
    queryParams = {},
    withVirtualDOM,
    handleSearch
}: PaginationProps<T>) {
    if (pagination.meta.last_page <= 1) {
        return null;
    }

    const handleClickButton = (link: LinkMetaPagination) => {
        if (link.url === null || link.active) {
            return;
        }

        if (withVirtualDOM) {
            handleClickWithVirtualDOM(link.url);
            return;
        }

        handleClickInertia(link.url);
    }

    const handleClickInertia = (url: string) => {
        router.get(
            url,
            {
                ...queryParams,
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            },
        );
    }

    const handleClickWithVirtualDOM = (url: string) => {
        const urlObj = new URL(url);
        const page = Number(urlObj.searchParams.get('page') || 1);

        if (handleSearch) {
            handleSearch(isNaN(page) ? 1 : page);
        }
    }

    return (
        <nav className="mt-8 flex flex-wrap items-center gap-2">
            {pagination.meta.links.map((link, index) => (
                <Button
                    key={`${link.label}-${index}`}
                    size="sm"
                    variant={link.active ? 'default' : 'outline'}
                    disabled={link.url === null}
                    onClick={() => handleClickButton(link)}
                >
                    <span dangerouslySetInnerHTML={{ __html: link.label }} />
                </Button>
            ))}
        </nav>
    );
}
