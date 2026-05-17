<?php

namespace App\Modules\Forms\Application\UseCases\Command;

use App\Models\Forms\Form;
use App\Models\Forms\FormLogicRule;
use App\Modules\Forms\Application\DTOs\FormLogicRuleFormData;
use App\Modules\Forms\Domain\Contracts\FormWriteRepository;

class StoreFormLogicRuleAction
{
    public function __construct(private FormWriteRepository $formWriteRepository) {}

    public function execute(Form $form, FormLogicRuleFormData $data): FormLogicRule
    {
        return $this->formWriteRepository->createLogicRule($form, $data);
    }
}
