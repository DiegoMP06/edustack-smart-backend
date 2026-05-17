<?php

namespace App\Modules\Forms\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Forms\Form;
use App\Models\Forms\FormResponse;
use App\Modules\Forms\Application\DTOs\FormResponseFormData;
use App\Modules\Forms\Application\UseCases\Command\StoreFormResponseAction;
use App\Modules\Forms\Application\UseCases\Query\ListFormResponsesAction;
use App\Modules\Forms\Http\Requests\StoreFormResponseRequest;
use App\Modules\Forms\Http\Resources\FormResponseCollection;
use App\Modules\Shared\DTOs\Query\ListCollectionQueryParamsData;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class FormResponseController extends Controller
{
    public function __construct(
        private ListFormResponsesAction $listFormResponsesAction,
        private StoreFormResponseAction $storeFormResponseAction,
    ) {}

    public function index(Form $form, Request $request)
    {
        $this->authorize('viewResponses', $form);

        $params = ListCollectionQueryParamsData::fromRequest($request);
        $responses = $this->listFormResponsesAction->execute($form->id, $params);

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

        $data = FormResponseFormData::from([
            ...$request->validated(),
            'attempt_number' => $attempt,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $response = $this->storeFormResponseAction->execute($form, $data, $request->user());

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
