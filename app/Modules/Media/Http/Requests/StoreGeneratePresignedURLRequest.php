<?php

namespace App\Modules\Media\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGeneratePresignedURLRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'images' => ['required', 'array', 'min:1'],
            'images.*.id' => ['required', 'numeric', 'min:0'],
            'images.*.extension' => ['required', 'string', 'min:1'],
            'images.*.type' => ['required', 'string', 'min:1'],
        ];
    }
}
