<?php

namespace App\Modules\Forms\Application\UseCases\Command;

use App\Models\Forms\Form;
use App\Models\User;
use App\Modules\Forms\Application\DTOs\DraftFormFormData;
use App\Modules\Forms\Domain\Contracts\FormWriteRepository;

class CreateFormAction
{
    public function __construct(
        private FormWriteRepository $formWriteRepository,
    ) {}

    public function execute(User $user, DraftFormFormData $data): Form
    {
        return $this->formWriteRepository->createForUser($user, $data);
    }
}
