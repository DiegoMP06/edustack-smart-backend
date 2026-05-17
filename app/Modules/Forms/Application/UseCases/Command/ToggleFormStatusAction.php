<?php

namespace App\Modules\Forms\Application\UseCases\Command;

use App\Models\Forms\Form;
use App\Modules\Forms\Domain\Contracts\FormWriteRepository;

class ToggleFormStatusAction
{
    public function __construct(
        private FormWriteRepository $formWriteRepository,
    ) {}

    public function execute(Form $form): Form
    {
        return $this->formWriteRepository->toggleStatus($form);
    }
}
