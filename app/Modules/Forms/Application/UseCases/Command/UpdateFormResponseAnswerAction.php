<?php

namespace App\Modules\Forms\Application\UseCases\Command;

use App\Models\Forms\FormResponseAnswer;
use App\Modules\Forms\Application\DTOs\GradeAnswerFormData;
use App\Modules\Forms\Domain\Contracts\FormWriteRepository;

class UpdateFormResponseAnswerAction
{
    public function __construct(private FormWriteRepository $formWriteRepository) {}

    public function execute(FormResponseAnswer $answer, GradeAnswerFormData $data): FormResponseAnswer
    {
        return $this->formWriteRepository->updateResponseAnswer($answer, $data);
    }
}
