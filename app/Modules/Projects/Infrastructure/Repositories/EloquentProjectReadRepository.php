<?php

namespace App\Modules\Projects\Infrastructure\Repositories;

use App\Models\Projects\Project;
use App\Models\User;
use App\Modules\Projects\Domain\Contracts\ProjectReadRepository;
use App\Modules\Projects\Infrastructure\Queries\Options\ProjectIndexQueryOptions;
use App\Modules\Projects\Infrastructure\Queries\Options\PublishedProjectIndexQueryOptions;
use App\Modules\Shared\DTOs\Query\ListCollectionQueryParamsData;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\QueryBuilder;

class EloquentProjectReadRepository implements ProjectReadRepository
{
    public function paginateUserProjects(User $user, ListCollectionQueryParamsData $params): LengthAwarePaginator
    {
        $options = new ProjectIndexQueryOptions;

        return QueryBuilder::for(Project::class)
            ->where('user_id', $user->id)
            ->allowedFilters($options->filters())
            ->allowedSorts($options->sorts())
            ->allowedIncludes($options->includes())
            ->defaultSort('-created_at')
            ->paginate($params->per_page)
            ->withQueryString();
    }

    public function paginatePublishedProjects(ListCollectionQueryParamsData $params): LengthAwarePaginator
    {
        $options = new PublishedProjectIndexQueryOptions;

        return QueryBuilder::for(Project::class)
            ->where('is_published', true)
            ->allowedFilters($options->filters())
            ->allowedSorts($options->sorts())
            ->allowedIncludes($options->includes())
            ->defaultSort('-published_at')
            ->paginate($params->per_page)
            ->withQueryString();
    }
}
