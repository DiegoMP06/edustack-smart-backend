<?php

namespace App\Modules\Forms\Application\UseCases\Command;

use App\Models\Forms\FormLogicCondition;
use App\Modules\Forms\Domain\Contracts\FormWriteRepository;

class DeleteFormLogicConditionAction
{
    public function __construct(private FormWriteRepository $formWriteRepository) {}

    public function execute(FormLogicCondition $condition): void
    {
        $this->formWriteRepository->deleteLogicCondition($condition);
    }
}
