<?php

namespace App\Modules\Classroom\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseDiscussionReplyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'min:2'],
            'parent_id' => ['nullable', 'exists:course_discussion_replies,id'],
            'is_solution' => ['required', 'boolean'],
        ];
    }
}
