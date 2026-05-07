<?php

namespace App\Modules\Forms\DTOs;

use App\Models\Forms\Form;

readonly class FormStatusData
{
    public function __construct(
        public bool $isActive,
    ) {}

    public static function fromModel(Form $form): self
    {
        return new self(
            isActive: ! $form->is_published,
        );
    }
}
