<?php

namespace App\Models\Forms;

use App\Enums\Forms\FormResponseStatus;
use Illuminate\Database\Eloquent\Model;

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
            'graded_at' => 'datetime',
            'started_at' => 'datetime',
            'submitted_at' => 'datetime',
        ];
    }
}
