import { keepPreviousData, useQuery } from "@tanstack/react-query";
import { Plus } from 'lucide-react';
import { useState } from "react";
import { useDebounce } from "use-debounce";
import UsersAPI from "@/API/UsersAPI";
import InputSearch from "@/components/ui/app/input-search";
import Pagination from "@/components/ui/app/pagination";
import { Button } from "@/components/ui/shadcn/button";
import { Item, ItemActions, ItemContent, ItemDescription, ItemTitle } from "@/components/ui/shadcn/item";
import { UsersWithPaginationSchema } from "@/schemas";
import type { UserFromAPI } from "@/types";

type SearchSpeakerProps = {
    setExistingSpeaker: (id: UserFromAPI) => void
}

export default function SearchSpeaker({ setExistingSpeaker }: SearchSpeakerProps) {
    const [search, setSearch] = useState('');
    const [page, setPage] = useState(1);

    const [debouncedSearch] = useDebounce(search, 500);

    const handleSearchChange = (value: string) => {
        setSearch(value);
        setPage(1);
    };

    const handlePageChange = (newPage: number) => {
        setPage(newPage);
    }

    const { data, isLoading, isError, isFetching } = useQuery({
        queryKey: ['search-speaker', debouncedSearch, page],
        queryFn: async () => {
            const response = await UsersAPI.getUsers({ search: debouncedSearch, page });

            return UsersWithPaginationSchema.parse(response);
        },
        placeholderData: keepPreviousData,
    });

    return (
        <>
            <InputSearch search={search} setSearch={handleSearchChange} />

            {isError && <p className="my-20 text-center text-destructive">Ha ocurrido un error</p>}

            {isLoading ? (
                <p className="my-20 text-center text-muted-foreground">Cargando...</p>
            ) : (
                <div className={isFetching ? "opacity-50 transition-opacity" : "transition-opacity"}>
                    {data?.data.length === 0 ? (
                        <p className="my-20 text-center text-accent-foreground">No Hay Usuarios</p>
                    ) : (
                        <div className="grid grid-cols-1 gap-4">
                            {data?.data.map((user) => (
                                <Item key={user.id} variant="outline">
                                    <ItemContent>
                                        <ItemTitle>{user.name} {user.father_last_name} {user.mother_last_name}</ItemTitle>
                                        <ItemDescription>{user.email}</ItemDescription>
                                    </ItemContent>

                                    <ItemActions>
                                        <Button onClick={() => setExistingSpeaker(user)} type="button" variant="default">
                                            <Plus className="size-4" />
                                        </Button>
                                    </ItemActions>
                                </Item>
                            ))}
                        </div>
                    )}
                </div>
            )}

            {data && (
                <Pagination
                    pagination={data}
                    withVirtualDOM
                    handleSearch={handlePageChange}
                />
            )}
        </>
    )
}

