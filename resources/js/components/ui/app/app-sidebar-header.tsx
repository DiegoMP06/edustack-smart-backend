import { Breadcrumbs } from '@/components/ui/app/breadcrumbs';
import { SidebarTrigger } from '@/components/ui/shadcn/sidebar';
import type { BreadcrumbItem as BreadcrumbItemType } from '@/types';
import { Button } from '../shadcn/button';
import { Search } from 'lucide-react';
import SpotlightSearch from './spotlight-search';
import { useState } from 'react';

export function AppSidebarHeader({
    breadcrumbs = [],
    withSearch = false,
    collectionName,
}: {
    breadcrumbs?: BreadcrumbItemType[];
    withSearch?: boolean;
    collectionName?: string;
}) {
    const [isOpen, setIsOpen] = useState(false)

    return (
        <header className="flex h-16 shrink-0 items-center gap-2 border-b border-sidebar-border/50 px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4">
            <div className="flex flex-1 items-center justify-between gap-4">
                <div className="flex items-center gap-2">
                    <SidebarTrigger className="-ml-1" />
                    <Breadcrumbs breadcrumbs={breadcrumbs} />
                </div>

                {(withSearch && collectionName) && (
                    <div className="p-2">
                        <Button variant='ghost' onClick={() => setIsOpen(true)}>
                            <Search className="size-5" />
                            <span className="hidden md:inline">
                                Buscar
                            </span>
                        </Button>

                        <SpotlightSearch
                            collectionName={collectionName}
                            isOpen={isOpen}
                            setIsOpen={setIsOpen}
                        />
                    </div>
                )}
            </div>
        </header>
    );
}
