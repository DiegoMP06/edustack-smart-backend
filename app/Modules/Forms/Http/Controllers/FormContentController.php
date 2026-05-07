<?php

namespace App\Modules\Forms\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Forms\Form;
use App\Modules\Forms\DTOs\FormContentData;
use App\Modules\Forms\Http\Requests\UpdateFormContentRequest;
use App\Modules\Forms\Services\FormContentService;
use Illuminate\Http\Request;

class FormContentController extends Controller
{
    public function __construct(
        private FormContentService $contentService,
    ) {}

    /**
     * Show the content editor for the given model.
     */
    public function edit(Form $form, Request $request)
    {
        $this->authorize('update', $form);

        return inertia('forms/form-content', [
            'form' => $form,
            'edit' => $request->boolean('edit', false),
            'message' => $request->session()->get('message'),
        ]);
    }

    /**
     * Persist editor content for the given model.
     */
    public function update(Form $form, UpdateFormContentRequest $request)
    {
        $this->authorize('update', $form);

        $data = FormContentData::fromArray($request->validated());
        $this->contentService->update($form, $data);

        return back()->with('message', 'Form content saved successfully.');
    }
}
