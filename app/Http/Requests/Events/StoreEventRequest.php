<?php

namespace App\Http\Requests\Events;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'logo' => ['required', 'string'],
            'description' => ['required', 'string', 'min:50'],
            'price' => ['required', 'numeric', 'min:0'],
            'percent_off' => ['required', 'numeric', 'min:0', 'max:100'],
            'capacity' => ['nullable', 'required_if:with_capacity,true', 'numeric', 'min:1'],
            'is_online' => ['required', 'boolean'],
            'online_link' => [
                'nullable',
                'required_if:is_online,true',
                'prohibited_if:is_online,false',
                'url',
                'max:255',
            ],
            'location' => [
                'nullable',
                'required_if:is_online,false',
                'prohibited_if:is_online,true',
                'string',
                'max:255',
            ],
            'lat' => ['nullable', 'required_if:is_online,false', 'numeric', 'min:-90', 'max:90'],
            'lng' => ['nullable', 'required_if:is_online,false', 'numeric', 'min:-180', 'max:180'],
            'registration_started_at' => ['required', 'date', 'after:now'],
            'registration_ended_at' => [
                'required',
                'date',
                'after:registration_started_at',
                'before:start_date',
            ],
            'start_date' => [
                'required',
                'date_format:Y-m-d',
                'after:registration_ended_at',
            ],
            'end_date' => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:start_date',
            ],
        ];
    }
}
