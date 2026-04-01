<?php

namespace App\Http\Controllers\Forms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Forms\StoreFormResponseRequest;
use App\Http\Resources\Forms\FormResponseCollection;
use App\Models\Forms\Form;
use App\Models\Forms\FormResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class FormResponseController extends Controller
{
    public function index(Form $form, Request $request)
    {
        $this->authorize('viewResponses', $form);

        $responses = $form->responses()
            ->with(['user', 'answers'])
            ->orderByDesc('submitted_at')
            ->paginate(20);

        return Inertia::render('forms/form-responses', [
            'form' => $form->load('type'),
            'responses' => new FormResponseCollection($responses),
            'message' => $request->session()->get('message'),
        ]);
    }

    public function store(StoreFormResponseRequest $request, Form $form)
    {
        if (! $form->is_published || ! $form->is_active) {
            throw ValidationException::withMessages([
                'form' => 'Este formulario no está disponible.',
            ]);
        }

        if (($form->available_from !== null && now()->lt($form->available_from)) || ($form->available_until !== null && now()->gt($form->available_until))) {
            throw ValidationException::withMessages([
                'form' => 'Este formulario no está en el período de respuesta.',
            ]);
        }

        if ($form->requires_login && ! $request->user()) {
            abort(401);
        }

        if ($form->max_responses !== null && $form->responses()->whereIn('status', ['submitted', 'graded'])->count() >= $form->max_responses) {
            throw ValidationException::withMessages([
                'form' => 'Este formulario ha alcanzado el número máximo de respuestas.',
            ]);
        }

        if (! $form->allow_multiple_responses && $request->user() && $form->responses()->where('user_id', $request->user()->id)->whereIn('status', ['submitted', 'graded'])->exists()) {
            throw ValidationException::withMessages([
                'form' => 'Ya has respondido este formulario.',
            ]);
        }

        $attempt = $form->responses()->where('user_id', $request->user()?->id)->count() + 1;

        if ($attempt > $form->max_attempts) {
            throw ValidationException::withMessages([
                'form' => 'Has alcanzado el número máximo de intentos.',
            ]);
        }

        $data = $request->validated();

        $response = $form->responses()->create([
            'user_id' => $request->user()?->id,
            'respondent_email' => $data['respondent_email'] ?? null,
            'attempt_number' => $attempt,
            'status' => 'submitted',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'started_at' => now(),
            'submitted_at' => now(),
        ]);

        foreach ($data['answers'] as $answer) {
            $response->answers()->create([
                'form_question_id' => $answer['form_question_id'],
                'text_answer' => $answer['text_answer'] ?? null,
                'number_answer' => $answer['number_answer'] ?? null,
                'date_answer' => $answer['date_answer'] ?? null,
                'time_answer' => $answer['time_answer'] ?? null,
                'datetime_answer' => $answer['datetime_answer'] ?? null,
                'selected_option_ids' => $answer['selected_option_ids'] ?? null,
                'structured_answer' => $answer['structured_answer'] ?? null,
                'was_skipped' => $answer['was_skipped'],
            ]);
        }

        return redirect()->intended(route('forms.responses.show', ['form' => $form, 'response' => $response], false))
            ->with('message', 'Respuesta enviada correctamente.');
    }

    public function show(Form $form, FormResponse $response)
    {
        $this->authorize('viewResponses', $form);

        if ($response->form_id !== $form->id) {
            abort(404);
        }

        return Inertia::render('forms/show-response', [
            'form' => $form->load('type'),
            'response' => $response->load(['answers.question', 'user', 'gradedBy']),
        ]);
    }
}
