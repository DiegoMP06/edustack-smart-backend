import { Head } from '@inertiajs/react';
import UserItem from '@/components/admin/users/UserItem';
import Pagination from '@/components/ui/app/pagination';
import type {
    RoleData,
    UserData,
} from '@/generated/types/App/Modules/Admin/DTOs';
import type { ListCollectionQueryParamsData } from '@/generated/types/App/Modules/Shared/DTOs/Query';
import AppLayout from '@/layouts/app-layout';
import users from '@/routes/admin/users';
import type { BreadcrumbItem, PaginationType } from '@/types';

type UsersProps = {
    users: PaginationType<UserData>;
    roles: RoleData[];
    filter: ListCollectionQueryParamsData['filter'];
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Usuarios',
        href: users.index().url,
    },
];

export default function Users({ users, filter, roles }: UsersProps) {
    return (
        <AppLayout breadcrumbs={breadcrumbs} withSearch collectionName="users">
            <Head title="Usuarios" />

            {users.data.length > 0 ? (
                <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                    {users.data.map((user) => (
                        <UserItem key={user.id} user={user} roles={roles} />
                    ))}
                </div>
            ) : (
                <p className="my-20 text-center text-accent-foreground">
                    No Hay Usuarios
                </p>
            )}

            <Pagination pagination={users} queryParams={{ ...filter }} />
        </AppLayout>
    );
}
