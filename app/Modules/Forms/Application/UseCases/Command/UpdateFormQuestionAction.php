<?php

namespace App\Modules\Forms\Application\UseCases\Command;

use App\Models\Forms\FormQuestion;
use App\Modules\Forms\Application\DTOs\FormQuestionFormData;
use App\Modules\Forms\Domain\Contracts\FormWriteRepository;

class UpdateFormQuestionAction
{
    public function __construct(private FormWriteRepository $formWriteRepository) {}

    public function execute(FormQuestion $question, FormQuestionFormData $data): FormQuestion
    {
        return $this->formWriteRepository->updateQuestion($question, $data);
    }
}
