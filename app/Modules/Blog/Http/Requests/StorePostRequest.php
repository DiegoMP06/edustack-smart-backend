<?php

namespace App\Modules\Blog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:50'],
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['required', 'string'],
            'reading_time_minutes' => ['required', 'integer', 'min:1'],
            'post_type_id' => ['required', 'integer', 'exists:post_types,id'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['required', 'integer', 'exists:post_categories,id'],
        ];
    }
}
