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
            'event_status_id' => ['required', Rule::exists('event_statuses', 'id')],
            'event_activity_type_id' => ['required', Rule::exists('event_activity_types', 'id')],
            'difficulty_level_id' => ['nullable', Rule::exists('difficulty_levels', 'id')],
            'started_at' => ['required', 'date', 'after:now'],
            'ended_at' => ['required', 'date', 'after:started_at'],
            'registration_started_at' => ['nullable', 'date', 'before:started_at'],
            'registration_ended_at' => ['nullable', 'date', 'after:registration_started_at', 'before:started_at'],
            'price' => ['required', 'numeric', 'min:0'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'is_online' => ['required', 'boolean'],
            'online_link' => ['required_if:is_online,true', 'nullable', 'url', 'max:255'],
            'location' => ['required_if_declined:is_online', 'string', 'max:255'],
            'lat' => ['required_if_declined:is_online', 'numeric', 'min:-90', 'max:90'],
            'lng' => ['required_if_declined:is_online', 'numeric', 'min:-180', 'max:180'],
            'has_teams' => ['required', 'boolean'],
            'requires_team' => ['required_if:has_teams,true', 'boolean'],
            'min_team_size' => ['required_if:has_teams,true', 'integer', 'min:1'],
            'max_team_size' => ['nullable', 'integer', 'gte:min_team_size'],
            'only_students' => ['required', 'boolean'],
            'is_competition' => ['required', 'boolean'],
            'course_id' => ['nullable', Rule::exists('courses', 'id')],
            'project_id' => ['nullable', Rule::exists('projects', 'id')],
            'repository_url' => ['nullable', 'url', 'max:255'],
            'categories' => ['nullable', 'array'],
            'categories.*' => [Rule::exists('event_activity_categories', 'id')],
        ];
    }
}
