<?php

namespace App\Enums\Forms;

enum FormResponseStatus: string
{
    case IN_PROGRESS = 'in-progress';
    case SUBMITTED = 'submitted';
    case GRADED = 'graded';
}
