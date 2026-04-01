<?php

namespace App\Models\Classroom;

use App\Models\Forms\FormResponse;
use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'user_id',
    'assignment_id',
    'text_content',
    'url_content',
    'form_response_id',
    'attempt_number',
    'submission_status_id',
    'is_late',
    'score',
    'feedback',
    'graded_by',
    'graded_at',
    'submitted_at',
])]
class AssignmentSubmission extends Model
{
    protected function casts(): array
    {
        return [
            'is_late' => 'boolean',
            'score' => 'float',
            'graded_at' => 'datetime:Y-m-d H:i:s',
            'submitted_at' => 'datetime:Y-m-d H:i:s',
        ];
    }

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function formResponse()
    {
        return $this->belongsTo(FormResponse::class);
    }

    public function status()
    {
        return $this->belongsTo(SubmissionStatus::class, 'submission_status_id');
    }

    public function gradedBy()
    {
        return $this->belongsTo(User::class, 'graded_by');
    }

    public function comments()
    {
        return $this->hasMany(SubmissionComment::class);
    }
}
