<?php

namespace App\Models\Forms;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'form_response_id',
    'form_question_id',
    'text_answer',
    'number_answer',
    'date_answer',
    'time_answer',
    'datetime_answer',
    'selected_option_ids',
    'structured_answer',
    'is_correct',
    'score_awarded',
    'feedback',
    'was_skipped',
])]
class FormResponseAnswer extends Model
{
    protected function casts(): array
    {
        return [
            'number_answer' => 'float',
            'date_answer' => 'date:Y-m-d',
            'time_answer' => 'datetime:H:i:s',
            'datetime_answer' => 'datetime:Y-m-d H:i:s',
            'selected_option_ids' => 'array',
            'structured_answer' => 'array',
            'is_correct' => 'boolean',
            'score_awarded' => 'float',
            'was_skipped' => 'boolean',
        ];
    }

    public function response()
    {
        return $this->belongsTo(FormResponse::class, 'form_response_id');
    }

    public function question()
    {
        return $this->belongsTo(FormQuestion::class, 'form_question_id');
    }
}
