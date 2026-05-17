<?php

namespace App\Modules\Forms\Application\UseCases\Query;

use App\Modules\Forms\Domain\Contracts\FormReadRepository;
use App\Modules\Shared\DTOs\Query\ListCollectionQueryParamsData;
use Illuminate\Pagination\LengthAwarePaginator;

class ListFormResponsesAction
{
    public function __construct(
        private FormReadRepository $formReadRepository,
    ) {}

    public function execute(int $formId, ListCollectionQueryParamsData $params): LengthAwarePaginator
    {
        return $this->formReadRepository->paginateFormResponses($formId, $params);
    }
}
