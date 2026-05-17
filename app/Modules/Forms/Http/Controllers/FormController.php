<?php

namespace App\Modules\Forms\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Forms\Form;
use App\Modules\Forms\Application\DTOs\DraftFormFormData;
use App\Modules\Forms\Application\Support\FormDataMapper;
use App\Modules\Forms\Application\UseCases\Command\CreateFormAction;
use App\Modules\Forms\Application\UseCases\Command\DeleteFormAction;
use App\Modules\Forms\Application\UseCases\Command\UpdateFormAction;
use App\Modules\Forms\Application\UseCases\Query\GetFormFormOptionsAction;
use App\Modules\Forms\Application\UseCases\Query\ListUserFormsAction;
use App\Modules\Forms\Http\Requests\StoreFormRequest;
use App\Modules\Forms\Http\Requests\UpdateFormRequest;
use App\Modules\Shared\DTOs\Query\ListCollectionQueryParamsData;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class FormController extends Controller
{
    public function __construct(
        private ListUserFormsAction $listUserFormsAction,
        private GetFormFormOptionsAction $getFormFormOptionsAction,
        private CreateFormAction $createFormAction,
        private UpdateFormAction $updateFormAction,
        private DeleteFormAction $deleteFormAction,
        private FormDataMapper $formDataMapper,
    ) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Form::class);

        $params = ListCollectionQueryParamsData::fromRequest($request);
        $forms = $this->listUserFormsAction->execute($request->user(), $params);

        return Inertia::render('forms/forms', [
            ...$this->getFormFormOptionsAction->execute(),
            'forms' => $forms,
            'filter' => $request->query('filter'),
            'message' => $request->session()->get('message'),
        ]);
    }

    public function create()
    {
        $this->authorize('create', Form::class);

        return Inertia::render('forms/create-form', $this->getFormFormOptionsAction->execute());
    }

    public function store(StoreFormRequest $request)
    {
        $this->authorize('create', Form::class);

        $data = DraftFormFormData::from($request->validated());

        $form = $this->createFormAction->execute($request->user(), $data);

        return redirect()->intended(route('forms.sections.index', ['form' => $form], false));
    }

    public function show(Form $form)
    {
        $this->authorize('view', $form);

        $form->load(['type', 'sections.questions.options', 'logicRules.conditions', 'responses']);

        return Inertia::render('forms/show-form', [
            'form' => $this->formDataMapper->forShow($form),
        ]);
    }

    public function edit(Form $form, Request $request)
    {
        $this->authorize('update', $form);

        $form->load(['type', 'sections']);

        return Inertia::render('forms/edit-form', [
            ...$this->getFormFormOptionsAction->execute(),
            'form' => $this->formDataMapper->forEdit($form),
            'message' => $request->session()->get('message'),
        ]);
    }

    public function update(UpdateFormRequest $request, Form $form)
    {
        $this->authorize('update', $form);

        $data = DraftFormFormData::from($request->validated());

        $this->updateFormAction->execute($form, $data);

        return back()->with('message', 'Formulario actualizado correctamente.');
    }

    public function destroy(Form $form)
    {
        $this->authorize('delete', $form);

        if ($form->responses()->whereIn('status', ['submitted', 'graded'])->exists()) {
            throw ValidationException::withMessages([
                'form' => 'No puedes eliminar un formulario con respuestas enviadas.',
            ]);
        }

        $this->deleteFormAction->execute($form);

        return back()->with('message', 'Formulario eliminado correctamente.');
    }
}
