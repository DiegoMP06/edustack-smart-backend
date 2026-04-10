export const EVENT_COLLABORATOR_ROLE = {
    organizer: 'Organizador',
    speaker: 'Ponente',
    mentor: 'Mentor',
    judge: 'Juez',
    volunteer: 'Voluntario',
    collaborator: 'Colaborador',
} as const;

export const EVENT_ACTIVITY_TYPE_SLUG = {
    workshop: 'workshop',
    lecture: 'lecture',
    competition: 'competition',
    seminar: 'seminar',
    course: 'course',
    project: 'project',
} as const;

export const EVENT_REGISTRATION_STATUS = {
    pending: 'pending',
    confirmed: 'confirmed',
    cancelled: 'cancelled',
    refunded: 'refunded',
} as const;

export const EVENT_STATUS_SLUG = {
    upcoming: 'upcoming',
    open: 'open',
    closed: 'closed',
    ongoing: 'ongoing',
    finished: 'finished',
} as const;
