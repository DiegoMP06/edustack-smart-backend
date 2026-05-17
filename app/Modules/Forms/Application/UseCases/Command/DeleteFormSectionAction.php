<?php

namespace App\Modules\Forms\Application\UseCases\Command;

use App\Models\Forms\FormSection;
use App\Modules\Forms\Domain\Contracts\FormWriteRepository;

class DeleteFormSectionAction
{
    public function __construct(private FormWriteRepository $formWriteRepository) {}

    public function execute(FormSection $section): void
    {
        $this->formWriteRepository->deleteSection($section);
    }
}
