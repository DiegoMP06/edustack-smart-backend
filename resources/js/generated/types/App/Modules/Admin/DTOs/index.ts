export type RoleData = {
    id: number;
    name: string;
};
export type UpdateUserRoleFormData = {
    role: string;
};
export type UserData = {
    id: number;
    name: string;
    father_last_name: string;
    mother_last_name: string;
    email: string;
    created_at: string;
    updated_at: string;
    is_active: boolean;
    roles: RoleData[] | null;
};
