<?php

namespace App\Modules\Events\Http\Requests\EventActivities;

use App\Modules\Events\Rules\EventActivity\ActivityWithinEventRange;
use App\Modules\Shared\Concerns\NormalizesDateTimeFields;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEventActivityRequest extends FormRequest
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
            'description' => ['required', 'string', 'min:50'],
            'requirements' => ['nullable', 'string'],
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['required', 'string'],
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
            'has_teams' => ['required', 'boolean'],
            'requires_team' => ['boolean'],
            'min_team_size' => ['nullable', 'required_if:has_teams,true', 'integer', 'min:1'],
            'max_team_size' => ['nullable', 'required_if:has_teams,true', 'integer', 'gte:min_team_size'],
            'with_capacity' => ['required', 'boolean'],
            'capacity' => ['nullable', 'required_if:with_capacity,true', 'numeric', 'min:1'],
            'only_students' => ['required', 'boolean'],
            'price' => ['required', 'numeric', 'min:0'],
            'speakers' => ['nullable', 'array'],
            'speakers.*.id' => ['required', 'string'],
            'speakers.*.name' => ['required', 'string', 'max:255'],
            'speakers.*.father_last_name' => ['required', 'string', 'max:255'],
            'speakers.*.mother_last_name' => ['required', 'string', 'max:255'],
            'speakers.*.email' => ['required', 'string', 'max:255', 'email'],
            'speakers.*.job_title' => ['nullable', 'string', 'max:255'],
            'speakers.*.company' => ['nullable', 'string', 'max:255'],
            'speakers.*.social' => ['nullable', 'array'],
            'speakers.*.social.*.name' => ['string', 'string', 'max:255'],
            'speakers.*.social.*.url' => ['string', 'url', 'string', 'max:255'],
            'speakers.*.biography' => ['required', 'string', 'min:50', 'max:5000'],
            'repository_url' => ['nullable', 'url', 'max:255'],
            'registration_started_at' => ['required', 'date', 'after:now'],
            'registration_ended_at' => [
                'required',
                'date',
                'after:registration_started_at',
                'before:started_at',
            ],
            'started_at' => [
                'required',
                'date',
                'after:registration_ended_at',
                new ActivityWithinEventRange($this->route('event'), $this->input('ended_at')),
            ],
            'ended_at' => [
                'required',
                'date',
                'after:started_at',
                new ActivityWithinEventRange($this->route('event'), $this->input('started_at')),
            ],
            'event_activity_type_id' => ['required', Rule::exists('event_activity_types', 'id')],
            'difficulty_level_id' => ['nullable', Rule::exists('difficulty_levels', 'id')],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['required', 'integer', Rule::exists('event_activity_categories', 'id')],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->normalizeDateTimeFields([
            'registration_started_at',
            'registration_ended_at',
            'started_at',
            'ended_at',
        ]);
    }
}
