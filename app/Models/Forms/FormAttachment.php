<?php

namespace App\Models\Forms;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'form_id',
    'context',
    'label',
    'order',
    'is_required',
    'settings_override',
])]
class FormAttachment extends Model
{
    protected function casts(): array
    {
        return [
            'is_required' => 'boolean',
            'settings_override' => 'array',
        ];
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function formable()
    {
        return $this->morphTo();
    }
}
