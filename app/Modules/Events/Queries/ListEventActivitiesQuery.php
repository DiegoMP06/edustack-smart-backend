<?php

namespace App\Modules\Events\Queries;

use App\Models\Events\EventActivity;
use App\Modules\Shared\Concerns\ApiQueryable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Spatie\QueryBuilder\QueryBuilder;

class ListEventActivitiesQuery
{
    use ApiQueryable;

    public function build(array $params = [], array $filters = [], array $defaultIncludes = [], array $includes = [], array $sorts = []): QueryBuilder
    {
        return $this->buildQuery(
            subject: EventActivity::where('event_id', $params['event_id']),
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
