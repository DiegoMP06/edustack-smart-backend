<?php

namespace App\Modules\Forms\Actions;

use App\Models\Forms\Form;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GetFormAction
{
    /**
     * Retrieve a model by its primary key.
     *
     * @throws ModelNotFoundException
     */
    public function execute(int $id): Form
    {
        $form = Form::find($id);

        if (! $form) {
            throw new ModelNotFoundException("The record with ID {$id} was not found.");
        }

        // If you prefer returning a DTO, map it here and adjust the return type.

        return $form;
    }
}
