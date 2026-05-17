<?php

namespace App\Modules\Forms\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Forms\Form;
use App\Models\Forms\FormResponseAnswer;
use App\Models\Forms\FormSection;
use App\Modules\Forms\Application\DTOs\FormSectionFormData;
use App\Modules\Forms\Application\UseCases\Command\DeleteFormSectionAction;
use App\Modules\Forms\Application\UseCases\Command\StoreFormSectionAction;
use App\Modules\Forms\Application\UseCases\Command\UpdateFormSectionAction;
use App\Modules\Forms\Http\Requests\StoreFormSectionRequest;
use App\Modules\Forms\Http\Requests\UpdateFormSectionRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class FormSectionController extends Controller
{
    public function __construct(
        private StoreFormSectionAction $storeFormSectionAction,
        private UpdateFormSectionAction $updateFormSectionAction,
        private DeleteFormSectionAction $deleteFormSectionAction,
    ) {}

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

        $data = FormSectionFormData::from($request->validated());

        if ($form->sections()->where('order', $data->order)->exists()) {
            throw ValidationException::withMessages([
                'order' => 'Ya existe una sección con ese orden en este formulario.',
            ]);
        }

        $this->storeFormSectionAction->execute($form, $data);

        return back()->with('message', 'Sección creada correctamente.');
    }

    public function update(UpdateFormSectionRequest $request, Form $form, FormSection $section)
    {
        $this->authorize('update', $form);

        if ($section->form_id !== $form->id) {
            abort(404);
        }

        $data = FormSectionFormData::from($request->validated());

        $this->updateFormSectionAction->execute($section, $data);

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

        $this->deleteFormSectionAction->execute($section);

        return back()->with('message', 'Sección eliminada correctamente.');
    }
}
