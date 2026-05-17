<?php

namespace App\Modules\Blog\Infrastructure\Repositories;

use App\Models\Blog\Post;
use App\Models\User;
use App\Modules\Blog\Domain\Contracts\PostReadRepository;
use App\Modules\Blog\Infrastructure\Queries\Options\PostIndexQueryOptions;
use App\Modules\Blog\Infrastructure\Queries\Options\PublishedPostIndexQueryOptions;
use App\Modules\Shared\Domain\Contracts\QueryOptionsContract;
use App\Modules\Shared\DTOs\Query\ListCollectionQueryParamsData;
use App\Modules\Shared\Queries\Filters\GlobalScoutFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class EloquentPostReadRepository implements PostReadRepository
{
    public function paginateUserPosts(User $user, ListCollectionQueryParamsData $data): LengthAwarePaginator
    {
        return $this->buildPaginator(
            query: Post::where('user_id', $user->id),
            data: $data,
            options: new PostIndexQueryOptions,
            defaultIncludes: ['type', 'categories', 'media'],
        );
    }

    public function paginatePublishedPosts(ListCollectionQueryParamsData $data): LengthAwarePaginator
    {
        return $this->buildPaginator(
            query: Post::where('is_published', true),
            data: $data,
            options: new PublishedPostIndexQueryOptions,
            defaultIncludes: ['categories', 'type', 'media', 'author'],
        );
    }

    private function buildPaginator(
        Builder $query,
        ListCollectionQueryParamsData $data,
        QueryOptionsContract $options,
        array $defaultIncludes,
    ): LengthAwarePaginator {
        return QueryBuilder::for($query)
            ->allowedFilters(
                AllowedFilter::custom('search', new GlobalScoutFilter),
                ...$options->filters(),
            )
            ->with($defaultIncludes)
            ->allowedIncludes(...$options->includes())
            ->allowedSorts(...$options->sorts())
            ->defaultSort('-id')
            ->paginate($data->per_page)
            ->withQueryString();
    }
}
