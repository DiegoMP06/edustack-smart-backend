<?php

namespace App\Http\Controllers\Forms;

use App\Http\Controllers\Controller;
use App\Models\Forms\Form;

class FormStatusController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Form $form)
    {
        $this->authorize('update', $form);

        $form->is_published = ! $form->is_published;
        $form->save();

        return back()->with('message', 'Estado del formulario actualizado.');
    }
}
