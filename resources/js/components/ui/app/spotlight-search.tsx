import { useEffect, useRef, useState } from "react";
import { Command, CommandDialog, CommandEmpty, CommandGroup, CommandInput, CommandList } from "@/components/ui/shadcn/command";
import { router, usePage } from "@inertiajs/react";
import { useDebounce } from "use-debounce";

type SpotlightSearchProps = {
    isOpen: boolean,
    setIsOpen: (isOpen: boolean) => void,
    collectionName: string
}
export default function SpotlightSearch({ isOpen, setIsOpen, collectionName }: SpotlightSearchProps) {
    const filters = (usePage().props?.filter as { [key: string]: string }) || {}
    const [search, setSearch] = useState(filters.search || '')
    const [query] = useDebounce(search, 1000)
    const isFirstRender = useRef(true);

    useEffect(() => {
        setSearch(filters.search || '');
    }, [filters.search]);

    useEffect(() => {
        if (isFirstRender.current) {
            isFirstRender.current = false;
            return;
        }

        const currentSearch = filters.search || '';

        if (query !== currentSearch) {
            const filterParams = query.length > 0 ? { search: query } : {};

            router.get(
                window.location.pathname,
                { filter: filterParams },
                {
                    preserveScroll: true,
                    preserveState: true,
                    showProgress: true,
                    replace: true,
                    only: [collectionName],
                })
        }
    }, [query])


    return (
        <CommandDialog
            onOpenChange={setIsOpen}
            open={isOpen}
            title='Buscar...'
            description='Busca cualquier cosa en este módulo...'
        >
            <Command>
                <CommandInput
                    placeholder='Buscar...'
                    onValueChange={setSearch}
                    value={search}
                />
            </Command>
        </CommandDialog>
    )
}