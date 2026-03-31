<?php

namespace App\Enums\Events;

enum EventCollaboratorRole: string
{
    case ORGANIZER = 'organizer';
    case SPEAKER = 'speaker';
    case MENTOR = 'mentor';
    case JUDGE = 'judge';
    case VOLUNTEER = 'volunteer';
}
