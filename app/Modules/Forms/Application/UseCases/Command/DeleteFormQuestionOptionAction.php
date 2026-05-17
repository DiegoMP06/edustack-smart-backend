<?php

namespace App\Modules\Forms\Application\UseCases\Command;

use App\Models\Forms\FormQuestionOption;
use App\Modules\Forms\Domain\Contracts\FormWriteRepository;

class DeleteFormQuestionOptionAction
{
    public function __construct(private FormWriteRepository $formWriteRepository) {}

    public function execute(FormQuestionOption $option): void
    {
        $this->formWriteRepository->deleteQuestionOption($option);
    }
}
