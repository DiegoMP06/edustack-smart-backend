<?php

namespace App\Modules\Forms\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Forms\Form;
use App\Modules\Forms\Application\UseCases\Command\ToggleFormStatusAction;

class FormStatusController extends Controller
{
    public function __construct(
        private ToggleFormStatusAction $toggleFormStatusAction,
    ) {}

    public function __invoke(Form $form)
    {
        $this->authorize('update', $form);

        $this->toggleFormStatusAction->execute($form);

        return back();
    }
}
