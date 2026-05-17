import { usePage } from '@inertiajs/react';
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
import { useCallback } from 'react';
import { dashboard } from '@/routes';
import users from '@/routes/admin/users';
import courses from '@/routes/courses';
import events from '@/routes/events';
import forms from '@/routes/forms';
import posts from '@/routes/posts';
import projects from '@/routes/projects';
import type { NavItem } from '@/types';

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
        href: courses.index(),
        icon: GraduationCap,
    },
    {
        title: 'Proyectos',
        href: projects.index(),
        icon: FileCode,
    },
    {
        title: 'Formularios',
        href: forms.index(),
        icon: ClipboardList,
    },
];

const teacherNavItems: NavItem[] = [
    {
        title: 'Blog',
        href: posts.index(),
        icon: NotebookPen,
    },
];

const memberNavItems: NavItem[] = [
    {
        title: 'Eventos',
        href: events.index(),
        icon: CalendarDays,
    },
];

const adminNavItems: NavItem[] = [
    {
        title: 'Usuarios',
        href: users.index(),
        icon: Users2,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Repository',
        href: 'https://github.com/DiegoMP06/innotech-app.git',
        icon: Folder,
    },
];

export function useAppSidebarLinks() {
    const { auth } = usePage().props;

    return {
        mainNav: useCallback(() => {
            if (auth.user.roles.some((role) => role.name === 'admin')) {
                return [
                    ...defaultNavItems,
                    ...studentNavItems,
                    ...teacherNavItems,
                    ...memberNavItems,
                    ...adminNavItems,
                ];
            }

            if (auth.user.roles.some((role) => role.name === 'member')) {
                return [
                    ...defaultNavItems,
                    ...studentNavItems,
                    ...teacherNavItems,
                    ...memberNavItems,
                ];
            }

            if (auth.user.roles.some((role) => role.name === 'teacher')) {
                return [
                    ...defaultNavItems,
                    ...studentNavItems,
                    ...teacherNavItems,
                ];
            }

            if (auth.user.roles.some((role) => role.name === 'student')) {
                return [...defaultNavItems, ...studentNavItems];
            }

            return defaultNavItems;
        }, [auth.user]),
        footerNav: useCallback(() => {
            return footerNavItems;
        }, []),
    };
}
