<?php

namespace App\Http\Controllers\Forms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Forms\StoreFormQuestionOptionRequest;
use App\Http\Requests\Forms\UpdateFormQuestionOptionRequest;
use App\Models\Forms\Form;
use App\Models\Forms\FormQuestion;
use App\Models\Forms\FormQuestionOption;
use Illuminate\Validation\ValidationException;

class FormQuestionOptionController extends Controller
{
    public function store(StoreFormQuestionOptionRequest $request, Form $form, FormQuestion $question)
    {
        $this->authorize('update', $form);

        if ($question->form_id !== $form->id) {
            abort(404);
        }

        $question->options()->create($request->validated());

        return back()->with('message', 'Opción creada correctamente.');
    }

    public function update(UpdateFormQuestionOptionRequest $request, Form $form, FormQuestion $question, FormQuestionOption $option)
    {
        $this->authorize('update', $form);

        if ($question->form_id !== $form->id) {
            abort(404);
        }

        if ($option->form_question_id !== $question->id) {
            abort(404);
        }

        $option->update($request->validated());

        return back()->with('message', 'Opción actualizada correctamente.');
    }

    public function destroy(Form $form, FormQuestion $question, FormQuestionOption $option)
    {
        $this->authorize('update', $form);

        if ($question->form_id !== $form->id) {
            abort(404);
        }

        if ($option->form_question_id !== $question->id) {
            abort(404);
        }

        if (str_contains((string) $question->question_type, 'choice') && $question->options()->count() === 1) {
            throw ValidationException::withMessages([
                'option' => 'La pregunta debe tener al menos una opción.',
            ]);
        }

        $option->delete();

        return back()->with('message', 'Opción eliminada correctamente.');
    }
}
