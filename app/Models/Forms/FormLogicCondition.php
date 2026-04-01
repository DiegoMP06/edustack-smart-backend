<?php

namespace App\Models\Forms;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'source_question_id',
    'operator',
    'comparison_value',
    'comparison_option_id',
    'form_logic_rule_id',
])]
class FormLogicCondition extends Model
{
    protected function casts(): array
    {
        return [
            'comparison_value' => 'array',
        ];
    }

    public function rule()
    {
        return $this->belongsTo(FormLogicRule::class, 'form_logic_rule_id');
    }

    public function sourceQuestion()
    {
        return $this->belongsTo(FormQuestion::class, 'source_question_id');
    }

    public function comparisonOption()
    {
        return $this->belongsTo(FormQuestionOption::class, 'comparison_option_id');
    }
}
