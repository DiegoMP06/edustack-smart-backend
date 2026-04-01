<?php

namespace App\Http\Requests\Classroom;

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
            'content' => ['required', 'array'],
            'parent_id' => ['nullable', 'exists:course_discussion_replies,id'],
            'is_solution' => ['required', 'boolean'],
        ];
    }
}
