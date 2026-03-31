import type { ROLES } from '@/consts/roles';

export type Roles = keyof typeof ROLES;
export type RolesValues = (typeof ROLES)[Roles];

export type Role = {
    id: number;
    name: Roles;
    guard_name: string;
    created_at: string;
    updated_at: string;
};

export type User = {
    id: number;
    name: string;
    father_last_name: string;
    mother_last_name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    two_factor_enabled?: boolean;
    is_active: boolean;
    created_at: string;
    updated_at: string;
    roles: Role[];
    [key: string]: unknown;
};

export type Auth = {
    user: User;
};

export type TwoFactorSetupData = {
    svg: string;
    url: string;
};

export type TwoFactorSecretKey = {
    secretKey: string;
};

export type AuthForm = {
    name: string;
    father_last_name: string;
    mother_last_name: string;
    email: string;
    password: string;
    password_confirmation: string;
    remember: boolean;
    role: number;
};

export type RegisterAuthForm = Pick<
    AuthForm,
    | 'name'
    | 'father_last_name'
    | 'mother_last_name'
    | 'email'
    | 'password'
    | 'password_confirmation'
    | 'role'
>;

export type LoginAuthForm = Pick<AuthForm, 'email' | 'password' | 'remember'>;

export type ChangeRoleForm = {
    role: Role['name'];
};

export type UserData = Pick<
    User,
    | 'id'
    | 'name'
    | 'father_last_name'
    | 'mother_last_name'
    | 'email'
    | 'created_at'
    | 'updated_at'
    | 'is_active'
    | 'roles'
>;
