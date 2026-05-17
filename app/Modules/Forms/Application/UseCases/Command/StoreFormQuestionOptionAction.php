<?php

namespace App\Modules\Forms\Application\UseCases\Command;

use App\Models\Forms\FormQuestion;
use App\Models\Forms\FormQuestionOption;
use App\Modules\Forms\Application\DTOs\FormQuestionOptionFormData;
use App\Modules\Forms\Domain\Contracts\FormWriteRepository;

class StoreFormQuestionOptionAction
{
    public function __construct(private FormWriteRepository $formWriteRepository) {}

    public function execute(FormQuestion $question, FormQuestionOptionFormData $data): FormQuestionOption
    {
        return $this->formWriteRepository->createQuestionOption($question, $data);
    }
}
