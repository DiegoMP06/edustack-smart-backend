<?php

namespace App\Modules\Forms\Application\UseCases\Command;

use App\Models\Forms\Form;
use App\Models\Forms\FormQuestion;
use App\Modules\Forms\Application\DTOs\FormQuestionFormData;
use App\Modules\Forms\Domain\Contracts\FormWriteRepository;

class StoreFormQuestionAction
{
    public function __construct(private FormWriteRepository $formWriteRepository) {}

    public function execute(Form $form, FormQuestionFormData $data): FormQuestion
    {
        return $this->formWriteRepository->createQuestion($form, $data);
    }
}
