<?php

namespace App\Modules\Forms\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Forms\Form;
use App\Modules\Forms\Services\FormStatusService;

class FormStatusController extends Controller
{
    public function __construct(
        private FormStatusService $statusService,
    ) {}

    /**
     * Toggle the model status flag.
     */
    public function __invoke(Form $form)
    {
        $this->authorize('update', $form);

        $this->statusService->toggle($form);

        return back()->with('message', 'Form status updated successfully.');
    }
}
