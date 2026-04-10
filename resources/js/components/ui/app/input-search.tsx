import { Search } from 'lucide-react';
import { Input } from '@/components/ui/shadcn/input';

type InputSearchProps = {
    search: string;
    setSearch: (search: string) => void;
};

export default function InputSearch({
    search,
    setSearch,
}: InputSearchProps) {
    return (
        <div className="relative mb-6 w-full max-w-sm">
            <Search className="pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2 text-muted-foreground" />
            <Input
                value={search}
                onChange={(event) => setSearch(event.target.value)}
                placeholder="Buscar..."
                className="pl-9"
                type="search"
                name="search"
                id="search"
            />
        </div>
    );
}
