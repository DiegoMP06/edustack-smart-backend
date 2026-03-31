<?php

namespace App\Enums\Events;

enum BehaviorType: string
{
    case COMPETITION = 'competition';
    case BOOTCAMP = 'bootcamp';
    case WORKSHOP = 'workshop';
    case TALK = 'talk';
    case OPEN_SOURCE = 'open_source';
    case DEMO = 'demo';
    case CODE_REVIEW = 'code_review';
    case DEFAULT = 'default';
}
