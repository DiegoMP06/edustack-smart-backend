<?php

namespace App\Modules\Forms\Application\UseCases\Command;

use App\Models\Forms\Form;
use App\Modules\Forms\Application\DTOs\DraftFormFormData;
use App\Modules\Forms\Domain\Contracts\FormWriteRepository;

class UpdateFormAction
{
    public function __construct(
        private FormWriteRepository $formWriteRepository,
    ) {}

    public function execute(Form $form, DraftFormFormData $data): Form
    {
        return $this->formWriteRepository->update($form, $data);
    }
}
