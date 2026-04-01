<?php

namespace App\Models\Forms;

use App\Enums\Forms\FormResponseStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'form_id',
    'user_id',
    'respondent_email',
    'attempt_number',
    'status',
    'ip_address',
    'user_agent',
    'score',
    'max_score',
    'percentage',
    'passed',
    'graded_by',
    'graded_at',
    'started_at',
    'submitted_at',
])]
class FormResponse extends Model
{
    protected function casts(): array
    {
        return [
            'status' => FormResponseStatus::class,
            'score' => 'float',
            'max_score' => 'float',
            'percentage' => 'float',
            'passed' => 'boolean',
            'graded_at' => 'datetime:Y-m-d H:i:s',
            'started_at' => 'datetime:Y-m-d H:i:s',
            'submitted_at' => 'datetime:Y-m-d H:i:s',
        ];
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gradedBy()
    {
        return $this->belongsTo(User::class, 'graded_by');
    }

    public function answers()
    {
        return $this->hasMany(FormResponseAnswer::class);
    }
}
