<?php

namespace App\Enums\Forms;

enum ContextFormAttachment: string
{
    case REGISTRATION = 'registration';
    case FEEDBACK = 'feedback';
    case EMBEDDED = 'embedded';
    case QUIZ = 'quiz';
    case EVALUATION = 'evaluation';
    case DIAGNOSTIC = 'diagnostic';
    case SELF_ASSESSMENT = 'self_assessment';
    case PEER_REVIEW = 'peer_review';
    case SURVEY = 'survey';
    case SUBMISSION = 'submission';
    case CUSTOM = 'custom';
}
