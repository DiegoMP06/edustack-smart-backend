<?php

namespace App\Enums\Classroom;

enum SubmissionType: string
{
    case FILE = 'file';
    case TEXT = 'text';
    case URL = 'url';
    case FORM = 'form';
    case MIXED = 'mixed';
}
