import { Link } from '@inertiajs/react';
import { ArrowLeft, ArrowRight } from 'lucide-react';
import { useState } from 'react';
import AppLogo from '@/components/ui/app/app-logo';
import { NavFooter } from '@/components/ui/app/nav-footer';
import { NavMain } from '@/components/ui/app/nav-main';
import { NavUser } from '@/components/ui/app/nav-user';
import { Button } from '@/components/ui/shadcn/button';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/shadcn/sidebar';
import { useAppSidebarLinks } from '@/hooks/app/useAppSidebarLinks';
import { dashboard } from '@/routes';
import type { NavItem } from '@/types';

type AppSidebarProps = {
    links?: NavItem[];
    label?: string;
};

export function AppSidebar({ label, links }: AppSidebarProps) {
    const [isAppNav, setIsAppNav] = useState(false);
    const { footerNav, mainNav } = useAppSidebarLinks();

    const withExtraNavigation = label && links;

    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton
                            size="lg"
                            asChild
                            className="flex items-center [&_svg]:size-6"
                        >
                            <Link href={dashboard()} prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                {withExtraNavigation ? (
                    <NavMain
                        items={isAppNav ? mainNav() : links}
                        label={
                            isAppNav ? (
                                <Button
                                    variant="ghost"
                                    size="xs"
                                    onClick={() => setIsAppNav(false)}
                                >
                                    {label}
                                    <ArrowRight className="size-4" />
                                </Button>
                            ) : (
                                <Button
                                    variant="ghost"
                                    size="xs"
                                    onClick={() => setIsAppNav(true)}
                                >
                                    <ArrowLeft className="size-4" />
                                    Volver
                                </Button>
                            )
                        }
                    />
                ) : (
                    <NavMain items={mainNav()} />
                )}
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNav()} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
