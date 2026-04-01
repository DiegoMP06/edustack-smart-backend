<?php

namespace App\Models\Forms;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'text',
    'value',
    'image_url',
    'order',
    'is_row',
    'correct_order',
    'match_option_id',
    'is_correct',
    'feedback',
    'form_question_id',
])]
class FormQuestionOption extends Model
{
    protected function casts(): array
    {
        return [
            'is_row' => 'boolean',
            'is_correct' => 'boolean',
        ];
    }

    public function question()
    {
        return $this->belongsTo(FormQuestion::class, 'form_question_id');
    }

    public function matchOption()
    {
        return $this->belongsTo(self::class, 'match_option_id');
    }

    public function matchedBy()
    {
        return $this->hasMany(self::class, 'match_option_id');
    }

    public function logicConditions()
    {
        return $this->hasMany(FormLogicCondition::class, 'comparison_option_id');
    }
}
