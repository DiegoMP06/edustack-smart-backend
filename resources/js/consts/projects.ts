export const ROLE_COLLABORATORS = {
    leader: 'Líder',
    developer: 'Desarrollador',
    designer: 'Diseñador',
    analyst: 'Analista',
    collaborator: 'Colaborador',
} as const;

export const PROJECT_STATUS_SLUG = {
    planning: 'planning',
    'in-progress': 'in-progress',
    'on-hold': 'on-hold',
    completed: 'completed',
    cancelled: 'cancelled',
} as const;

export const PROJECT_LICENSE = {
    MIT: 'MIT',
    'Apache-2.0': 'Apache-2.0',
    'GPL-3.0': 'GPL-3.0',
    'BSD-3-Clause': 'BSD-3-Clause',
    Unlicense: 'Unlicense',
    Other: 'Other',
} as const;
