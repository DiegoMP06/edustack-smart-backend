<?php

namespace App\Http\Requests\Events;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
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
            'logo' => ['nullable', 'image', 'mimes:jpg,png,jpeg,webp'],
            'summary' => ['required', 'string', 'min:100'],
            'price' => ['required', 'numeric', 'min:0'],
            'percent_off' => ['required', 'numeric', 'min:0', 'max:100'],
            'capacity' => ['nullable', 'numeric', 'min:0'],
            'is_online' => ['required', 'boolean'],
            'online_link' => ['required_if:is_online,true', 'url', 'max:255'],
            'location' => ['required_if_declined:is_online', 'string', 'max:255'],
            'lat' => ['required_if_declined:is_online', 'numeric', 'min:-90', 'max:90'],
            'lng' => ['required_if_declined:is_online', 'numeric', 'min:-180', 'max:180'],
            'registration_started_at' => ['required', 'date'],
            'registration_ended_at' => ['required', 'date', 'after:registration_started_at', 'before:start_date'],
            'start_date' => ['required', 'date', 'after:registration_ended_at', 'before:end_date'],
            'end_date' => ['required', 'date', 'after:start_date'],
        ];
    }
}
