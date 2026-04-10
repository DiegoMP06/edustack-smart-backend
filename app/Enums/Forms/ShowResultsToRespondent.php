<?php

namespace App\Enums\Forms;

enum ShowResultsToRespondent: string
{
    case IMMEDIATELY = 'immediately';
    case AFTER_CLOSE = 'after_close';
    case NEVER = 'never';
}
