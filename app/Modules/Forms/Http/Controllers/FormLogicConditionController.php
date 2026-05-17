<?php

namespace App\Modules\Forms\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Forms\Form;
use App\Models\Forms\FormLogicCondition;
use App\Models\Forms\FormLogicRule;
use App\Models\Forms\FormQuestion;
use App\Modules\Forms\Application\DTOs\FormLogicConditionFormData;
use App\Modules\Forms\Application\UseCases\Command\DeleteFormLogicConditionAction;
use App\Modules\Forms\Application\UseCases\Command\StoreFormLogicConditionAction;
use App\Modules\Forms\Application\UseCases\Command\UpdateFormLogicConditionAction;
use App\Modules\Forms\Http\Requests\StoreFormLogicConditionRequest;
use App\Modules\Forms\Http\Requests\UpdateFormLogicConditionRequest;
use Illuminate\Validation\ValidationException;

class FormLogicConditionController extends Controller
{
    public function __construct(
        private StoreFormLogicConditionAction $storeFormLogicConditionAction,
        private UpdateFormLogicConditionAction $updateFormLogicConditionAction,
        private DeleteFormLogicConditionAction $deleteFormLogicConditionAction,
    ) {}

    public function store(StoreFormLogicConditionRequest $request, Form $form, FormLogicRule $rule)
    {
        $this->authorize('update', $form);

        if ($rule->form_id !== $form->id) {
            abort(404);
        }

        $data = FormLogicConditionFormData::from($request->validated());

        if (FormQuestion::where('id', $data->source_question_id)
            ->where('form_id', $form->id)
            ->doesntExist()) {
            throw ValidationException::withMessages([
                'source_question_id' => 'La pregunta fuente no pertenece a este formulario.',
            ]);
        }

        $this->storeFormLogicConditionAction->execute($rule, $data);

        return back()->with('message', 'Condición creada correctamente.');
    }

    public function update(UpdateFormLogicConditionRequest $request, Form $form, FormLogicRule $rule, FormLogicCondition $condition)
    {
        $this->authorize('update', $form);

        if ($rule->form_id !== $form->id) {
            abort(404);
        }

        if ($condition->form_logic_rule_id !== $rule->id) {
            abort(404);
        }

        $data = FormLogicConditionFormData::from($request->validated());

        $this->updateFormLogicConditionAction->execute($condition, $data);

        return back()->with('message', 'Condición actualizada correctamente.');
    }

    public function destroy(Form $form, FormLogicRule $rule, FormLogicCondition $condition)
    {
        $this->authorize('update', $form);

        if ($rule->form_id !== $form->id) {
            abort(404);
        }

        if ($condition->form_logic_rule_id !== $rule->id) {
            abort(404);
        }

        $this->deleteFormLogicConditionAction->execute($condition);

        return back()->with('message', 'Condición eliminada correctamente.');
    }
}
