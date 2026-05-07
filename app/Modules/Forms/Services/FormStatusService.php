<?php

namespace App\Modules\Forms\Services;

use App\Models\Forms\Form;
use App\Modules\Forms\Actions\ToggleFormStatusAction;
use App\Modules\Forms\DTOs\FormStatusData;

class FormStatusService
{
    public function __construct(
        private ToggleFormStatusAction $toggleStatusAction,
    ) {}

    public function toggle(Form $form): Form
    {
        $data = FormStatusData::fromModel($form);

        return $this->toggleStatusAction->execute($form, $data);
    }
}
