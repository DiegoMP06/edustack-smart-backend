<?php

namespace App\Http\Requests\Forms;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFormQuestionOptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'text' => ['required', 'string', 'max:500'],
            'value' => ['nullable', 'string', 'max:255'],
            'image_url' => ['nullable', 'url', 'max:255'],
            'order' => ['required', 'integer', 'min:0'],
            'is_row' => ['required', 'boolean'],
            'correct_order' => ['nullable', 'integer', 'min:1'],
            'match_option_id' => ['nullable', 'exists:form_question_options,id'],
            'is_correct' => ['required', 'boolean'],
            'feedback' => ['nullable', 'string', 'max:500'],
        ];
    }
}
