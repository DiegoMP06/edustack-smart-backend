<?php

namespace App\Modules\Forms\Application\UseCases\Command;

use App\Models\Forms\Form;
use App\Models\Forms\FormSection;
use App\Modules\Forms\Application\DTOs\FormSectionFormData;
use App\Modules\Forms\Domain\Contracts\FormWriteRepository;

class StoreFormSectionAction
{
    public function __construct(private FormWriteRepository $formWriteRepository) {}

    public function execute(Form $form, FormSectionFormData $data): FormSection
    {
        return $this->formWriteRepository->createSection($form, $data);
    }
}
