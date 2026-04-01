<?php

namespace App\Http\Requests\Forms;

use Illuminate\Foundation\Http\FormRequest;

class StoreFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'form_type_id' => ['required', 'exists:form_types,id'],
            'requires_login' => ['required', 'boolean'],
            'allow_multiple_responses' => ['required', 'boolean'],
            'max_responses' => ['nullable', 'integer', 'min:1'],
            'collect_email' => ['required', 'boolean'],
            'show_progress_bar' => ['required', 'boolean'],
            'shuffle_sections' => ['required', 'boolean'],
            'available_from' => ['nullable', 'date_format:Y-m-d H:i:s'],
            'available_until' => ['nullable', 'date_format:Y-m-d H:i:s', 'after:available_from'],
            'confirmation_message' => ['nullable', 'string', 'max:1000'],
            'redirect_url' => ['nullable', 'url', 'max:255'],
            'is_quiz_mode' => ['required', 'boolean'],
            'time_limit_minutes' => ['nullable', 'integer', 'min:1', 'max:300'],
            'max_attempts' => ['required', 'integer', 'min:1', 'max:10'],
            'passing_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'randomize_questions' => ['required', 'boolean'],
            'randomize_options' => ['required', 'boolean'],
            'show_results_to_respondent' => ['required', 'in:immediately,after_close,never'],
            'show_correct_answers' => ['required', 'boolean'],
            'show_feedback_after' => ['required', 'boolean'],
        ];
    }
}
