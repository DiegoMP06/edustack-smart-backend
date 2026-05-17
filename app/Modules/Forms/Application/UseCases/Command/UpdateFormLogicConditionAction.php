<?php

namespace App\Modules\Forms\Application\UseCases\Command;

use App\Models\Forms\FormLogicCondition;
use App\Modules\Forms\Application\DTOs\FormLogicConditionFormData;
use App\Modules\Forms\Domain\Contracts\FormWriteRepository;

class UpdateFormLogicConditionAction
{
    public function __construct(private FormWriteRepository $formWriteRepository) {}

    public function execute(FormLogicCondition $condition, FormLogicConditionFormData $data): FormLogicCondition
    {
        return $this->formWriteRepository->updateLogicCondition($condition, $data);
    }
}
