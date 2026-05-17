<?php

namespace App\Modules\Projects\Infrastructure\Queries\Options;

use App\Modules\Shared\Domain\Contracts\QueryOptionsContract;
use Spatie\QueryBuilder\AllowedFilter;

class ProjectIndexQueryOptions implements QueryOptionsContract
{
    public function filters(): array
    {
        return [
            AllowedFilter::exact('status', 'project_status_id'),
            AllowedFilter::exact('category', 'categories.id'),
            'name',
        ];
    }

    public function includes(): array
    {
        return ['status', 'categories', 'media', 'collaborators'];
    }

    public function sorts(): array
    {
        return ['created_at', 'name'];
    }
}
