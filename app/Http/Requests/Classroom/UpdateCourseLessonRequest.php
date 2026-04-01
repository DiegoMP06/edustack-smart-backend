<?php

namespace App\Http\Requests\Classroom;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseLessonRequest extends FormRequest
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
            'type' => ['required', 'in:text,video,activity,live'],
            'video_url' => ['required_if:type,video', 'url', 'max:255'],
            'video_duration_seconds' => ['required_if:type,video', 'integer', 'min:1'],
            'order' => ['required', 'integer', 'min:0'],
            'estimated_minutes' => ['required', 'integer', 'min:1', 'max:600'],
            'is_published' => ['required', 'boolean'],
            'is_preview' => ['required', 'boolean'],
            'course_section_id' => ['required', 'exists:course_sections,id'],
        ];
    }
}
