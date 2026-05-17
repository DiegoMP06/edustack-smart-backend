<?php

namespace App\Modules\Forms\Infrastructure\Repositories;

use App\Models\Forms\FormResponse;
use App\Models\User;
use App\Modules\Forms\Domain\Contracts\FormReadRepository;
use App\Modules\Forms\Infrastructure\Queries\Options\FormIndexQueryOptions;
use App\Modules\Forms\Infrastructure\Queries\Options\FormResponseIndexQueryOptions;
use App\Modules\Shared\DTOs\Query\ListCollectionQueryParamsData;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\QueryBuilder;

class EloquentFormReadRepository implements FormReadRepository
{
    public function paginateUserForms(User $user, ListCollectionQueryParamsData $params): LengthAwarePaginator
    {
        $query = $user->forms();

        $queryBuilder = QueryBuilder::for($query)
            ->allowedFilters(FormIndexQueryOptions::allowedFilters())
            ->allowedSorts(FormIndexQueryOptions::allowedSorts())
            ->defaultSort(FormIndexQueryOptions::defaultSort())
            ->allowedIncludes(FormIndexQueryOptions::allowedIncludes());

        return $queryBuilder->paginate($params->per_page)->withQueryString();
    }

    public function paginateFormResponses(int $formId, ListCollectionQueryParamsData $params): LengthAwarePaginator
    {
        $query = FormResponse::where('form_id', $formId);

        $queryBuilder = QueryBuilder::for($query)
            ->allowedFilters(FormResponseIndexQueryOptions::allowedFilters())
            ->allowedSorts(FormResponseIndexQueryOptions::allowedSorts())
            ->defaultSort(FormResponseIndexQueryOptions::defaultSort())
            ->allowedIncludes(FormResponseIndexQueryOptions::allowedIncludes());

        return $queryBuilder->paginate($params->per_page)->withQueryString();
    }
}
