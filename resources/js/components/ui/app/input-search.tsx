import { router } from '@inertiajs/react';
import { Search } from 'lucide-react';
import { useEffect, useState } from 'react';
import { Input } from '@/components/ui/shadcn/input';

type QueryParams = Record<string, string | number | boolean | null | undefined>;

type InputSearchProps = {
    search?: string;
    queryParams?: QueryParams;
};

export default function InputSearch({
    search = '',
    queryParams = {},
}: InputSearchProps) {
    const [searchValue, setSearchValue] = useState(search);

    useEffect(() => {
        const timeout = setTimeout(() => {
            router.get(
                window.location.pathname,
                {
                    ...queryParams,
                    page: 1,
                    search: searchValue || undefined,
                },
                {
                    preserveState: true,
                    preserveScroll: true,
                    replace: true,
                },
            );
        }, 350);

        return () => clearTimeout(timeout);
    }, [queryParams, searchValue]);

    return (
        <div className="relative mb-6 w-full max-w-sm">
            <Search className="pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2 text-muted-foreground" />
            <Input
                value={searchValue}
                onChange={(event) => setSearchValue(event.target.value)}
                placeholder="Buscar..."
                className="pl-9"
            />
        </div>
    );
}
