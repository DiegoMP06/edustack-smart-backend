<?php

namespace App\Http\Requests\Forms;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFormLogicConditionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'source_question_id' => ['required', 'exists:form_questions,id'],
            'operator' => ['required', 'in:equals,not_equals,contains,not_contains,starts_with,ends_with,greater_than,greater_or_equal,less_than,less_or_equal,is_answered,is_empty,includes_option,excludes_option'],
            'comparison_value' => ['nullable', 'array'],
            'comparison_option_id' => ['nullable', 'exists:form_question_options,id'],
        ];
    }
}
