<?php

namespace App\Modules\Forms\Services;

use App\Models\Forms\Form;
use App\Modules\Forms\Actions\UpdateFormContentAction;
use App\Modules\Forms\DTOs\FormContentData;

class FormContentService
{
    public function __construct(
        private UpdateFormContentAction $updateContentAction,
    ) {}

    public function update(Form $form, FormContentData $data): Form
    {
        return $this->updateContentAction->execute($form, $data);
    }
}
