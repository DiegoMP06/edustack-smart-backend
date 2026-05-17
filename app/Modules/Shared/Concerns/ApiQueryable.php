<?php

namespace App\Modules\Shared\Concerns;

use App\Modules\Shared\Queries\Filters\GlobalScoutFilter;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

trait ApiQueryable
{
    public function buildQuery(
        mixed $subject,
        array $filters = [],
        array $defaultIncludes = [],
        array $includes = [],
        array $sorts = [],
    ): QueryBuilder {
        return QueryBuilder::for($subject)
            ->allowedFilters(
                AllowedFilter::custom('search', new GlobalScoutFilter),
                ...$filters,
            )
            ->with($defaultIncludes)
            ->allowedIncludes(...$includes)
            ->allowedSorts(...$sorts)
            ->defaultSort('-id');
    }
}
