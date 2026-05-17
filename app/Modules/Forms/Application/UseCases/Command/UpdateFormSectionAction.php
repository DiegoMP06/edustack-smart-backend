<?php

namespace App\Modules\Forms\Application\UseCases\Command;

use App\Models\Forms\FormSection;
use App\Modules\Forms\Application\DTOs\FormSectionFormData;
use App\Modules\Forms\Domain\Contracts\FormWriteRepository;

class UpdateFormSectionAction
{
    public function __construct(private FormWriteRepository $formWriteRepository) {}

    public function execute(FormSection $section, FormSectionFormData $data): FormSection
    {
        return $this->formWriteRepository->updateSection($section, $data);
    }
}
