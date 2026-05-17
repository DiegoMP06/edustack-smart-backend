<?php

namespace App\Modules\Events\Queries;

use App\Models\Events\Event;
use App\Modules\Shared\Concerns\ApiQueryable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Spatie\QueryBuilder\QueryBuilder;

class ListUserEventsQuery
{
    use ApiQueryable;

    public function build(array $params = [], array $filters = [], array $defaultIncludes = [], array $includes = [], array $sorts = []): QueryBuilder
    {
        return $this->buildQuery(
            subject: Event::where('user_id', $params['user_id']),
            filters: $filters,
            defaultIncludes: $defaultIncludes,
            includes: $includes,
            sorts: $sorts,
        );
    }

    public function get(array $params = [], array $filters = [], array $defaultIncludes = [], array $includes = [], array $sorts = []): Collection
    {
        return $this->build($params, $filters, $defaultIncludes, $includes, $sorts)->get();
    }

    public function paginate(array $params = [], int $perPage = 15, array $filters = [], array $defaultIncludes = [], array $includes = [], array $sorts = []): LengthAwarePaginator
    {
        return $this->build($params, $filters, $defaultIncludes, $includes, $sorts)->paginate($perPage);
    }

    public function count(array $params = [], array $filters = [], array $defaultIncludes = [], array $includes = [], array $sorts = []): int
    {
        return $this->build($params, $filters, $defaultIncludes, $includes, $sorts)->count();
    }
}
