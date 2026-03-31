<?php

namespace App\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'summary' => ['required', 'string', 'min:50'],
            'images' => ['required', 'array', 'min:1', 'max:20'],
            'images.*' => ['required', 'image', 'mimes:jpg,png,jpeg,webp'],
            'reading_time_minutes' => ['required', 'integer', 'min:1'],
            'post_type_id' => ['required', 'integer', 'exists:post_types,id'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['required', 'integer', 'exists:post_categories,id'],
        ];
    }
}
