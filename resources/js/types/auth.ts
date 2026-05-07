import type { z } from 'zod';
import type { ROLES } from '@/consts/roles';
import type { UserSchema } from '@/schemas';
import type {
    EventCollaboratorRole,
    PivotType,
    ProjectCollaboratorRole,
} from '.';

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
    roles: Roles[];
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

export type Collaborator = PivotType<
    UserData,
    {
        role: ProjectCollaboratorRole | EventCollaboratorRole;
    }
>;

export type UserFromAPI = z.infer<typeof UserSchema>;
