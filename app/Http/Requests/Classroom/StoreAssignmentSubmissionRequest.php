<?php

namespace App\Http\Requests\Classroom;

use App\Models\Classroom\Assignment;
use Illuminate\Foundation\Http\FormRequest;

class StoreAssignmentSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        /** @var Assignment $assignment */
        $assignment = $this->route('assignment');

        return match ($assignment->submission_type) {
            'text' => [
                'text_content' => ['required', 'string', 'min:10'],
            ],
            'url' => [
                'url_content' => ['required', 'url', 'max:255'],
            ],
            'mixed' => [
                'text_content' => ['nullable', 'string'],
                'url_content' => ['nullable', 'url'],
            ],
            default => [],
        };
    }
}
