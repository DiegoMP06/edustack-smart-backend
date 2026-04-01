import { Link } from '@inertiajs/react';
import type { PropsWithChildren } from 'react';
import Heading from '@/components/ui/app/heading';
import { Avatar, AvatarFallback } from '@/components/ui/shadcn/avatar';
import { Button } from '@/components/ui/shadcn/button';
import { Separator } from '@/components/ui/shadcn/separator';
import { useCurrentUrl } from '@/hooks/app/use-current-url';
import { useInitials } from '@/hooks/use-initials';
import { cn, getIdealResponsiveMediaLink, toUrl } from '@/lib/utils';
import events from '@/routes/events';
import type { Event, NavItem } from '@/types';

const sidebarNavItems: (event: Event) => NavItem[] = (event) => [
    {
        title: 'Información',
        href: events.show(event.id),
        icon: null,
    },
    {
        title: 'Actividades',
        href: events.activities.index(event.id),
        icon: null,
    },
];

export default function EventLayout({
    children,
    event,
}: PropsWithChildren<{
    event: Event;
}>) {
    const getInitials = useInitials();
    const { isCurrentOrParentUrl } = useCurrentUrl();

    if (typeof window === 'undefined') {
        return null;
    }

    return (
        <div>
            <div className="mx-auto max-w-4xl">
                <div className="mx-auto">
                    <img
                        src={
                            event.media.at(0)?.is_processed
                                ? getIdealResponsiveMediaLink(
                                      event.media.at(0)!,
                                  )
                                : event.media.at(0)?.urls.original
                        }
                        alt={event.name}
                        className="mx-auto block aspect-square w-full max-w-40 rounded-full shadow-md"
                        width={800}
                        height={800}
                    />
                </div>

                <h1 className="mt-4 mb-6 text-center text-3xl leading-normal font-bold text-pretty text-foreground">
                    {event.name}
                </h1>

                <div className="flex flex-col items-center gap-4">
                    <div className="flex flex-wrap items-center justify-center gap-4">
                        <div className="flex items-center gap-2">
                            <Avatar className="h-8 w-8 overflow-hidden rounded-full">
                                <AvatarFallback className="rounded-lg bg-indigo-200 text-indigo-700 dark:bg-neutral-700 dark:text-white">
                                    {getInitials(
                                        event.author?.name +
                                            ' ' +
                                            event.author?.father_last_name +
                                            ' ' +
                                            event.author?.mother_last_name,
                                    )}
                                </AvatarFallback>
                            </Avatar>
                            <div className="grid flex-1 text-left text-sm leading-tight">
                                <span className="truncate font-medium">
                                    {event.author?.name}{' '}
                                    {event.author?.father_last_name}{' '}
                                    {event.author?.mother_last_name}
                                </span>
                                <span className="truncate text-xs text-accent-foreground">
                                    {event.author?.email}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <p className="mt-8 mb-10 text-center leading-normal whitespace-pre-wrap text-muted-foreground">
                    {event.summary}
                </p>
            </div>

            <Heading
                title="Evento"
                description="Administra la configuración de tu evento"
            />

            <div className="flex flex-col lg:flex-row lg:space-x-12">
                <aside className="w-full max-w-xl lg:w-48">
                    <nav
                        className="flex flex-col space-y-1 space-x-0"
                        aria-label="Evento"
                    >
                        {sidebarNavItems(event).map((item, index) => (
                            <Button
                                key={`${toUrl(item.href)}-${index}`}
                                size="sm"
                                variant="ghost"
                                asChild
                                className={cn('w-full justify-start', {
                                    'bg-muted': isCurrentOrParentUrl(item.href),
                                })}
                            >
                                <Link href={item.href}>
                                    {item.icon && (
                                        <item.icon className="h-4 w-4" />
                                    )}
                                    {item.title}
                                </Link>
                            </Button>
                        ))}
                    </nav>
                </aside>

                <Separator className="my-6 lg:hidden" />

                <div className="flex-1 md:max-w-6xl">
                    <section className="space-y-12">{children}</section>
                </div>
            </div>
        </div>
    );
}
