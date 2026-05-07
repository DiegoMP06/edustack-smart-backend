<?php

namespace App\Modules\Forms\Actions;

use App\Models\Forms\Form;
use App\Modules\Forms\DTOs\FormContentData;

class UpdateFormContentAction
{
    public function execute(Form $form, FormContentData $data): Form
    {
        $form->content = $data->content;
        $form->save();

        return $form;
    }
}
