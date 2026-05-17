<?php

namespace App\Modules\Forms\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Forms\Form;
use App\Models\Forms\FormQuestion;
use App\Models\Forms\FormQuestionOption;
use App\Modules\Forms\Application\DTOs\FormQuestionOptionFormData;
use App\Modules\Forms\Application\UseCases\Command\DeleteFormQuestionOptionAction;
use App\Modules\Forms\Application\UseCases\Command\StoreFormQuestionOptionAction;
use App\Modules\Forms\Application\UseCases\Command\UpdateFormQuestionOptionAction;
use App\Modules\Forms\Http\Requests\StoreFormQuestionOptionRequest;
use App\Modules\Forms\Http\Requests\UpdateFormQuestionOptionRequest;
use Illuminate\Validation\ValidationException;

class FormQuestionOptionController extends Controller
{
    public function __construct(
        private StoreFormQuestionOptionAction $storeFormQuestionOptionAction,
        private UpdateFormQuestionOptionAction $updateFormQuestionOptionAction,
        private DeleteFormQuestionOptionAction $deleteFormQuestionOptionAction,
    ) {}

    public function store(StoreFormQuestionOptionRequest $request, Form $form, FormQuestion $question)
    {
        $this->authorize('update', $form);

        if ($question->form_id !== $form->id) {
            abort(404);
        }

        $data = FormQuestionOptionFormData::from($request->validated());

        $this->storeFormQuestionOptionAction->execute($question, $data);

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

        $data = FormQuestionOptionFormData::from($request->validated());

        $this->updateFormQuestionOptionAction->execute($option, $data);

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

        $this->deleteFormQuestionOptionAction->execute($option);

        return back()->with('message', 'Opción eliminada correctamente.');
    }
}
