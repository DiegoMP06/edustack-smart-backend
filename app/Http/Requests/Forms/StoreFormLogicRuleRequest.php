<?php

namespace App\Http\Requests\Forms;

use Illuminate\Foundation\Http\FormRequest;

class StoreFormLogicRuleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:255'],
            'action_type' => ['required', 'in:show_question,hide_question,require_question,skip_question,jump_to_section,end_form'],
            'target_question_id' => ['nullable', 'exists:form_questions,id'],
            'target_section_id' => ['nullable', 'exists:form_sections,id'],
            'condition_operator' => ['required', 'in:and,or'],
            'order' => ['required', 'integer', 'min:0'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
