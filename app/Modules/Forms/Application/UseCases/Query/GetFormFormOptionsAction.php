<?php

namespace App\Modules\Forms\Application\UseCases\Query;

use App\Modules\Forms\Domain\Contracts\FormFormOptionsRepository;

class GetFormFormOptionsAction
{
    public function __construct(
        private FormFormOptionsRepository $formOptionsRepository,
    ) {}

    public function execute(): array
    {
        return [
            'types' => $this->formOptionsRepository->getTypes(),
        ];
    }
}
