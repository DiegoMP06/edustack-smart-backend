<?php

namespace App\Modules\Forms\Application\UseCases\Command;

use App\Models\Forms\FormQuestion;
use App\Modules\Forms\Domain\Contracts\FormWriteRepository;

class DeleteFormQuestionAction
{
    public function __construct(private FormWriteRepository $formWriteRepository) {}

    public function execute(FormQuestion $question): void
    {
        $this->formWriteRepository->deleteQuestion($question);
    }
}
