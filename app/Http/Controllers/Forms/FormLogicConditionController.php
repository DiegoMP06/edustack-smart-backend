<?php

namespace App\Http\Controllers\Forms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Forms\StoreFormLogicConditionRequest;
use App\Http\Requests\Forms\UpdateFormLogicConditionRequest;
use App\Models\Forms\Form;
use App\Models\Forms\FormLogicCondition;
use App\Models\Forms\FormLogicRule;
use App\Models\Forms\FormQuestion;
use Illuminate\Validation\ValidationException;

class FormLogicConditionController extends Controller
{
    public function store(StoreFormLogicConditionRequest $request, Form $form, FormLogicRule $rule)
    {
        $this->authorize('update', $form);

        if ($rule->form_id !== $form->id) {
            abort(404);
        }

        $data = $request->validated();

        if (FormQuestion::where('id', $data['source_question_id'])
            ->where('form_id', $form->id)
            ->doesntExist()) {
            throw ValidationException::withMessages([
                'source_question_id' => 'La pregunta fuente no pertenece a este formulario.',
            ]);
        }

        $rule->conditions()->create($data);

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

        $condition->update($request->validated());

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

        $condition->delete();

        return back()->with('message', 'Condición eliminada correctamente.');
    }
}
