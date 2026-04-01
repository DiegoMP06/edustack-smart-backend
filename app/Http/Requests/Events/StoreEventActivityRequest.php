<?php

namespace App\Http\Requests\Events;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEventActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'summary' => ['required', 'string', 'min:50'],
            'requirements' => ['nullable', 'string'],
            'images' => ['required', 'array', 'min:1', 'max:20'],
            'images.*' => ['required', 'image', 'mimes:jpg,png,jpeg,webp'],
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
            'max_participants' => ['nullable', 'required_if:with_capacity,true', 'numeric', 'min:1'],
            'only_students' => ['required', 'boolean'],
            'is_competition' => ['required', 'boolean'],
            'price' => ['required', 'numeric', 'min:0'],
            'speakers' => ['nullable', 'array'],
            'speakers.*.id' => ['required', 'numeric', 'min:1'],
            'speakers.*.exists_in_platform' => ['required', 'boolean'],
            'speakers.*.name' => ['required', 'string', 'max:255'],
            'speakers.*.father_last_name' => ['required', 'string', 'max:255'],
            'speakers.*.mother_last_name' => ['required', 'string', 'max:255'],
            'speakers.*.email' => ['required', 'string', 'max:255', 'email'],
            'speakers.*.job_title' => ['nullable', 'string', 'max:255'],
            'speakers.*.company' => ['nullable', 'string', 'max:255'],
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
            ],
            'ended_at' => [
                'required',
                'date',
                'after_or_equal:started_at',
            ],
            'event_status_id' => ['required', Rule::exists('event_statuses', 'id')],
            'event_activity_type_id' => ['required', Rule::exists('event_activity_types', 'id')],
            'difficulty_level_id' => ['nullable', Rule::exists('difficulty_levels', 'id')],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['required', 'integer', Rule::exists('event_activity_categories', 'id')],
        ];
    }
}
