<?php

namespace App\Http\Controllers\Forms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Forms\StoreFormQuestionRequest;
use App\Http\Requests\Forms\UpdateFormQuestionRequest;
use App\Models\Forms\Form;
use App\Models\Forms\FormQuestion;
use App\Models\Forms\FormResponseAnswer;
use App\Models\Forms\FormSection;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class FormQuestionController extends Controller
{
    public function index(Form $form, Request $request)
    {
        $this->authorize('update', $form);

        return Inertia::render('forms/form-questions', [
            'form' => $form->load(['sections', 'questions.options', 'questions.section']),
            'message' => $request->session()->get('message'),
        ]);
    }

    public function store(StoreFormQuestionRequest $request, Form $form)
    {
        $this->authorize('update', $form);

        $data = $request->validated();

        if (array_key_exists('form_section_id', $data) && $data['form_section_id'] !== null && FormSection::where('id', $data['form_section_id'])
            ->where('form_id', $form->id)
            ->doesntExist()) {
            throw ValidationException::withMessages([
                'form_section_id' => 'La sección no pertenece a este formulario.',
            ]);
        }

        $form->questions()->create($data);

        return back()->with('message', 'Pregunta creada correctamente.');
    }

    public function update(UpdateFormQuestionRequest $request, Form $form, FormQuestion $question)
    {
        $this->authorize('update', $form);

        if ($question->form_id !== $form->id) {
            abort(404);
        }

        $question->update($request->validated());

        return back()->with('message', 'Pregunta actualizada correctamente.');
    }

    public function destroy(Form $form, FormQuestion $question)
    {
        $this->authorize('update', $form);

        if ($question->form_id !== $form->id) {
            abort(404);
        }

        if (FormResponseAnswer::where('form_question_id', $question->id)->exists()) {
            throw ValidationException::withMessages([
                'question' => 'No puedes eliminar una pregunta con respuestas registradas.',
            ]);
        }

        $question->delete();

        return back()->with('message', 'Pregunta eliminada correctamente.');
    }
}
