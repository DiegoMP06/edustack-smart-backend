import type { Content } from '@puckeditor/core';
import type { LatLng } from 'leaflet';
import type { EVENT_COLLABORATOR_ROLE } from '@/consts/events';
import type { ComponentProps } from '@/lib/puck';
import type { Collaborator, Media, PivotType, UserData } from '.';

export type EventCollaboratorRole = keyof typeof EVENT_COLLABORATOR_ROLE;

export type EventCollaboratorRoleValues =
    (typeof EVENT_COLLABORATOR_ROLE)[EventCollaboratorRole];

export type EventStatus = {
    id: number;
    name: string;
    slug: string;
    color: string;
    description: string;
    order: number;
};

export type DifficultyLevel = {
    id: number;
    name: string;
    slug: string;
    color: string;
    description: string;
    order: number;
};

export type EventActivityType = {
    id: number;
    name: string;
    slug: string;
    description: string;
    icon: string;
    behavior_type: string;
    order: number;
};

export type EventActivityCategory = {
    id: number;
    name: string;
    slug: string;
    description: string;
    icon: string;
    order: number;
};

export type Speaker = {
    id: string;
    name: string;
    father_last_name: string;
    mother_last_name: string;
    email: string;
    biography: string;
    job_title?: string;
    company?: string;
    social: {
        name: string;
        url: string;
    }[];
};

export type SpeakerFormData = Pick<
    Speaker,
    | 'name'
    | 'father_last_name'
    | 'mother_last_name'
    | 'email'
    | 'job_title'
    | 'company'
    | 'biography'
    | 'social'
>;

export type EventActivity = {
    id: number;
    name: string;
    slug: string;
    description: string;
    content: Content<ComponentProps>;
    requirements: string | null;
    is_online: boolean;
    online_link: string | null;
    location: string | null;
    lat: number | null;
    lng: number | null;
    has_teams: boolean;
    requires_team: boolean;
    min_team_size: number | null;
    max_team_size: number | null;
    capacity: number | null;
    only_students: boolean;
    is_competition: boolean;
    price: number;
    speakers: Speaker[];
    repository_url: string | null;
    is_published: boolean;
    started_at: string;
    ended_at: string;
    registration_started_at: string;
    registration_ended_at: string;
    course_id: number | null;
    project_id: number | null;
    difficulty_level_id: number;
    event_status_id: number;
    event_activity_type_id: number;
    event_id: number;
    created_at: string;
    updated_at: string;
    media: Media[];
    categories: PivotType<EventActivityCategory>[];
    difficultyLevel: DifficultyLevel;
    status: EventStatus;
    type: EventActivityType;
    author: UserData;
};

export type EventActivityFormData = Pick<
    EventActivity,
    | 'name'
    | 'description'
    | 'requirements'
    | 'is_online'
    | 'online_link'
    | 'location'
    | 'has_teams'
    | 'requires_team'
    | 'min_team_size'
    | 'max_team_size'
    | 'capacity'
    | 'only_students'
    | 'price'
    | 'speakers'
    | 'repository_url'
    | 'event_activity_type_id'
    | 'difficulty_level_id'
> & {
    latLng: LatLng;
    is_free: boolean;
    with_capacity: boolean;
    started_at: Date;
    ended_at: Date;
    registration_started_at: Date;
    registration_ended_at: Date;
    categories: number[];
    images?: File[];
};

export type Event = {
    id: number;
    name: string;
    slug: string;
    description: string;
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
    collaborators: Collaborator[];
    author: UserData;
    media: Media[];
};

export type EventFormData = Pick<
    Event,
    | 'name'
    | 'description'
    | 'location'
    | 'price'
    | 'percent_off'
    | 'is_online'
    | 'online_link'
    | 'capacity'
> & {
    logo: File[];
    latLng: LatLng;
    is_free: boolean;
    with_capacity: boolean;
    registration_started_at: Date;
    registration_ended_at: Date;
    start_date: Date;
    end_date: Date;
};
