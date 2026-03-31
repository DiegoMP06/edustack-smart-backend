<?php

namespace App\Http\Requests\Events;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompetitionRoundRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'content' => ['required', 'array'],
            'started_at' => ['required', 'date', 'after:now'],
            'ended_at' => ['required', 'date', 'after:started_at'],
            'participants_per_round' => ['nullable', 'integer', 'min:2'],
            'starting_from_scratch' => ['required', 'boolean'],
            'qualified_participants' => ['required', 'integer', 'min:1'],
            'winners_count' => ['required', 'integer', 'min:1'],
            'is_the_final' => ['required', 'boolean'],
        ];
    }
}
