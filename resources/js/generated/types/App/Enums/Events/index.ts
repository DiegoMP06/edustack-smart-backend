export type ActivityRegistrationStatus =
    | 'registered'
    | 'confirmed'
    | 'cancelled'
    | 'disqualified';
export type BehaviorType =
    | 'competition'
    | 'bootcamp'
    | 'workshop'
    | 'talk'
    | 'open_source'
    | 'demo'
    | 'code_review'
    | 'default';
export type EventCollaboratorRole =
    | 'organizer'
    | 'speaker'
    | 'mentor'
    | 'judge'
    | 'volunteer'
    | 'collaborator';
export type EventRegistrationStatus =
    | 'pending'
    | 'confirmed'
    | 'waitlisted'
    | 'cancelled';
export type RoundStatus = 'pending' | 'active' | 'completed' | 'cancelled';
export type TeamMemberRole = 'captain' | 'member';
export type TeamStatus = 'forming' | 'confirmed' | 'disqualified';
