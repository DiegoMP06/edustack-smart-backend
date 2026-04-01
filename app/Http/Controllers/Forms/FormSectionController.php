<?php

namespace App\Http\Controllers\Forms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Forms\StoreFormSectionRequest;
use App\Http\Requests\Forms\UpdateFormSectionRequest;
use App\Models\Forms\Form;
use App\Models\Forms\FormResponseAnswer;
use App\Models\Forms\FormSection;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class FormSectionController extends Controller
{
    public function index(Form $form, Request $request)
    {
        $this->authorize('update', $form);

        return Inertia::render('forms/form-sections', [
            'form' => $form->load(['sections.questions']),
            'message' => $request->session()->get('message'),
        ]);
    }

    public function store(StoreFormSectionRequest $request, Form $form)
    {
        $this->authorize('update', $form);

        $data = $request->validated();

        if ($form->sections()->where('order', $data['order'])->exists()) {
            throw ValidationException::withMessages([
                'order' => 'Ya existe una sección con ese orden en este formulario.',
            ]);
        }

        $form->sections()->create($data);

        return back()->with('message', 'Sección creada correctamente.');
    }

    public function update(UpdateFormSectionRequest $request, Form $form, FormSection $section)
    {
        $this->authorize('update', $form);

        if ($section->form_id !== $form->id) {
            abort(404);
        }

        $section->update($request->validated());

        return back()->with('message', 'Sección actualizada correctamente.');
    }

    public function destroy(Form $form, FormSection $section)
    {
        $this->authorize('update', $form);

        if ($section->form_id !== $form->id) {
            abort(404);
        }

        $questionIds = $section->questions()->pluck('id');

        if (FormResponseAnswer::whereIn('form_question_id', $questionIds)->exists()) {
            throw ValidationException::withMessages([
                'section' => 'No puedes eliminar una sección con respuestas registradas.',
            ]);
        }

        $section->delete();

        return back()->with('message', 'Sección eliminada correctamente.');
    }
}
