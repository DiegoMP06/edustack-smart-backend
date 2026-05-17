<?php

namespace App\Modules\Classroom\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubmissionCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'min:1', 'max:1000'],
        ];
    }
}
