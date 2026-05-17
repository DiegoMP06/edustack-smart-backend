<?php

namespace App\Modules\Blog\Infrastructure\Queries\Options;

use App\Modules\Shared\Domain\Contracts\QueryOptionsContract;
use Spatie\QueryBuilder\AllowedFilter;

class PublishedPostIndexQueryOptions implements QueryOptionsContract
{
    public function filters(): array
    {
        return [
            AllowedFilter::exact('post_type_id'),
            AllowedFilter::exact('is_featured'),
        ];
    }

    public function includes(): array
    {
        return ['type', 'categories', 'media', 'author'];
    }

    public function sorts(): array
    {
        return ['id', 'created_at', 'published_at', 'views_count'];
    }
}
