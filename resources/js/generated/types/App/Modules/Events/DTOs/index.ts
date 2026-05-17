import {
    EventCollaboratorRole,
    EventRegistrationStatus,
} from '../../../Enums/Events';
import { RoleData, UserData } from '../../Admin/DTOs';
import { EventActivityData } from './EventActivity';
import { MediaData } from '../../Media/DTOs';
export type DifficultyLevelData = {
    id: number;
    name: string;
    slug: string;
    color: string;
    description: string;
    order: number;
};
export type DraftEventFormData = {
    name: string;
    description: string;
    logo: File[];
    location: string | null;
    latLng: { lat: number; lng: number };
    lat?: number;
    lng?: number;
    is_free: boolean;
    price: number;
    percent_off: number;
    is_online: boolean;
    online_link: string | null;
    with_capacity: boolean;
    capacity: number | null;
    start_date: Date;
    end_date: Date;
    registration_started_at: Date;
    registration_ended_at: Date;
};
export type EventCollaboratorData = {
    pivot_role: EventCollaboratorRole;
    pivot_id: number;
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
export type EventCollaboratorFormData = {
    user_id: number;
    role: EventCollaboratorRole;
};
export type EventData = {
    id: number;
    name: string;
    slug: string;
    description: string;
    content: Record<string, unknown>;
    price: number;
    percent_off: number;
    capacity: number | null;
    is_online: boolean;
    online_link: string | null;
    location: string | null;
    lat: number | null;
    lng: number | null;
    readonly registration_started_at: string;
    readonly registration_ended_at: string;
    start_date: string;
    end_date: string;
    is_published: boolean;
    event_status_id: number;
    user_id: number;
    published_at: string;
    updated_at: string;
    author: UserData | null;
    activities: EventActivityData[] | null;
    status: EventStatusData | null;
    collaborators: EventCollaboratorData[] | null;
    registrations: EventRegistrationData[] | null;
    media: MediaData[] | null;
};
export type EventRegistrationData = {
    pivot_id: number;
    pivot_status: EventRegistrationStatus;
    pivot_confirmed_at: string | null;
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
export type EventStatusData = {
    id: number;
    name: string;
    slug: string;
    color: string;
    description: string;
    order: number;
};
