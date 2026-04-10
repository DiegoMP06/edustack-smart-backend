<?php

namespace App\Enums\Forms;

enum QuestionType: string
{
    case SHORT_TEXT = 'short_text';
    case LONG_TEXT = 'long_text';
    case EMAIL = 'email';
    case PHONE = 'phone';
    case URL = 'url';
    case NUMBER = 'number';
    case SINGLE_CHOICE = 'single_choice';
    case MULTIPLE_CHOICE = 'multiple_choice';
    case DROPDOWN = 'dropdown';
    case YES_NO = 'yes_no';
    case IMAGE_CHOICE = 'image_choice';
    case LINEAR_SCALE = 'linear_scale';
    case RATING = 'rating';
    case NPS = 'nps';
    case LIKERT_SCALE = 'likert_scale';
    case SEMANTIC_DIFF = 'semantic_diff';
    case MATRIX = 'matrix';
    case CHECKBOX_GRID = 'checkbox_grid';
    case RANKING = 'ranking';
    case DATE = 'date';
    case TIME = 'time';
    case DATETIME = 'datetime';
    case FILL_IN_BLANK = 'fill_in_blank';
    case MATCHING = 'matching';
    case ORDERING = 'ordering';
    case CODE = 'code';
    case FILE_UPLOAD = 'file_upload';
    case SIGNATURE = 'signature';
    case SECTION_BREAK = 'section_break';
    case STATEMENT = 'statement';
}
