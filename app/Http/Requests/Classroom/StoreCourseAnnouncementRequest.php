<?php

namespace App\Http\Requests\Classroom;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseAnnouncementRequest extends FormRequest
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
            'notify_students' => ['required', 'boolean'],
            'published_at' => ['nullable', 'date_format:Y-m-d H:i:s'],
        ];
    }
}
