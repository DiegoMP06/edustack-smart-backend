<?php

namespace App\Http\Requests\Forms;

use Illuminate\Foundation\Http\FormRequest;

class StoreFormResponseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'respondent_email' => ['nullable', 'email', 'max:255'],
            'answers' => ['required', 'array', 'min:1'],
            'answers.*.form_question_id' => ['required', 'exists:form_questions,id'],
            'answers.*.text_answer' => ['nullable', 'string'],
            'answers.*.number_answer' => ['nullable', 'numeric'],
            'answers.*.date_answer' => ['nullable', 'date_format:Y-m-d'],
            'answers.*.time_answer' => ['nullable', 'date_format:H:i:s'],
            'answers.*.datetime_answer' => ['nullable', 'date_format:Y-m-d H:i:s'],
            'answers.*.selected_option_ids' => ['nullable', 'array'],
            'answers.*.selected_option_ids.*' => ['integer', 'exists:form_question_options,id'],
            'answers.*.structured_answer' => ['nullable', 'array'],
            'answers.*.was_skipped' => ['required', 'boolean'],
        ];
    }
}
