<?php

namespace App\Modules\Forms\Infrastructure\Queries\Options;

use App\Modules\Shared\Domain\Contracts\QueryOptionsContract;
use Spatie\QueryBuilder\AllowedFilter;

class FormResponseIndexQueryOptions implements QueryOptionsContract
{
    public static function allowedFilters(): array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('status'),
            AllowedFilter::exact('user_id'),
        ];
    }

    public static function allowedSorts(): array
    {
        return [
            'id',
            'started_at',
            'submitted_at',
            'score',
            'status',
        ];
    }

    public static function defaultSort(): string
    {
        return '-submitted_at';
    }

    public static function allowedIncludes(): array
    {
        return [
            'answers',
            'user',
        ];
    }
}
