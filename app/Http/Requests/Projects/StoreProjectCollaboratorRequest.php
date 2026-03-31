<?php

namespace App\Http\Requests\Projects;

use App\Enums\Projects\ProjectCollaboratorRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProjectCollaboratorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'role' => ['required', 'string', Rule::enum(ProjectCollaboratorRole::class)],
        ];
    }
}
