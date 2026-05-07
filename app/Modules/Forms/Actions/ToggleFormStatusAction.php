<?php

namespace App\Modules\Forms\Actions;

use App\Models\Forms\Form;
use App\Modules\Forms\DTOs\FormStatusData;

class ToggleFormStatusAction
{
    public function execute(Form $form, FormStatusData $data): Form
    {
        $form->is_published = $data->isActive;
        $form->published_at = $data->isActive ? now() : null;
        $form->save();

        return $form;
    }
}
