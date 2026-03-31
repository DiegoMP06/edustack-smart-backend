<?php

namespace App\Enums\Projects;

enum ProjectCollaboratorRole: string
{
    case LEADER = 'leader';
    case DEVELOPER = 'developer';
    case DESIGNER = 'designer';
    case ANALYST = 'analyst';
    case COLLABORATOR = 'collaborator';
}
