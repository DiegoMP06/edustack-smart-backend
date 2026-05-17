<?php

namespace App\Modules\Projects\Application\UseCases\Query;

use App\Modules\Projects\Domain\Contracts\ProjectFormOptionsRepository;

class GetProjectFormOptionsAction
{
    public function __construct(
        private ProjectFormOptionsRepository $formOptionsRepository
    ) {}

    public function execute(): array
    {
        return [
            'categories' => $this->formOptionsRepository->getCategories(),
            'statuses' => $this->formOptionsRepository->getStatuses(),
        ];
    }
}
