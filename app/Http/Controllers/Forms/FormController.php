<?php

namespace App\Http\Controllers\Forms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Forms\StoreFormRequest;
use App\Http\Requests\Forms\UpdateFormRequest;
use App\Http\Resources\Forms\FormCollection;
use App\Models\Forms\Form;
use App\Models\Forms\FormType;
use App\Traits\ApiQueryable;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class FormController extends Controller
{
    use ApiQueryable;

    private function formData(): array
    {
        return [
            'types' => FormType::orderBy('order')->get(),
        ];
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Form::class);

        $forms = $this->buildQuery(
            $request->user()->forms(),
            defaultIncludes: ['type']
        )->paginate(20)->withQueryString();

        return Inertia::render('forms/forms', [
            ...$this->formData(),
            'forms' => new FormCollection($forms),
            'filter' => $request->query('filter'),
            'message' => $request->session()->get('message'),
        ]);
    }

    public function create()
    {
        $this->authorize('create', Form::class);

        return Inertia::render('forms/create-form', $this->formData());
    }

    public function store(StoreFormRequest $request)
    {
        $this->authorize('create', Form::class);

        $data = $request->validated();

        $form = $request->user()->forms()->create([
            ...$data,
            'is_published' => false,
            'is_active' => true,
        ]);

        return redirect()->intended(route('forms.sections.index', ['form' => $form], false));
    }

    public function show(Form $form)
    {
        $this->authorize('view', $form);

        return Inertia::render('forms/show-form', [
            'form' => (new FormCollection([$form->load(['type', 'sections.questions.options', 'logicRules.conditions', 'responses'])]))->first(),
        ]);
    }

    public function edit(Form $form, Request $request)
    {
        $this->authorize('update', $form);

        return Inertia::render('forms/edit-form', [
            ...$this->formData(),
            'form' => $form->load(['type', 'sections']),
            'message' => $request->session()->get('message'),
        ]);
    }

    public function update(UpdateFormRequest $request, Form $form)
    {
        $this->authorize('update', $form);

        $form->update($request->validated());

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

        $form->delete();

        return back()->with('message', 'Formulario eliminado correctamente.');
    }
}
