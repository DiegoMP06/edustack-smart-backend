<?php

namespace App\Modules\Forms\Application\UseCases\Command;

use App\Models\Forms\Form;
use App\Models\Forms\FormResponse;
use App\Models\User;
use App\Modules\Forms\Application\DTOs\FormResponseFormData;
use App\Modules\Forms\Domain\Contracts\FormWriteRepository;

class StoreFormResponseAction
{
    public function __construct(private FormWriteRepository $formWriteRepository) {}

    public function execute(Form $form, FormResponseFormData $data, ?User $user): FormResponse
    {
        return $this->formWriteRepository->createResponse($form, $data, $user);
    }
}
