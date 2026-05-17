<?php

namespace App\Modules\Forms\Application\UseCases\Command;

use App\Models\Forms\FormQuestionOption;
use App\Modules\Forms\Application\DTOs\FormQuestionOptionFormData;
use App\Modules\Forms\Domain\Contracts\FormWriteRepository;

class UpdateFormQuestionOptionAction
{
    public function __construct(private FormWriteRepository $formWriteRepository) {}

    public function execute(FormQuestionOption $option, FormQuestionOptionFormData $data): FormQuestionOption
    {
        return $this->formWriteRepository->updateQuestionOption($option, $data);
    }
}
