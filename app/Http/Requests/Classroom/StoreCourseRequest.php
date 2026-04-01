<?php

namespace App\Http\Requests\Classroom;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'cover' => ['required', 'image', 'mimes:jpg,png,jpeg,webp'],
            'summary' => ['required', 'string', 'min:100'],
            'code' => ['nullable', 'string', 'max:20'],
            'credits' => ['required', 'integer', 'min:0', 'max:20'],
            'period' => ['nullable', 'string', 'max:50'],
            'price' => ['required', 'numeric', 'min:0'],
            'is_free' => ['required', 'boolean'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'course_status_id' => ['required', 'exists:course_statuses,id'],
            'course_category_id' => ['nullable', 'exists:course_categories,id'],
            'start_date' => ['required', 'date', 'after:today'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'enrollment_start_date' => ['nullable', 'date', 'before_or_equal:start_date'],
            'enrollment_end_date' => ['nullable', 'date', 'after:enrollment_start_date', 'before_or_equal:end_date'],
            'is_published' => ['required', 'boolean'],
        ];
    }
}
