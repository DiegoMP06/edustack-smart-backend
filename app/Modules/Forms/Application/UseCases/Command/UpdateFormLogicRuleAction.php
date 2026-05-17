<?php

namespace App\Modules\Forms\Application\UseCases\Command;

use App\Models\Forms\FormLogicRule;
use App\Modules\Forms\Application\DTOs\FormLogicRuleFormData;
use App\Modules\Forms\Domain\Contracts\FormWriteRepository;

class UpdateFormLogicRuleAction
{
    public function __construct(private FormWriteRepository $formWriteRepository) {}

    public function execute(FormLogicRule $rule, FormLogicRuleFormData $data): FormLogicRule
    {
        return $this->formWriteRepository->updateLogicRule($rule, $data);
    }
}
