<?php

namespace App\Models\Forms;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'title',
    'description',
    'question_type',
    'is_required',
    'is_visible',
    'order',
    'settings',
    'has_correct_answer',
    'score',
    'explanation',
    'form_id',
    'form_section_id',
])]
class FormQuestion extends Model
{
    protected function casts(): array
    {
        return [
            'is_required' => 'boolean',
            'is_visible' => 'boolean',
            'settings' => 'array',
            'has_correct_answer' => 'boolean',
            'score' => 'float',
        ];
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function section()
    {
        return $this->belongsTo(FormSection::class, 'form_section_id');
    }

    public function options()
    {
        return $this->hasMany(FormQuestionOption::class)->orderBy('order');
    }

    public function logicConditions()
    {
        return $this->hasMany(FormLogicCondition::class, 'source_question_id');
    }

    public function responseAnswers()
    {
        return $this->hasMany(FormResponseAnswer::class);
    }
}
