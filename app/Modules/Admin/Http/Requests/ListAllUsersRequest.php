<?php

namespace App\Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListAllUsersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'filter' => ['nullable', 'array'],
            'filter.search' => ['nullable', 'string'],
            'sort' => ['nullable', 'string'],
            'include' => ['nullable', 'string'],
            'per_page' => ['nullable', 'integer', 'min:1'],
        ];
    }

    /**
     * Get custom validation messages for this request.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [];
    }
}
