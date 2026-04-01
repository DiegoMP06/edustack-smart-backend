<?php

namespace App\Models\Classroom;

use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['content', 'assignment_submission_id', 'user_id'])]
class SubmissionComment extends Model
{
    public function submission()
    {
        return $this->belongsTo(AssignmentSubmission::class, 'assignment_submission_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
