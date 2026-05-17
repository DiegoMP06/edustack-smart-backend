<?php

namespace App\Modules\Forms\Application\UseCases\Command;

use App\Models\Forms\FormLogicRule;
use App\Modules\Forms\Domain\Contracts\FormWriteRepository;

class DeleteFormLogicRuleAction
{
    public function __construct(private FormWriteRepository $formWriteRepository) {}

    public function execute(FormLogicRule $rule): void
    {
        $this->formWriteRepository->deleteLogicRule($rule);
    }
}
