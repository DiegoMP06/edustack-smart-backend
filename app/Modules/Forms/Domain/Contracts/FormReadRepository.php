<?php

namespace App\Modules\Forms\Domain\Contracts;

use App\Models\User;
use App\Modules\Shared\DTOs\Query\ListCollectionQueryParamsData;
use Illuminate\Pagination\LengthAwarePaginator;

interface FormReadRepository
{
    public function paginateUserForms(User $user, ListCollectionQueryParamsData $params): LengthAwarePaginator;

    public function paginateFormResponses(int $formId, ListCollectionQueryParamsData $params): LengthAwarePaginator;
}
