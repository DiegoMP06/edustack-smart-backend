<?php

namespace App\Modules\Events\Http\Requests;

use App\Modules\Shared\Concerns\NormalizesDateTimeFields;
use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    use NormalizesDateTimeFields;

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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'logo' => ['required', 'string'],
            'description' => ['required', 'string', 'min:50'],
            'is_free' => ['required', 'boolean'],
            'price' => ['required', 'numeric', 'min:0'],
            'percent_off' => ['required', 'numeric', 'min:0', 'max:100'],
            'with_capacity' => ['required', 'boolean'],
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
            'registration_ended_at' => ['required', 'date', 'after:registration_started_at'],
            'start_date' => [
                'required',
                'date',
                'after:registration_ended_at',
            ],
            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date',
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->normalizeDateTimeFields([
            'registration_started_at',
            'registration_ended_at',
            'start_date',
            'end_date',
        ]);
    }
}
