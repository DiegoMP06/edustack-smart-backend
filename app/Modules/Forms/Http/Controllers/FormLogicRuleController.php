<?php

namespace App\Modules\Forms\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Forms\Form;
use App\Models\Forms\FormLogicRule;
use App\Modules\Forms\Application\DTOs\FormLogicRuleFormData;
use App\Modules\Forms\Application\UseCases\Command\DeleteFormLogicRuleAction;
use App\Modules\Forms\Application\UseCases\Command\StoreFormLogicRuleAction;
use App\Modules\Forms\Application\UseCases\Command\UpdateFormLogicRuleAction;
use App\Modules\Forms\Http\Requests\StoreFormLogicRuleRequest;
use App\Modules\Forms\Http\Requests\UpdateFormLogicRuleRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class FormLogicRuleController extends Controller
{
    public function __construct(
        private StoreFormLogicRuleAction $storeFormLogicRuleAction,
        private UpdateFormLogicRuleAction $updateFormLogicRuleAction,
        private DeleteFormLogicRuleAction $deleteFormLogicRuleAction,
    ) {}

    public function index(Form $form, Request $request)
    {
        $this->authorize('update', $form);

        return Inertia::render('forms/form-logic', [
            'form' => $form->load([
                'logicRules.conditions.sourceQuestion',
                'logicRules.targetQuestion',
                'logicRules.targetSection',
                'questions',
                'sections',
            ]),
            'message' => $request->session()->get('message'),
        ]);
    }

    public function store(StoreFormLogicRuleRequest $request, Form $form)
    {
        $this->authorize('update', $form);

        $data = FormLogicRuleFormData::from($request->validated());

        if (in_array($data->action_type, ['show_question', 'hide_question', 'require_question', 'skip_question'], true) && $data->target_question_id === null) {
            throw ValidationException::withMessages([
                'target_question_id' => 'Esta acción requiere una pregunta destino.',
            ]);
        }

        if ($data->action_type === 'jump_to_section' && $data->target_section_id === null) {
            throw ValidationException::withMessages([
                'target_section_id' => 'Esta acción requiere una sección destino.',
            ]);
        }

        $this->storeFormLogicRuleAction->execute($form, $data);

        return back()->with('message', 'Regla de lógica creada correctamente.');
    }

    public function update(UpdateFormLogicRuleRequest $request, Form $form, FormLogicRule $rule)
    {
        $this->authorize('update', $form);

        if ($rule->form_id !== $form->id) {
            abort(404);
        }

        $data = FormLogicRuleFormData::from($request->validated());

        if (in_array($data->action_type, ['show_question', 'hide_question', 'require_question', 'skip_question'], true) && $data->target_question_id === null) {
            throw ValidationException::withMessages([
                'target_question_id' => 'Esta acción requiere una pregunta destino.',
            ]);
        }

        if ($data->action_type === 'jump_to_section' && $data->target_section_id === null) {
            throw ValidationException::withMessages([
                'target_section_id' => 'Esta acción requiere una sección destino.',
            ]);
        }

        $this->updateFormLogicRuleAction->execute($rule, $data);

        return back()->with('message', 'Regla actualizada correctamente.');
    }

    public function destroy(Form $form, FormLogicRule $rule)
    {
        $this->authorize('update', $form);

        if ($rule->form_id !== $form->id) {
            abort(404);
        }

        $this->deleteFormLogicRuleAction->execute($rule);

        return back()->with('message', 'Regla eliminada correctamente.');
    }
}
