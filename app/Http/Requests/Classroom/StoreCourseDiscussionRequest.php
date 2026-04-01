<?php

namespace App\Http\Requests\Classroom;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseDiscussionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'array'],
            'is_pinned' => ['required', 'boolean'],
            'course_lesson_id' => ['nullable', 'exists:course_lessons,id'],
        ];
    }
}
