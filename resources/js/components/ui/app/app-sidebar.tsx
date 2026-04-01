import { Link, usePage } from '@inertiajs/react';
import {
    CalendarDays,
    ClipboardList,
    FileCode,
    Folder,
    GraduationCap,
    LayoutGrid,
    NotebookPen,
    Users2,
} from 'lucide-react';
import AppLogo from '@/components/ui/app/app-logo';
import { NavFooter } from '@/components/ui/app/nav-footer';
import { NavMain } from '@/components/ui/app/nav-main';
import { NavUser } from '@/components/ui/app/nav-user';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/shadcn/sidebar';
import { dashboard } from '@/routes';
import events from '@/routes/events';
import forms from '@/routes/forms';
import posts from '@/routes/posts';
import projects from '@/routes/projects';
import users from '@/routes/admin/users';
import type { NavItem } from '@/types/navigation';
import { useMemo } from 'react';


const defaultNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
];

const studentNavItems: NavItem[] = [
    {
        title: 'Classroom',
        href: '/classroom/courses',
        icon: GraduationCap,
    },
    {
        title: 'Proyectos',
        href: projects.index({ query: { filter: { search: '' } } }),
        icon: FileCode,
    },
    {
        title: 'Formularios',
        href: forms.index({ query: { filter: { search: '' } } }),
        icon: ClipboardList,
    },
]

const teacherNavItems: NavItem[] = [
    {
        title: 'Blog',
        href: posts.index({ query: { filter: { search: '' } } }),
        icon: NotebookPen,
    },
]

const memberNavItems: NavItem[] = [
    {
        title: 'Eventos',
        href: events.index({ query: { filter: { search: '' } } }),
        icon: CalendarDays,
    },
]

const adminNavItems: NavItem[] = [
    {
        title: 'Usuarios',
        href: users.index({ query: { filter: { search: '' } } }),
        icon: Users2,
    },
]

const footerNavItems: NavItem[] = [
    {
        title: 'Repository',
        href: 'https://github.com/DiegoMP06/innotech-app.git',
        icon: Folder,
    },
];

export function AppSidebar() {
    const { auth } = usePage().props;


    const mainNavItems = useMemo(() => {
        if (auth.user.roles.some((role) => role.name === 'admin')) {
            return [...defaultNavItems, ...studentNavItems, ...teacherNavItems, ...memberNavItems, ...adminNavItems]
        }

        if (auth.user.roles.some((role) => role.name === 'member')) {
            return [...defaultNavItems, ...studentNavItems, ...teacherNavItems, ...memberNavItems]
        }

        if (auth.user.roles.some((role) => role.name === 'teacher')) {
            return [...defaultNavItems, ...studentNavItems, ...teacherNavItems]
        }

        if (auth.user.roles.some((role) => role.name === 'student')) {
            return [...defaultNavItems, ...studentNavItems]
        }

        return defaultNavItems
    }, [auth.user])

    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild className="flex items-center [&_svg]:size-6">
                            <Link href={dashboard()} prefetch >
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={mainNavItems} />
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNavItems} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
