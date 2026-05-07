<?php

namespace App\Modules\Classroom\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseContentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('course'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'content' => ['required', 'array'],
        ];
    }
}
