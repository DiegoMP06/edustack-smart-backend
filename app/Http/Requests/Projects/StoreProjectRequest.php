<?php

namespace App\Http\Requests\Projects;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
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
            'repository_url' => ['required', 'url', 'max:255'],
            'demo_url' => ['required', 'url', 'max:255'],
            'tech_stack' => ['required', 'array', 'min:1'],
            'tech_stack.*' => ['required', 'string', 'max:255'],
            'version' => ['required', 'string', 'max:255'],
            'license' => ['required', 'string', 'max:255'],
            'project_status_id' => ['required', 'integer', 'exists:project_statuses,id'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['required', 'integer', 'exists:project_categories,id'],
        ];
    }
}
