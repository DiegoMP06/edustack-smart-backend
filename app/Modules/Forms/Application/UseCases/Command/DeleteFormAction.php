<?php

namespace App\Modules\Forms\Application\UseCases\Command;

use App\Models\Forms\Form;
use App\Modules\Forms\Domain\Contracts\FormWriteRepository;

class DeleteFormAction
{
    public function __construct(
        private FormWriteRepository $formWriteRepository,
    ) {}

    public function execute(Form $form): void
    {
        $this->formWriteRepository->delete($form);
    }
}
