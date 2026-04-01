<?php

namespace App\Http\Requests\Classroom;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'summary' => ['nullable', 'string', 'max:500'],
            'instructions' => ['required', 'array'],
            'max_score' => ['required', 'numeric', 'min:1'],
            'passing_score' => ['required', 'numeric', 'min:0', 'lte:max_score'],
            'allow_late_submissions' => ['required', 'boolean'],
            'max_attempts' => ['required', 'integer', 'min:1', 'max:10'],
            'submission_type' => ['required', 'in:file,text,url,form,mixed'],
            'is_published' => ['required', 'boolean'],
            'due_date' => ['nullable', 'date', 'after:now'],
            'available_from' => ['nullable', 'date', 'before_or_equal:due_date'],
            'course_lesson_id' => ['nullable', 'exists:course_lessons,id'],
        ];
    }
}
