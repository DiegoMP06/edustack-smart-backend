<?php

namespace App\Modules\Forms\Application\UseCases\Command;

use App\Models\Forms\FormLogicCondition;
use App\Models\Forms\FormLogicRule;
use App\Modules\Forms\Application\DTOs\FormLogicConditionFormData;
use App\Modules\Forms\Domain\Contracts\FormWriteRepository;

class StoreFormLogicConditionAction
{
    public function __construct(private FormWriteRepository $formWriteRepository) {}

    public function execute(FormLogicRule $rule, FormLogicConditionFormData $data): FormLogicCondition
    {
        return $this->formWriteRepository->createLogicCondition($rule, $data);
    }
}
