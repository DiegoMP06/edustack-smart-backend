<?php

namespace App\Http\Requests\Forms;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFormQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:500'],
            'description' => ['nullable', 'string', 'max:1000'],
            'question_type' => ['required', 'in:short_text,long_text,email,phone,url,number,single_choice,multiple_choice,dropdown,yes_no,image_choice,linear_scale,rating,nps,likert_scale,semantic_diff,matrix,checkbox_grid,ranking,date,time,datetime,fill_in_blank,matching,ordering,code,file_upload,signature,section_break,statement'],
            'is_required' => ['required', 'boolean'],
            'is_visible' => ['required', 'boolean'],
            'order' => ['required', 'integer', 'min:0'],
            'settings' => ['nullable', 'array'],
            'has_correct_answer' => ['required', 'boolean'],
            'score' => ['required', 'numeric', 'min:0'],
            'explanation' => ['nullable', 'string', 'max:1000'],
            'form_section_id' => ['nullable', 'exists:form_sections,id'],
        ];
    }
}
