import type { Content } from '@puckeditor/core';
import type { LatLng } from 'leaflet';
import type {
    EVENT_ACTIVITY_TYPE_SLUG,
    EVENT_COLLABORATOR_ROLE,
    EVENT_REGISTRATION_STATUS,
    EVENT_STATUS_SLUG,
} from '@/consts/events';
import type { ComponentProps } from '@/lib/puck';
import type { Media, UserData } from '.';

export type EventCollaboratorRole = keyof typeof EVENT_COLLABORATOR_ROLE;
export type EventCollaboratorRoleValue =
    (typeof EVENT_COLLABORATOR_ROLE)[EventCollaboratorRole];

export type EventActivityTypeSlug = keyof typeof EVENT_ACTIVITY_TYPE_SLUG;
export type EventActivityTypeSlugValue =
    (typeof EVENT_ACTIVITY_TYPE_SLUG)[EventActivityTypeSlug];

export type EventRegistrationStatus = keyof typeof EVENT_REGISTRATION_STATUS;
export type EventRegistrationStatusValue =
    (typeof EVENT_REGISTRATION_STATUS)[EventRegistrationStatus];

export type EventStatusSlug = keyof typeof EVENT_STATUS_SLUG;
export type EventStatusSlugValue = (typeof EVENT_STATUS_SLUG)[EventStatusSlug];

export type EventCatalogItem = {
    id: number;
    name: string;
    slug?: string;
    color?: string;
    icon?: string;
    behavior_type?: string;
};

export type EventRegistration = {
    id: number;
    status: string;
    user_id: number;
    event_id?: number;
    event_activity_id?: number;
};

export type EventTeam = {
    id: number;
    name: string;
    description: string | null;
    captain_user_id: number;
    status: string;
    members_count?: number;
};

export type EventExercise = {
    id: number;
    name: string;
    description: string | null;
};

export type EventRound = {
    id: number;
    name: string;
    content: Record<string, unknown>[];
    round_number: number;
    participants_per_round: number | null;
    starting_from_scratch: boolean;
    qualified_participants: number;
    winners_count: number;
    is_the_final: boolean;
    status: string;
    started_at: string;
    ended_at: string;
    exercises: EventExercise[];
};

export type EventActivity = {
    id: number;
    name: string;
    slug: string;
    image: string | null;
    summary: string;
    content: Content<ComponentProps>;
    location: string;
    lat: number | string | null;
    lng: number | string | null;
    is_online: boolean;
    online_link: string | null;
    has_teams: boolean;
    requires_team: boolean;
    min_team_size: number | null;
    max_team_size: number | null;
    event_status_id: number;
    price: number;
    max_participants: number | null;
    only_students: boolean;
    is_competition: boolean;
    registration_started_at: string | null;
    registration_ended_at: string | null;
    is_published: boolean;
    published_at: string | null;
    started_at: string;
    ended_at: string;
    event_id: number;
    event_activity_type_id: number;
    difficulty_level_id: number;
    type?: EventCatalogItem | null;
    difficultyLevel?: EventCatalogItem | null;
    status?: EventCatalogItem | null;
    categories?: EventCatalogItem[];
    teams?: EventTeam[];
    user_registration?: EventRegistration | null;
    created_at: string;
    updated_at: string;
};

export type EventActivityFormData = {
    name: string;
    summary: string;
    image: File[];
    event_status_id: number | string;
    event_activity_type_id: number | string;
    difficulty_level_id: number | string;
    started_at: Date;
    ended_at: Date;
    registration_started_at: Date | null;
    registration_ended_at: Date | null;
    price: number;
    capacity: number | null;
    is_online: boolean;
    online_link: string;
    location: string;
    latLng: LatLng;
    is_competition: boolean;
    has_teams: boolean;
    requires_team: boolean;
    min_team_size: number | null;
    max_team_size: number | null;
    only_students: boolean;
    course_id: number | null;
    project_id: number | null;
    repository_url: string;
    categories: number[];
};

export type EventRoundFormData = {
    name: string;
    content: Record<string, unknown>[];
    started_at: Date;
    ended_at: Date;
    participants_per_round: number | null;
    starting_from_scratch: boolean;
    qualified_participants: number;
    winners_count: number;
    is_the_final: boolean;
};

export type Event = {
    id: number;
    name: string;
    slug: string;
    summary: string;
    content: Content<ComponentProps>;
    price: number;
    percent_off: number;
    capacity: number | null;
    is_online: boolean;
    online_link: string | null;
    location: string | null;
    lat: number | null;
    lng: number | null;
    registration_started_at: string;
    registration_ended_at: string;
    start_date: string;
    end_date: string;
    is_published: boolean;
    event_status_id: number;
    user_id: number;
    created_at: string;
    updated_at: string;
    activities: EventActivity[];
    user_registration?: EventRegistration | null;
    author: UserData;
    media: Media[];
};

export type EventFormData = Pick<
    Event,
    | 'name'
    | 'summary'
    | 'location'
    | 'price'
    | 'percent_off'
    | 'is_online'
    | 'online_link'
> & {
    logo: File[];
    latLng: LatLng;
    is_free: boolean;
    with_capacity: boolean;
    capacity: number;
    registration_started_at: Date;
    registration_ended_at: Date;
    start_date: Date;
    end_date: Date;
};
