<?php

namespace App\Models\Forms;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'action_type',
    'target_question_id',
    'target_section_id',
    'condition_operator',
    'order',
    'is_active',
    'form_id',
])]
class FormLogicRule extends Model
{
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function targetQuestion()
    {
        return $this->belongsTo(FormQuestion::class, 'target_question_id');
    }

    public function targetSection()
    {
        return $this->belongsTo(FormSection::class, 'target_section_id');
    }

    public function conditions()
    {
        return $this->hasMany(FormLogicCondition::class);
    }
}
